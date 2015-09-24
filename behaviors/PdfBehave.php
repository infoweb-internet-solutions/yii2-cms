<?php
namespace infoweb\cms\behaviors;

use yii;
use yii\helpers\BaseFileHelper;
use yii\base\Exception;
use yii\helpers\Inflector;
use yii\helpers\Html;
use kartik\mpdf\Pdf;

class PdfBehave extends yii\base\Behavior
{
    public $file;

    public function attachFile() {

        try {
            $model = $this->owner;
            $fileName = Inflector::slug($model->fullAddress) . '.pdf';
            $folder = $this->getModelSubDir() . '/';
            $model->pdf_path = $fileName;
            
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_FILE,
                'content' => Yii::$app->view->render('@frontend/views/site/real-estate/print', ['model' => $model]),
                'methods' => [
                    'SetHeader' => ['Rapport ' . $model->fullAddress],
                    'SetFooter' => ['|Pagina {PAGENO}|'],
                ],
                'filename' => Yii::getAlias('@uploadsBasePath') . "/files/{$folder}{$fileName}",
            ]);

            $this->file = $pdf;
            
            BaseFileHelper::removeDirectory(Yii::getAlias('@uploadsBasePath') . "/files/{$folder}");

            if (!BaseFileHelper::createDirectory(Yii::getAlias('@uploadsBasePath') . "/files/{$folder}")) {
                throw new Exception(Yii::t('app', 'Failed to create the file upload directory'));
            }

            // Save and update model
            $pdf->render();
            $model->save();

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
        return [];
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
            $this->fileUrl,
            ['target' => '_blank']
        );
    }

    public function getFileUrl() {
        return Yii::getAlias('@uploadsBaseUrl') . "/files/{$this->getModelSubDir()}/{$this->owner->report_path}";
    }
}