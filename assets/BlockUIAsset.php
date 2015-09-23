<?php

namespace infoweb\cms\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Bootstrap hover asset files
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BlockUIAsset extends AssetBundle
{
    public $sourcePath = '@infoweb/cms/assets/js';
    public $js = [
        'jquery.blockUI.js',
    ];
}
