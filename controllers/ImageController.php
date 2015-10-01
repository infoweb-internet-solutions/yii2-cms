<?php
namespace infoweb\cms\controllers;

use Yii;
use yii\web\Response;

class ImageController extends \yii\web\Controller
{
    /**
     * Removes all images that are attached to a model
     *
     * @param   string      $model
     * @return  string      JSON response
     */
    public function actionRemoveImages()
    {
        // Default response
        $response = [
            'status'    => 1,
            'msg'       => ''
        ];

        $post = Yii::$app->request->post();

        if (isset($post['model']) && !empty($post['model'])) {

            // Load model
            $model = Yii::createObject(['class' => $post['model'], 'id' => $post['modelId']]);

            // Remove the images
            $model->removeImages();
        }

        // Return validation in JSON format
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
}
