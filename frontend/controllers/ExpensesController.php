<?php

namespace frontend\controllers;

use frontend\models\Expenses;
use frontend\models\search\ExpensesSearch;
use yii\web\NotFoundHttpException;

class ExpensesController extends AppController
{

    public function actionIndex($month = null)
    {

        if (!$month){
            $month = date('Y-m');
        }

        $data = Expenses::find()->andWhere(['month' => $month])->asArray()->all();


        return $this->render('index', [
            'data' => $data ,
            'month' => $month,
        ]);
    }


    public function actionCreate()
    {
        $model = new Expenses();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionViewCreate($id)
    {

        $model = new Expenses();
        $oldModel = $this->findModel($id);

        if ($this->request->isPost) {
            $model->tearcher_id = $oldModel->tearcher_id;
            $model->period = $oldModel->id;
            $model->month = $oldModel->month;
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(["/expenses/view?id=".$oldModel->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('view-create', [
            'model' => $model,
        ]);
    }


    public function actionView($id)
    {
        $oldModel = $this->findModel($id);
        $data = Expenses::find()->andWhere(['period' => $oldModel->id])->all();


        $model = new Expenses();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('view', [
            'model' => $model,
            'data' => $data,
            'oldModel' => $oldModel
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Expenses::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
