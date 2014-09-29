<?php
namespace infoweb\cms;

use yii\web\AssetBundle as AssetBundle;

class CMSAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/';
    
    public $css = [
        'css/style.css'
    ];
    
    public $js = [
        'js/cms.js',
    ];
    
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}