<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);

$config = \frontend\models\Config::findOne(['id' => 1]);
$user = \frontend\models\User::findOne(['id' => Yii::$app->user->id]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="/images/simply.svg" type="image/x-icon">
    <link rel="icon" href="/images/simply.svg" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/element-plus"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/element-plus/dist/index.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0/axios.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container-scroller">
    <nav class="navbar default-layout-navbar col-lg-12 col-12 fixed-top d-flex flex-row" style="margin-top: 0!important; padding: 0!important;">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="/"><b><?=$config->title?></b></a>
            <a class="navbar-brand brand-logo-mini" href="/"><img src="/images/simply.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
            </button>
            <div class="search-field d-none d-md-block">
                <form class="d-flex align-items-center h-100" action="#">
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <i class="input-group-text border-0 mdi mdi-magnify"></i>
                        </div>
                        <input type="text" class="form-control bg-transparent border-0" placeholder="Поиск ученика....">
                    </div>
                </form>
            </div>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item nav-profile dropdown">
                    <b>ID-<?=$config->center_id?></b>
                </li>
                <li class="nav-item nav-logout d-none d-lg-block">
                    <a class="nav-link" href="/site/logout">
                        <i class="mdi mdi-power"></i>
                    </a>
                </li>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="nav-profile-img">
                            <img src="/images/user.png" alt="image">
                            <span class="availability-status online"></span>
                        </div>
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black"><?=$user->first_name.' '.$user->last_name?></p>
                        </div>
                    </a>
                    <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item text-dark" href="/site/logout"><i class="mdi mdi-logout me-2 text-primary"></i> Выход </a>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>

    <?php if ($user->role == 3): ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper"  >
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-profile">
                    <a href="/" class="nav-link">
                        <div class="nav-profile-image">
                            <img src="/images/user.png" alt="profile">
                            <span class="login-status online"></span>
                        </div>
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"><?=$user->phone?></span>
                            <span class="text-secondary text-small"><?=\common\models\User::dropDownRole(false)[$user->role]?> <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i></span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/">
                        <span class="menu-title">Asosiy</span>
                        <i class="mdi mdi-home menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pupil">
                        <span class="menu-title">O'quvchilar</span>
                        <i class="mdi mdi-human-greeting menu-icon"></i>
                    </a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="/group-pupil">-->
<!--                        <span class="menu-title">Корзинка</span>-->
<!--                        <i class="mdi mdi-trash-can menu-icon"></i>-->
<!--                    </a>-->
<!--                </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="/groups">
                        <span class="menu-title">Guruhlar</span>
                        <i class="mdi mdi-contacts menu-icon"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-6 mt-3 ml-3 ms-3">
                        <?= Alert::widget() ?>
                    </div>
                </div>
               <?=$content?>
            </div>
        </div>
    </div>
    <?php else: ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper"  >
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="/" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="/images/user.png" alt="profile">
                                <span class="login-status online"></span>
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="font-weight-bold mb-2"><?=$user->phone?></span>
                                <span class="text-secondary text-small"><?=\common\models\User::dropDownRole(false)[$user->role]?> <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i></span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <span class="menu-title">Asosiy</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="/group-pupil">-->
<!--                            <span class="menu-title">Ketganlar</span>-->
<!--                            <i class="mdi mdi-trash-can menu-icon"></i>-->
<!--                        </a>-->
<!--                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="/groups">
                            <span class="menu-title">Guruhlar</span>
                            <i class="mdi mdi-contacts menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pupil">
                            <span class="menu-title">O'quvchilar</span>
                            <i class="mdi mdi-human-greeting menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employee">
                            <span class="menu-title">Xodimlar</span>
                            <i class="mdi mdi-contact-mail menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/finance">
                            <span class="menu-title">Moliya bo'limi</span>
                            <i class="mdi mdi-wallet-giftcard menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/room">
                            <span class="menu-title">Xonalar</span>
                            <i class="mdi mdi-door-open menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/transactions">
                            <span class="menu-title">To'lovlar ro'yxati</span>
                            <i class="mdi mdi-wallet menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/expenses">
                            <span class="menu-title">O'qituvchilar hisobi</span>
                            <i class="mdi mdi-wallet menu-icon"></i>
                        </a>
                    </li>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="/rash-control/main">-->
<!--                            <span class="menu-title">Mstest</span>-->
<!--                            <i class="mdi mdi-wallet menu-icon"></i>-->
<!--                        </a>-->
<!--                    </li>-->
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-6 mt-3 ml-3 ms-3">
                            <?= Alert::widget() ?>
                        </div>
                    </div>
                    <?=$content?>
                </div>
            </div>
        </div>
    <?php endif; ?>


</div>

<?php
$script = <<< JS
    jQuery(document).ready(function() {
        table = $(".datatables").DataTable({
            dom:dataTabDom,
            buttons: [
                'excel'
            ],
            paging:true,
            pageLength: 100,
            lengthMenu: [
                [100, 500, 1000, -1],
                [100, 500, 1000, 'Все']
            ],
            fixedHeader: true,
            deferRender:true,
            info:true,
            filter:true,
            // order: [[ 1, "asc" ]],
            language:dataTabLang
        });
    });
JS;
$this->registerJs($script);
?>

<style>
    .dataTables_filter label{
        width: 100%!important;
    }
    .dataTables_filter label input{
        width: 100%!important;
    }
    .pagination li{
        margin-top: 0!important;
    }
    .buttons-excel{
        color: #fff!important;
        background-color: #1bcfb4;
        border-color: #1bcfb4;
    }
    .s:hover{
        color: #fff!important;
    }
</style>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
