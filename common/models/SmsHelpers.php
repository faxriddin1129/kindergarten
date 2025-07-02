<?php

namespace common\models;

use yii\base\Model;
use yii\web\ForbiddenHttpException;

class SmsHelpers extends Model
{

    public function sendSms($data)
    {
        if (\Yii::$app->user->id != 1){
            throw new ForbiddenHttpException('Sizga sms yubprish uchun ruxsatlar cheklangan!');
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'notify.eskiz.uz/api/message/sms/send-batch',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . self::getSmsToken(),
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response,1);
    }

    private static function getSmsToken()
    {

        $checkCache = \Yii::$app->cache->get('sms_token_es_kiz_4');
        if ($checkCache) {
            return $checkCache['token'];
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'notify.eskiz.uz/api/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('email' => 'info@softbooking.uz', 'password' => '3q6qnTOKd8WsQAKYDPo51zSFYIL1x20neYT7aI7l'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, 1);
        $token = $data['data']['token'];
        \Yii::$app->cache->set('sms_token_es_kiz_4', ['token' => $token], 3600);
        return $token;
    }
}