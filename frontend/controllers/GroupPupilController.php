<?php

namespace frontend\controllers;

use frontend\models\GroupPupil;
use frontend\models\search\GroupPupilSearch;

class GroupPupilController extends AppController
{

    public function actionIndex()
    {
        $searchModel = new GroupPupilSearch(['status' => GroupPupil::STATUS_LEAVE]);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
