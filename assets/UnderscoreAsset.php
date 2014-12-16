<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class UnderscoreAsset extends AssetBundle
{
    public $sourcePath = '@bower/underscore';
    
    public $js = [
        'underscore-min.js',
    ];
}
