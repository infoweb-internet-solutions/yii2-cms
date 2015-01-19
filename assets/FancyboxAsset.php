<?php

namespace infoweb\cms\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Fancybox asset files
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FancyboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/fancybox/source';
    public $css = [
        'jquery.fancybox.css'
    ];
    public $js = [
        'jquery.fancybox.pack.js'
    ];
}
