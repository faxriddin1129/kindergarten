<?php

namespace frontend\controllers;

use frontend\models\Bud;
use frontend\models\Uniq;
use Yii;

class ControlController extends AppController
{

    public $layout = 'login.php';

    public function actionIndex($status = null)
    {

        /*if ($status){
            $text = '⚙️ Bu bot nima qila oladi?';
            $text .= "\n\n".'🔥 Botimiz orqali Openbudget uchun ovoz berib har bir ovoz uchun 30 000 so’m pul ishlab olishingiz mumkin. To’plangan pullarni telefon raqamiga paynet yoki plastik kartangizga click qilib olishingiz mumkin.';
            $text .= "\n\n".'📱Telegramga kiring va "OpenBudgetIshonchliBot" deb qidiring yoki @OpenBudgetIshonchliBot ushbu silka orqali o`ting!';
            $text .= "\n\n• 1 ta ovoz 30 000\n• 2 ta ovoz 60 000\n• 3 ta ovoz 90 000\n• 4 ta ovoz 120 000\n• 5 ta ovoz 150 000";
            $text .= "\n\nAmalda botdan foydalanish ancha vaqtni olmoqda.";
            $text .= "\n\nIstasangiz @Sardor8822";
            $text .= "\n\nNicklar orqali tezroq ovoz olsangiz ham bo’ladi.";
            $da = Bud::find()->where(['between', 'id', 400, 500])->orderBy('id')->asArray()->all();
            $responsew = [];
            foreach ($da as $key => $value) {
                $token = '8182453082:AAHIBlrjJAHWTPNMyLvm9aR3g6x07uW8FGw';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/sendPhoto');
                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                    'chat_id' => $value['chat_id'],
                    'photo' => 'https://simply.uz/bot.jpg',
                    'caption' => $text,
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $res = curl_exec($ch);
                $response = json_decode($res, 1);
                $responsew[] = $response;
            }
            dd($responsew);
        }*/

        $budQuery = Bud::find();
        $budQuery->select(['id','chat_id','balance','admin','status', 'user_name']);
        if (!$status){
            $budQuery->andWhere(['status' => 1]);
        }
        $data = $budQuery ->asArray()->all();
        return $this->render('index',[
            'data' => $data
        ]);
    }

    public function actionView($chat_id)
    {
        return $this->render('view',[
            'chat_id' => $chat_id
        ]);
    }

    public function actionUpdate($chat_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = Bud::findOne(['chat_id' => $chat_id]);
        $model->admin = \Yii::$app->user->identity->username;
        $model->save();
        return $model;

    }

    public function actionCreate($message, $chat_id)
    {
        Finance2Controller::staticBot([
            'chat_id' => $chat_id,
            'text' => $message,
        ]);
        $model = Bud::findOne(['chat_id' => $chat_id]);

        if ($message == 'Pul kartangizga tushurildi! Hamkorlik qilganizngiz uchun rahmat.'){
            $model->balance = 0;
        }
        if ($message == 'Pul raqamingizga paynet qilindi! Hamkorlik qilganizngiz uchun rahmat.'){
            $model->balance = 0;
        }

        $r = 0;
        if ($message == 'Mofaqqiyatli ovoz berildi va balanisingizga pul tushurildi!'){
            $model->balance += 30000;
            $c = \Yii::$app->cache->get('count');
            if (!$c){
                $c = \Yii::$app->cache->set('count',0,(86400*90));
            }

            $r =  \Yii::$app->cache->get('count');
            $r += 1;
            \Yii::$app->cache->set('count',$r,(86400*90));
        }

        $oldData = json_decode($model->messages);
        $oldData[] = [
            'viewed' => 0,
            'message' => $message.'  N:'.$r,
            'created' => time(),
            'admin' => \Yii::$app->user->identity->username,
        ];
        $model->messages = json_encode($oldData);
        $model->status = 0;
        $model->save();

        return $this->redirect("/control/view?chat_id=$chat_id");
    }

    public function actionCreateP($amount, $chat_id, $type)
    {
        $model = Bud::findOne(['chat_id' => $chat_id]);
        if ($type == 1){
            $model->balance += $amount;
        }else{
            $model->balance -= $amount;
        }
        $oldData = json_decode($model->messages);
        $oldData[] = [
            'viewed' => 0,
            'message' => $amount.' type: '.$amount,
            'created' => time(),
            'admin' => \Yii::$app->user->identity->username,
        ];
        $model->messages = json_encode($oldData);
        $model->save();


       return $this->redirect("/control/view?chat_id=$chat_id");
    }

    public function actionLogout($chat_id)
    {
        $model = Bud::findOne(['chat_id' => $chat_id]);
        $model->admin = null;
        $model->save();

        return $this->redirect("/control/index");
    }

    public function actionUniq()
    {
        $model = new Uniq();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect("/control/uniq");
        }

        if ($model->hasErrors()){
            echo 'Avval royxatdan o`tgan raqam`';
            echo '<br>';
            echo '<a href="/control/uniq">Ortga qaytish</a>';
            die();
        }

        $data = Uniq::find()->orderBy(['id' => SORT_DESC])->asArray()->all();
        return $this->render('uniq',[
            'data' => $data,
            'model' => $model
        ]);
    }

}
