<?php

namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class GeneratorAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/';

    public $js = [
        'js/Generator.js',
    ];

    public $depends = [
        'backend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
    ];
}