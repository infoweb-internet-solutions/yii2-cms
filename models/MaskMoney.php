<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2015
 * @package yii2-money
 * @version 1.2.1
 */

namespace infoweb\cms\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\money\MaskMoneyAsset;

/**
 * A money mask input widget styled for Bootstrap 3 based on the jQuery-maskMoney plugin,
 * which offers a simple way to create masks to your currency form fields.
 *
 * @see https://github.com/plentz/jquery-maskmoney
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class MaskMoney extends \kartik\money\MaskMoney
{
    /**
     * @inherit doc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        MaskMoneyAsset::register($view);
        $id = 'jQuery("#' . $this->_displayOptions['id'] . '")';
        $idSave = 'jQuery("#' . $this->options['id'] . '")';
        $plugin = $this->_pluginName;
        $this->registerPlugin($plugin, $id);
        $js = <<< JS
var val = parseFloat({$idSave}.val()) * 100;
{$id}.{$plugin}('mask', val);
{$id}.on('change', function () {
     var numDecimal = {$id}.{$plugin}('unmasked')[0];
    {$idSave}.val(numDecimal);
    {$idSave}.trigger('change');
});
JS;
        $view->registerJs($js);
    }

}
