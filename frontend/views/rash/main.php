<?php

$this->title = 'Test Privacy';
?>
<div class="container">
    <div class="header">
        <h1>Mstest Parovz</h1>
        <p>Sizga yuborilgan test ID sini kiritishingiz lozim</p>
        <p class="m-0 p-0 text-danger"><?=$error?></p>
    </div>
    <?php \yii\widgets\ActiveForm::begin() ?>
    <div>
        <div class="form-group">
            <label for="test_id">Test ID</label>
            <input type="text" id="test_id" name="test_id" required>
        </div>
        <div class="form-group">
            <label for="full_name">Ism Famliyangiz</label>
            <input type="text" id="full_name" name="full_name" required>
            <input type="hidden" name="chat_id" id="chatId">
        </div>
        <button id="submit-button" type="submit" class="login-button">Tekshirish</button>
        <div class="signup">
            Nimadur  tushunarsizmi? <a target="_blank" href="https://t.me/Sardor8822">Bog'lanish</a>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end() ?>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    input{
        font-size: 18px!important;
    }

    body {
        background: linear-gradient(135deg, #667eea, #764ba2);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        width: 400px;
        max-width: 90%;
        padding: 40px;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .header h1 {
        color: #333;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .header p {
        color: #666;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #555;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: #764ba2;
        outline: none;
    }

    .forgot-password a {
        color: #764ba2;
        font-size: 14px;
        text-decoration: none;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    .login-button {
        width: 100%;
        background-color: #764ba2;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .login-button:hover {
        background-color: #663d91;
    }

    .signup {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }

    .signup a {
        color: #764ba2;
        text-decoration: none;
        font-weight: bold;
    }

    .signup a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .container {
            padding: 20px;
        }
    }
</style>

<!-- Telegram WebApp -->
<script src="https://telegram.org/js/telegram-web-app.js"></script>

<script>
    const TG = window.Telegram.WebApp;
    let USER_ID = TG.initDataUnsafe.user.id
    // let USER_ID = 7579528513
    TG.expand()

    window.addEventListener('DOMContentLoaded', () => {
        document.getElementById('chatId').value = USER_ID;
    });

</script>



<style>
    .loading {
        pointer-events: none;
        opacity: 0.6;
        position: relative;
    }

    .loading::after {
        content: "";
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        border: 2px solid #fff;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const button = document.querySelector('#submit-button');

        form.addEventListener('submit', function () {
            button.classList.add('loading');
            button.disabled = true;
        });
    });
</script>