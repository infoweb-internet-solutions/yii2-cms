<?php
namespace infoweb\cms\controllers;

use DateTime;
use DateTimeZone;
use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\Module;

class ParseController extends \yii\web\Controller
{
    /**
     * Convert display date for saving to model
     *
     * @returns JSON encoded HTML output
     */
    public function actionConvertDateControl()
    {
        $output = '';
        $module = Yii::$app->controller->module;
        $post = Yii::$app->request->post();
        if (isset($post['displayDate'])) {
            $type = empty($post['type']) ? Module::FORMAT_DATE : $post['type'];
            $saveFormat = ArrayHelper::getValue($post, 'saveFormat');
            $dispFormat = ArrayHelper::getValue($post, 'dispFormat') . '|';
            
            // If the date if saved as a unix timestamp and the format type is
            // a date, add a special character to ensure that only the date is 
            // saved without the time
            if ($type == Module::FORMAT_DATE && $saveFormat == 'U')
                $dispFormat .= '|';
             
            $dispTimezone = ArrayHelper::getValue($post, 'dispTimezone');
            $saveTimezone = ArrayHelper::getValue($post, 'saveTimezone');
            if ($dispTimezone != null) {
                $date = DateTime::createFromFormat($dispFormat, $post['displayDate'], new DateTimeZone($dispTimezone));
            } else {
                $date = DateTime::createFromFormat($dispFormat, $post['displayDate']);
            }
            if (empty($date) || !$date) {
                $value = '';
            } elseif ($saveTimezone != null) {
                $value = $date->setTimezone(new DateTimeZone($saveTimezone))->format($saveFormat);
            } else {
                $value = $date->format($saveFormat);
            }
            echo Json::encode(['status' => 'success', 'output' => $value]);
        } else {
            echo Json::encode(['status' => 'error', 'output' => 'No display date found']);
        }
    }
    
}
