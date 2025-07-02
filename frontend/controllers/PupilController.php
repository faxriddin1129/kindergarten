<?php

namespace frontend\controllers;

use frontend\models\form\PupilForm;
use frontend\models\GroupPupil;
use frontend\models\Pupil;
use frontend\models\search\PupilSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PupilController extends AppController
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    public function actionIndex()
    {
        $searchModel = new PupilSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionPupilError()
    {
        $data = Pupil::find()
            ->select(['public.pupil.id', 'public.user.id as user_id', 'public.pupil.first_name', 'public.pupil.last_name', 'public.user.phone'])
            ->leftJoin('public.user', 'public.user.id = public.pupil.user_id')
            ->andWhere(['or',
                ['public.user.phone' => null],
                ['public.user.phone' => '']
            ])
            ->asArray()->all();
        return $this->render('pupil-error', [
            'data' => $data,
        ]);
    }


    public function actionCreate()
    {
        $model = new PupilForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = new PupilForm(['id' => $id]);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $pupil = $this->findModel($id);
        $model->first_name = $pupil->first_name;
        $model->last_name = $pupil->last_name;
        $model->phone = $pupil->user->phone;

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Pupil::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    public function actionPupilSearch($query, $group_id = null){
        Yii::$app->response->format = 'json';

        if ($group_id){
            $pup = GroupPupil::find()
                    ->select(['group_pupil.id','group_pupil.status','pupil.first_name','pupil.last_name', 'us.phone'])
                ->leftJoin('pupil','pupil.id=group_pupil.pupil_id')
                ->leftJoin('public.user us','us.id=pupil.user_id')
                ->andWhere(['group_pupil.group_id' => $group_id])
                ->andFilterWhere(['ilike','pupil.first_name', $query])
                ->orFilterWhere(['ilike','pupil.last_name', $query])
                ->orFilterWhere(['ilike','us.phone', $query])
                ->asArray()->all();
            return $pup;
        }

        $query =  Pupil::find()
            ->select(['pupil.first_name', 'pupil.last_name', 'pupil.id', 'us.phone as phone'])
            ->leftJoin('user us','us.id=pupil.user_id')
            ->andFilterWhere(['ilike','pupil.first_name', $query])
            ->orFilterWhere(['ilike','pupil.last_name', $query])
            ->orFilterWhere(['ilike', 'us.phone', $query])->asArray()->all();

        return $query;
    }
}
