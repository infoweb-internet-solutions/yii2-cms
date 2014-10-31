<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class BootBoxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fastclick';
    
    public $js = [
        'fastclick.js'
    ];
}