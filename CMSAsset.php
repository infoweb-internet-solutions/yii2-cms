<?php
namespace infoweb\cms;

use yii\web\AssetBundle as AssetBundle;

class CMSAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/';
    
    public $css = [
        'http://fonts.googleapis.com/css?family=Lato:100,200,300,400,500,600,700',
        'css/sb-admin-2.css',
        'css/main.css'
    ];
    
    public $js = [
        'js/i18n.js',
        'js/cms.js',
        'js/main.js'
    ];
    
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'infoweb\cms\assets\BootBoxAsset',
        'infoweb\cms\assets\FastClickAsset',
        'infoweb\cms\assets\CookiesAsset',
        'kartik\sidenav\SideNavAsset',
        'infoweb\cms\assets\MoxieManagerAsset',
        'infoweb\cms\assets\PerfectScrollbarAsset',
        'infoweb\cms\assets\UnderscoreAsset',
        'infoweb\cms\assets\FancyboxAsset',
    ];
}