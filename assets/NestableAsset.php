<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class MoxieManagerAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/catalogue/assets/';
    
    public $js = [
        'js/jquery.nestable.js',
    ];
}
