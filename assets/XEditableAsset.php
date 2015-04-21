<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class XEditableAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/vendor/bootstrap3-editable';
    
    public $js = [
        'js/bootstrap-editable.js'
    ];
    
    public $css = [
        'css/bootstrap-editable.css'    
    ];
    
    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}