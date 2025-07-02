<?php

namespace frontend\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (\Yii::$app->user->id != 1){
            die('Error Admin');
        }

        parent::init();

        // custom initialization code goes here
    }
}
