<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\GroupPupil;
use frontend\models\Groups;
use frontend\models\Pupil;
use Shuchkin\SimpleXLSX;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class ImportController extends AppController
{

    public function actionIndex()
    {
        $groups = Groups::find()->andWhere(['status' => Groups::STATUS_ACTIVE])->indexBy('id')->asArray()->all();
        $users = User::find()->andWhere(['role' => 5])->indexBy('phone')->asArray()->all();
        $data = [];
        $key = null;
        if (\Yii::$app->request->isPost) {
            $file = $_FILES['file'];
            $xlsx = SimpleXLSX::parse($file['tmp_name']);
            $data = $xlsx->rows();
            unset($data[0]);
            $key = time() . \Yii::$app->user->id . 'xlsx';
            \Yii::$app->cache->set($key, $data, 600);
        }
        return $this->render('index', [
            'data' => $data,
            'key' => $key,
            'groups' => $groups,
            'users' => $users
        ]);
    }

    public function actionPupil($key)
    {
        $groups = Groups::find()->andWhere(['status' => Groups::STATUS_ACTIVE])->asArray()->all();
        $groupsIds = ArrayHelper::getColumn($groups, 'id');
        $data = \Yii::$app->cache->get($key);
        $db = yii::$app->db->beginTransaction();
        foreach ($data as $k => $v) {
            if (!in_array($v[3], $groupsIds)) {
                $db->rollBack();
                throw new BadRequestHttpException('Group not found in database');
            }

            $pupil = new User();
            $pupil->first_name = $v[0];
            $pupil->last_name = $v[1];
            $pupil->phone = $v[2];
            $pupil->username = $v[2] . Yii::$app->security->generateRandomString(10);
            $pupil->status = 10;
            $pupil->role = 5;
            $pupil->setPassword($pupil->username);
            $pupil->generateAuthKey();
            $pupil->generateEmailVerificationToken();
            $pupil->save();

            $Pm = new Pupil();
            $Pm->user_id = $pupil->id;
            $Pm->first_name = $pupil->first_name;
            $Pm->last_name = $pupil->last_name;
            $Pm->save();


            $model = new GroupPupil();
            $model->group_id = $v[3];
            $model->pupil_id = $Pm['id'];
            $model->date = $v[4];
            $model->status = 1;
            $model->leave_date = null;
            $model->created_by = Yii::$app->user->id;
            $model->updated_by = Yii::$app->user->id;
            $model->comment = 'Import';
            $model->save();

        }
        $db->commit();
        $this->redirect('/import/index');
    }

    public function actionOpen()
    {
        $this->layout = 'login';
        return $this->render('open');
    }
}
