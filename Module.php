<?php

namespace infoweb\cms;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'infoweb\cms\controllers';
    
    public function init()
    {
        parent::init();
        
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}
