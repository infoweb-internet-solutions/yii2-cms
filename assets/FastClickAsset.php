<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class FastclickAsset extends AssetBundle
{
    public $sourcePath = '@bower/fastclick';

    public $js = [
        'lib/fastclick.js'
    ];
}
