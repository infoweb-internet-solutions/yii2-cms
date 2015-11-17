<?php
namespace infoweb\cms\assets;

use yii\web\AssetBundle as AssetBundle;

class XEditableAsset extends AssetBundle
{
    public $sourcePath = '@bower/x-editable/dist/bootstrap3-editable';

    public $js = [
        'js/bootstrap-editable.js'
    ];

    public $css = [
        'css/bootstrap-editable.css'
    ];
}