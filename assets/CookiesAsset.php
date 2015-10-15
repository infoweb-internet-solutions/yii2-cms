<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class CookiesAsset extends AssetBundle
{
    public $sourcePath = '@bower/js-cookie';

    public $js = [
        'src/js.cookie.js'
    ];
}