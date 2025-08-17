<?php

namespace bot\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BotController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout', 'index', 'create', 'update', 'pupil-search', 'create-pupil-group', 'delete-pupil-group', 'view', 'group-pupils',
                            'group-pupils-checking', 'group-pupils-payment', 'invoices', 'create-invoice', 'transfer', 'debts', 'groups', 'pupil', 'update-all',
                            'update-invoice-all', 'sms-all', 'update-invoice', 'sms-all-in', 'pupil-error'
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


    public $layout = false;
    public $enableCsrfValidation = false;
    public $chatId;
    public $text;
    public $userId;
    public $phone;
    public $username;
    public $message_id;


    public function actionIndex()
    {

        die('ok');

        //offline
        $updates = $this->getUpdates();
        $request = end($updates['result']);

//        online
//        $updates = file_get_contents("php://input");
//        $request = json_decode($updates, true);

        if (isset($request['message']['voice']) || isset($request['message']['photo']) || isset($request['message']['video']) || isset($request['message']['location']) || isset($request['message']['document'])){
            die('No');
        }

        else {
            $message = $request['message'];
            $from = $message['from'];
            $chat = $message['chat'];
            $this->message_id = $request['message']['message_id'];
            $this->userId = $from['id'];
            $userNameCheck = array_key_exists('username', $from);
            if ($userNameCheck) {
                $this->username = $from['username'];
            }
            $this->chatId = $chat['id'];
            if ($this->text == '/start') { $this->main(); die(); }
        }
    }




    public function main()
    {
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => 'salom',
            'parse_mode' => 'html',
        ]);
    }

    public function menu()
    {
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => Yii::t('app',31),
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => Yii::t('app',32)],
                    ],
                    [
                        ['text' => Yii::t('app',64)],
                        ['text' => Yii::t('app',65)],
                    ],
                    [
                        ['text' => Yii::t('app',58)],
                        ['text' => Yii::t('app',60)],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
        die();
    }


    public function bot($method, $data = [])
    {
        $token = '7271605400:AAEmfPk3wWlEJruoGw-Hvt_k0d98R_Jf2oY';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/' . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

    public static function staticBot($method, $data = [])
    {
        $token = '7271605400:AAEmfPk3wWlEJruoGw-Hvt_k0d98R_Jf2oY';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/' . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

    public function getUpdates($data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot7271605400:AAEmfPk3wWlEJruoGw-Hvt_k0d98R_Jf2oY/getUpdates');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

}
