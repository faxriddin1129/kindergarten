<?php

namespace frontend\controllers;

use frontend\models\rash\RashAnswer;
use frontend\models\rash\RashControl;
use frontend\models\rash\RashQuiz;

class RashControlController extends AppController
{

    public function actionMain()
    {

        $model = new RashControl();
        if ($model->load(\Yii::$app->request->post()) && $model->save()){
            return  $this->redirect(["/rash-control/main"]);
        }

        $data = RashControl::find()->asArray()->all();
        return $this->render('main',[
            'data' => $data,
            'model' => $model
        ]);
    }

    public function actionQuiz($id)
    {

        $model = new RashQuiz();
        if ($model->load(\Yii::$app->request->post()) && $model->save()){
            return  $this->redirect(["/rash-control/quiz?id=".$id]);
        }
        $data = RashQuiz::find()->andWhere(['rash_control_id' => $id])->asArray()->all();
        return $this->render('quiz',[
            'data' => $data,
            'model' => $model,
            'id' => $id
        ]);
    }

    public function actionUpdate($id)
    {
        $model = RashControl::findOne($id);
        if ($model->status == 'Close'){
            $model->status = 'Open';
        }elseif ($model->status == 'Open'){
            $model->status = 'Close';
        }
        $model->save();
        return  $this->redirect(["/rash-control/main"]);
    }

    public function actionQuizDelete($id, $quiz_id)
    {
        $model = RashQuiz::findOne($quiz_id);
        $model->delete();
        return  $this->redirect(["/rash-control/quiz?id=".$id]);
    }

    public function actionUser($id)
    {
        $data = RashAnswer::find()->andWhere(['rash_control_id' => $id])->asArray()->all();
        return $this->render('user',[
            'data' => $data,
        ]);
    }

    public function actionRash($id)
    {
        $this->layout = 'login';
        $model = RashControl::findOne($id);
        $answers = RashAnswer::find()->andWhere(['rash_control_id' => $id])->asArray()->all();
        $questions = RashQuiz::find()->andWhere(['rash_control_id' => $id])->orderBy(['number' => SORT_ASC])->asArray()->all();
        return $this->render('rash',[
            'model' => $model,
            'answers' => $answers,
            'questions' => $questions
        ]);
    }

}
