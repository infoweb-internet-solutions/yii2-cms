<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class PerfectScrollbarAsset extends AssetBundle
{
    public $sourcePath = '@bower/perfect-scrollbar';

    public $js = [
        'lib/perfect-scrollbar.js'
    ];
}
