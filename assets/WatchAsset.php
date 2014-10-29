<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class WatchAsset extends AssetBundle
{
    public $sourcePath = '@bower/watch/src';
    
    public $js = [
        'watch.min.js'
    ];
}