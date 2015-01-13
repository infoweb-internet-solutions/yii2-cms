<?php

namespace infoweb\cms\controllers;

use rico\yii2images\controllers\ImagesController as BaseImagesController;

use Yii;

use infoweb\cms\models\Image;
use infoweb\cms\models\ImageSearch;
use infoweb\cms\models\UploadForm;

use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

class ImagesController extends BaseImagesController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'upload' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();

        $slider = Slider::findOne($get['sliderId']);

        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $slider->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'slider' => $slider,
        ]);
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();

            $slider = Slider::findOne($post['sliderId']);

            $form = new UploadForm;
            $images = UploadedFile::getInstances($form, 'images');

            foreach ($images as $k => $image) {

                $_model = new UploadForm();
                $_model->image = $image;

                if ($_model->validate()) {
                    $path = \Yii::getAlias('@uploadsBasePath') . "/img/{$_model->image->baseName}.{$_model->image->extension}";

                    $_model->image->saveAs($path);

                    // Attach image to model
                    $slider->attachImage($path);

                } else {
                    foreach ($_model->getErrors('image') as $error) {
                        $slider->addError('image', $error);
                    }
                }
            }

            if ($form->hasErrors('image')){
                $form->addError(
                    'image',
                    count($form->getErrors('image')) . ' of ' . count($images) . ' images not uploaded'
                );
            } else {
                Yii::$app->session->setFlash('image-success', Yii::t('infoweb/sliders', '{n, plural, =1{Image} other{# images}} successfully uploaded', ['n' => count($images)]));
            }

            return $this->redirect(['index?sliderId=' . $post['sliderId']]);

        }
    }

    /**
     * Displays a single Image model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Image model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id, $sliderId)
    {
        $model = $this->findModel($id);

        $slider = Slider::findOne($sliderId);

        // Load all the translations
        $model->loadTranslations(array_keys(Yii::$app->params['languages']));

        if (Yii::$app->request->getIsPost()) {

            $post = Yii::$app->request->post();

            // Ajax request, validate the models
            if (Yii::$app->request->isAjax) {

                // Populate the model with the POST data
                $model->load($post);

                // Populate the translation model for the primary language
                $translationModel = $model->getTranslation(Yii::$app->language);
                $translationModel->alt = $post['ImageLang'][Yii::$app->language]['alt'];
                $translationModel->title = $post['ImageLang'][Yii::$app->language]['title'];
                $translationModel->description = $post['ImageLang'][Yii::$app->language]['description'];

                // Validate the translation model
                $translationValidation = ActiveForm::validate($translationModel);
                $correctedTranslationValidation = [];

                // Correct the keys of the validation
                foreach($translationValidation as $k => $v) {
                    $correctedTranslationValidation[str_replace('imagelang-', "imagelang-{$translationModel->language}-", $k)] = $v;
                }

                // Validate the model and primary translation model
                $response = array_merge(ActiveForm::validate($model), $correctedTranslationValidation);

                // Return validation in JSON format
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;

                // Normal request, save models
            } else {
                // Wrap the everything in a database transaction
                $transaction = Yii::$app->db->beginTransaction();

                // Save the main model
                /*
                if (!$model->load($post) || !$model->save()) {
                    return $this->render('update', [
                        'model' => $model,
                        'slider' => $slider,
                    ]);
                }*/

                // Save the translation models
                foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
                    $model->language = $languageId;
                    $model->alt = $post['ImageLang'][$languageId]['alt'];
                    $model->title = $post['ImageLang'][$languageId]['title'];
                    $model->description = $post['ImageLang'][$languageId]['description'];

                    if (!$model->saveTranslation()) {
                        return $this->render('update', [
                            'model' => $model,
                            'slider' => $slider,
                        ]);
                    }
                }
                $transaction->commit();

                // Set flash message
                $model->language = Yii::$app->language;
                Yii::$app->getSession()->setFlash('image-success', Yii::t('app', '{item} has been updated', ['item' => $model->name]));

                return $this->redirect(['index?sliderId=' . $slider->id]);
            }

        }
        return $this->render('update', [
            'model' => $model,
            'slider' => $slider,
        ]);
    }
    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id, $sliderId)
    {
        $model = $this->findModel($id);
        $model->delete();

        // Set flash message
        $model->language = Yii::$app->language;

        Yii::$app->getSession()->setFlash('image-success', Yii::t('app', '{item} has been deleted', ['item' => $model->name]));

        return $this->redirect(['index?sliderId=' . $sliderId]);
    }

    /**
     * Deletes existing Slider models.
     * If deletion is successful, the gridview will be refreshed.
     * @param string $id
     * @return mixed
     */
    public function actionMultipleDelete()
    {
        // @todo $ids as param in action?

        $data['status'] = 0;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $ids = Yii::$app->request->post('ids');

            Image::deleteAll(['id' => $ids]);

            $data['message'] = Yii::t('infoweb/sliders', '{n, plural, =1{Image} other{# images}} successfully deleted', ['n' => count($ids)]);
            $data['status'] = 1;
        }

        return $data;
    }

    public function actionMultipleDeleteConfirmMessage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $message = Yii::t('infoweb/sliders', 'Are you sure you want to delete {n, plural, =1{this image} other{# images}}?', ['n' => Yii::$app->request->post('ids')]);
        return $message;
    }

    /**
     *
     *
     * @param string $id
     * @return mixed
     */
    public function actionSort($sliderId)
    {
        $slider = Slider::findOne($sliderId);

        $images = Image::find()->where(['itemId' => $slider->id, 'modelName' => 'Slider'])->orderBy(['position' => SORT_DESC])->all();

        return $this->render('sort', [
            'slider' => $slider,
            'images' => $images,
        ]);
    }

    public function actionSortPictures()
    {
        $data['status'] = 0;

        if (Yii::$app->request->isAjax) {

            // Get ids
            $post = Yii::$app->request->post();
            $ids = array_reverse($post['ids']);

            $sqlValues = [];

            // Update positions

            // Build values
            foreach ($ids as $position => $id) {
                $position++;
                $sqlValues[] = "({$id}, {$position})";
            }

            $sqlValues = implode(',', $sqlValues);

            // Execute query
            $connection = Yii::$app->db;
            $command = $connection->createCommand("
                INSERT INTO `image` (`id`,`position`) VALUES {$sqlValues}
                ON DUPLICATE KEY UPDATE `position` = VALUES(`position`)
            ");
            $command->execute();

            // Set responsse format
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Success
            $data['message'] = Yii::t('app', 'The sorting was successfully updated');
            $data['status'] = 1;
        }

        return $data;

    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist'));
        }
    }


}
