<?php

namespace common\models;

use yii\base\Model;

class BotHelpers extends Model
{
    const TOKEN = '7145253446:AAFgjuQpxUFvGfg4uPiQyBrPAHtgwH6opq0';
    const GROUP_MAIN = -1002352021201;

    public static function sendMessage($data = [])
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,'https://api.telegram.org/bot'.self::TOKEN.'/sendMessage');
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

}
