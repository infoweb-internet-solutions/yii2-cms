<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class PerfectScrollbarAsset extends AssetBundle
{
    public $sourcePath = '@bower/perfect-scrollbar';

    public $css = [
        'css/perfect-scrollbar.min.css',
    ];

    public $js = [
        'js/min/perfect-scrollbar.jquery.min.js'
    ];
}
