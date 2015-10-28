<?php

namespace infoweb\cms\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Fancybox asset files
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BootstrapHoverDropdownAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-hover-dropdown/';
    public $css = [];
    public $js = [
        'bootstrap-hover-dropdown.min.js'
    ];
}
