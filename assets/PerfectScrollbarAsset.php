<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class PerfectScrollbarAsset extends AssetBundle
{
    public $sourcePath = '@bower/perfect-scrollbar/min';

    public $css = [
        'perfect-scrollbar.min.css',
    ];

    public $js = [
        'perfect-scrollbar.min.js'
    ];
}
