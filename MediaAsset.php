<?php
namespace infoweb\cms;

use yii\web\AssetBundle as AssetBundle;

class MediaAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/';
    
    public $css = [
        'css/media.css',
    ];
    
    public $js = [
        'js/media.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'infoweb\cms\assets\CookiesAsset',
        'infoweb\cms\assets\MoxieManagerAsset',
    ];
}