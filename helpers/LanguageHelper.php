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

    /**
     * Returns the different parts of the provided or current language code.
     *     "en-US" => ['en', 'us']
     *
     * @return array
     */
    public static function getLanguageCodeParts($language = null)
    {
        return array_map('strtolower', explode('-', ($language) ? $language : Yii::$app->language));
    }

    /**
     * Returns only the language part of the provided or current language code.
     *     "en-US" => 'en'
     *
     * @return string
     */
    public static function getLanguage($language = null)
    {
        return self::getLanguageCodeParts($language)[0];
    }

    /**
     * Returns only the country part of the language code.
     *     "en-US" => 'us'
     *
     * @return string
     */
    public static function getCountry($language = null)
    {
        $parts = self::getLanguageCodeParts($language);

        return (isset($parts[1])) ? $parts[1] : '';
    }
}
