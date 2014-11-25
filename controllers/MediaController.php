<?php
namespace infoweb\cms\controllers;


class MediaController extends \yii\web\Controller
{
    /**
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMoxie()
    {
        $this->layout = 'moxie';
        return $this->render('moxie');
    }
}
