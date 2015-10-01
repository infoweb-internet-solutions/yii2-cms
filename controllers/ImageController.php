<?php
namespace infoweb\cms\controllers;

use Yii;
use yii\web\Response;

class ImageController extends \yii\web\Controller
{

    /**
     * Removes a specific image from the model
     *
     * @param   string      $model
     * @param   string      $identifier
     * @return  string      JSON response
     */
    public function actionRemoveImage()
    {
        // Default response
        $response = [
            'status'    => 1,
            'msg'       => ''
        ];

        $post = Yii::$app->request->post();

        if (isset($post['model']) && !empty($post['model'])) {

            /// Load model
            $model = Yii::createObject(['class' => $post['model'], 'id' => $post['modelId']]);

            // Remove the image
            $model->removeImageByIdentifier($post['identifier']);
        }

        // Return validation in JSON format
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }

}
