<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AppController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'open'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout', 'index', 'create', 'update', 'pupil-search', 'create-pupil-group', 'delete-pupil-group', 'view', 'group-pupils',
                            'group-pupils-checking', 'group-pupils-payment', 'invoices', 'create-invoice', 'transfer', 'debts', 'groups', 'pupil', 'update-all',
                            'update-invoice-all', 'sms-all', 'update-invoice', 'sms-all-in', 'pupil-error', 'sms', 'check', 'create-p', 'uniq', 'view-create',
                            'main', 'answer', 'quiz', 'quiz-delete', 'user', 'rash', 'delete'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                    'delete' => ['post','get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


}