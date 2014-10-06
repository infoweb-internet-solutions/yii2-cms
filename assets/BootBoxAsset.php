<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class BootBoxAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootbox';
    
    public $js = [
        'bootbox.js'
    ];
}