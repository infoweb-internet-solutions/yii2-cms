<?php

namespace infoweb\cms\helpers;

use Yii;

class LanguageHelper
{

    /**
     * Check if language is an rtl language
     *
     * @param $language
     * @return bool
     */
    public static function isRtl($language)
    {
        if (!isset(Yii::$app->params['rtl'])) {
            return false;
        }

        if (in_array($language, Yii::$app->params['rtl'])) {
            return true;
        };

        return false;
    }
}
