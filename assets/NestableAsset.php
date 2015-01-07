<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class NestableAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/catalogue/assets/';
    
    public $js = [
        'js/jquery.nestable.js',
    ];
}
