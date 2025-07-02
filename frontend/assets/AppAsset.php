<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/template/assets/vendors/mdi/css/materialdesignicons.min.css',
        '/template/assets/vendors/css/vendor.bundle.base.css',
        '/template/assets/css/style.css',
        '/css/site.css',
        "/datatables/datatables.min.css",
    ];

    public $js = [
        "https://unpkg.com/vue@3/dist/vue.global.js",
//        "/template/assets/vendors/js/vendor.bundle.base.js",
        "/template/assets/js/jquery.cookie.js",
        "/template/assets/js/off-canvas.js",
        "/template/assets/js/hoverable-collapse.js",
        "/template/assets/js/misc.js",
        "/template/assets/js/todolist.js",
        "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.0/gsap.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TimelineMax.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.2/vanilla-tilt.min.js",
        "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js",
        "/datatables/datatables.min.js",
        "/datatables/datatables_conf.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
