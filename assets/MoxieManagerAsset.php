<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class MoxieManagerAsset extends AssetBundle
{
    public $sourcePath = '@mihaildev/ckeditor/editor/plugins/moxiemanager/';
    
    public $js = [
        'js/moxman.loader.min.js',
    ];
}