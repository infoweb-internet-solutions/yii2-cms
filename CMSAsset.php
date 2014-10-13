<?php
namespace infoweb\cms;

use yii\web\AssetBundle as AssetBundle;

class CMSAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/';
    
    public $css = [
        'css/sb-admin-2.css',
        'css/main.css'
    ];
    
    public $js = [
        'js/i18n.js',
        'js/cms.js',
        'js/main.js',
    ];
    
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'infoweb\cms\assets\BootBoxAsset'
    ];
}