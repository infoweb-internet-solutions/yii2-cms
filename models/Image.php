<?php
namespace infoweb\cms\models;

use Yii;
use rico\yii2images\models\Image as BaseImage;
use dosamigos\translateable\TranslateableBehavior;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;

class Image extends BaseImage
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return time();
                },
            ],
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'alt',
                    'title',
                    'subtitle',
                    'description',
                    'url',
                ]
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Image'),
            'filePath' => Yii::t('app', 'File Path'),
            'itemId' => Yii::t('app', 'Item ID'),
            'isMain' => Yii::t('app', 'Main image'),
            'modelName' => Yii::t('app', 'Attached to'),
            'urlAlias' => Yii::t('app', 'Url alias'),
        ];
    }

    public function getUrl($size = false)
    {
        // Check if the image exists or return a placeholder
        if (!file_exists(Yii::getAlias('@uploadsBasePath/img/') . $this->filePath)) {
            $img = new PlaceHolder;
            return $img->getUrl($size);
        }

        $urlSize = ($size) ? '_'.$size : '';
        $base = $this->getModule()->getCachePath();
        $sub = $this->getSubDur();
        $origin = $this->getPathToOrigin();

        $filePath = $base.'/'.$sub.'/'.$this->urlAlias.$urlSize.'.'.pathinfo($origin, PATHINFO_EXTENSION);

        if(!file_exists($filePath)){
            $this->createVersion($origin, $size);

            if(!file_exists($filePath)){
                throw new \Exception(Yii::t('app', 'The image does not exist'));
            }
        }

        $httpPath = \Yii::getAlias('@uploadsBaseUrl').'/img/cache/'.$sub.'/'.$this->urlAlias.$urlSize.'.'.pathinfo($origin, PATHINFO_EXTENSION);

        return $httpPath;
    }
    
    public function getBaseUrl()
    {
        $base = $this->getModule()->getStorePath();
        $sub = $this->getSubDur();
        $origin = $this->getPathToOrigin();

        return $base.'/'.$this->filePath;
    }

    public function getPath($size = false)
    {
        $urlSize = ($size) ? '_'.$size : '';
        $base = $this->getModule()->getCachePath();
        $sub = $this->getSubDur();

        $origin = $this->getPathToOrigin();

        $filePath = $base.'/'.$sub.'/'.$this->urlAlias.$urlSize.'.'.pathinfo($origin, PATHINFO_EXTENSION);

        if(!file_exists($filePath)){
            $this->createVersion($origin, $size);

            if(!file_exists($filePath)){
                throw new \Exception(Yii::t('app', 'The image does not exist'));
            }
        }

        return $filePath;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // @todo Update rules
            //[['filePath', 'itemId', 'modelName', 'urlAlias'], 'required'],
            [['itemId', 'isMain', 'created_at', 'updated_at'], 'integer'],
            [['filePath', 'urlAlias'], 'string', 'max' => 400],
            [['modelName', 'identifier'], 'string', 'max' => 255]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ImageLang::className(), ['image_id' => 'id']);
    }

    public function getPopupImage()
    {
        return '<a class="fancybox" data-pjax="0" rel="fancybox" href="' . $this->getUrl('1000x') . '"><img src="' . $this->getUrl('80x80') . '" /></a>';
    }

    public function clearCache(){
        $subDir = $this->getSubDur();
        $dirToRemove = $this->getModule()->getCachePath().DIRECTORY_SEPARATOR.$subDir;
        
        if(preg_match('/'.preg_quote($this->modelName, '/').'/', $dirToRemove)){
            BaseFileHelper::removeDirectory($dirToRemove);
        }

        return true;
    }

    public function getFileInputWidgetPreview()
    {
        if ($this->name) {
            return [
                Html::img($this->getUrl(), ['class' => 'file-preview-image', 'alt' => $this->alt, 'title' => $this->alt])
            ];
        } else {
            return [];
        }    
    }
    
    public function getFileInputWidgetCaption()
    {
        return $this->name;    
    }
}