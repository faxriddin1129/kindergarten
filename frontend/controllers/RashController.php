<?php

namespace frontend\controllers;

use frontend\models\rash\RashAnswer;
use frontend\models\rash\RashControl;
use frontend\models\rash\RashQuiz;
use yii\web\Controller;

class RashController extends Controller
{
    public function beforeAction($action)
    {
        if ($action->id == 'send') {
            $this->enableCsrfValidation = false;
        }
        return $action;
    }

    public $layout = 'login';

    public function actionMain($error = null)
    {
        if (\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post();
            $id = (string)$data['test_id'];
            $model = RashControl::findOne(['quiz_id' => $id, 'status' => 'Open']);
            if (!$model) {
                $error = 'Bunday ID lik test topilmadi!';
            } else {
                $check = RashAnswer::find()->andWhere(['rash_control_id' => $model->id, 'chat_id' => $data['chat_id']])->one();
                if ($check){
                    $error = 'Siz ushbu testga oldin topshirgansiz!';
                    return $this->render('main', [
                        'error' => $error
                    ]);
                }
                return $this->redirect(["/rash/check?id=" . $id.'&full_name='.$data['full_name']]);
            }
        }
        return $this->render('main', [
            'error' => $error
        ]);
    }

    public function actionCheck($id, $full_name)
    {
        $model = RashControl::findOne(['quiz_id' => $id, 'status' => 'Open']);
        if (!$model) {
            return $this->redirect(['/rash/main?error=Bunday ID lik test topilmadi!']);
        }

        $questions = RashQuiz::find()->select(['id', 'rash_control_id', 'type', 'number'])->asArray()->andWhere(['rash_control_id' => $model->id])->orderBy(['number' => SORT_ASC])->all();

        return $this->render('check', [
            'model' => $model,
            'questions' => $questions,
            'full_name' => $full_name
        ]);
    }

    public function actionSend()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw, true);

        $check = RashAnswer::find()
            ->andWhere(['rash_control_id' => $decoded['rash_id']])
            ->andWhere(['chat_id' => $decoded['chat_id']])
            ->one();
        if ($check){
            \Yii::$app->response->statusCode = 403;
            return [
                'status' => false,
                'msg' => 'Oldin ushbu testni topshirgansiz'
            ];
        }

        $model = new RashAnswer();
        $model->rash_control_id = $decoded['rash_id'];
        $model->chat_id = $decoded['chat_id'];
        $model->full_name = $decoded['full_name'];
        $model->answers = json_encode($decoded['answers']);
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();

        \Yii::$app->response->statusCode = 200;
        return[
            'status' => true,
            'msg' => 'OK',
            'data' => $decoded
        ];
    }

    public function actionBot()
    {
        $updates = file_get_contents("php://input");
        $request = json_decode($updates, true);

        if (isset($request['message']['voice']) || isset($request['message']['photo']) || isset($request['message']['video']) || isset($request['message']['location']) || isset($request['message']['document'])){
            die('No');
        }

        $message = $request['message'];
        $chat = $message['chat'];
        $chatId = $chat['id'];

        $this->bot('sendMessage', [
            'chat_id' => $chatId,
            'text' => "Assalom alaykum\nHurmatli foydalanuvchi. Parvoz markazi tomonidan ishlab chiqilgan Mstest topshirish botiga\nPastdagi tugmani bosing va topshiring\n👇👇👇",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        ['text' => 'Test Topshirish', 'web_app' => ['url' => 'https://simply.uz/rash/main']],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);


    }

    public function bot($method, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot7620525946:AAGULOHd4EBOvZJR0aMLKz-sJMJnE6VxbJk/' . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

    public function actionRash($id)
    {
        dd($id);
    }

}
