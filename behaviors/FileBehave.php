<?php
namespace infoweb\cms\behaviors;

use yii;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use yii\base\Exception;
use yii\helpers\Inflector;
use yii\helpers\Html;

class FileBehave extends yii\base\Behavior
{
    public $file;

    public function attachFile() {

        try {

            $model = $this->owner;
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate() && $model->file) {

                $fileName = Inflector::slug($model->file->baseName) . '.' . $model->file->extension;
                $folder = $this->getModelSubDir() . '/';
                $model->path = $fileName;

                BaseFileHelper::removeDirectory(Yii::getAlias('@uploadsBasePath') . "/files/{$folder}");

                if (!BaseFileHelper::createDirectory(Yii::getAlias('@uploadsBasePath') . "/files/{$folder}")) {
                    throw new Exception(Yii::t('app', 'Failed to create the file upload directory'));
                }

                $model->file->saveAs(Yii::getAlias('@uploadsBasePath') . "/files/{$folder}{$model->path}");


                $model->save();
            }

            return true;

        } catch(yii\base\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => true]
        ];
    }


    public function getModelSubDir()
    {
        $modelName = $this->getShortClass($this->owner);
        $modelDir = Inflector::pluralize($modelName).'/'. $modelName . $this->owner->id;

        return $modelDir;
    }

    public function getShortClass($obj)
    {
        $className = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $className, $matches)) {
            $className = $matches[1];
        }

        return $className;
    }

    public function getHint() {

        if ($this->owner->isNewRecord) {
            return '';
        }

        return Html::a(
            Yii::t('infoweb/app', 'View attached file'),
            $this->url,
            ['target' => '_blank']
        );
    }

    public function getFileUrl() {
        return Yii::getAlias('@uploadsBaseUrl') . "/files/{$this->getModelSubDir()}/{$this->owner->path}";
    }
}