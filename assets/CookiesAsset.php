<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class CookiesAsset extends AssetBundle
{
    public $sourcePath = '@bower/cookies-js';
    
    public $js = [
        'src/cookies.js'
    ];
}