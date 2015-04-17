<?php

namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class ImageAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/';

    public $css = [
        'css/images.css',
    ];
    public $js = [
        'js/images.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'infoweb\cms\assets\BootBoxAsset',
        //'infoweb\cms\assets\BootstrapGrowlAsset',
        'kartik\growl\GrowlAsset',
        'infoweb\cms\assets\FancyboxAsset',
    ];
}