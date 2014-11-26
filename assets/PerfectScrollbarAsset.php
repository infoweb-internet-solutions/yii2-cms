<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class PerfectScrollbarAsset extends AssetBundle
{
    public $sourcePath = '@bower/perfect-scrollbar';

    public $css = [
        'min/perfect-scrollbar.min.css',
    ];

    public $js = [
        'min/perfect-scrollbar.min.js'
    ];
}
