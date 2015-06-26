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

    public function getUrl($size = false, $crop = true)
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
            $this->createVersion($origin, $size, $crop);

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

    public function createVersion($imagePath, $sizeString = false, $crop = true)
    {
        if(strlen($this->urlAlias)<1){
            throw new \Exception('Image without urlAlias!');
        }

        $cachePath = $this->getModule()->getCachePath();
        $subDirPath = $this->getSubDur();
        $fileExtension =  pathinfo($this->filePath, PATHINFO_EXTENSION);

        if($sizeString){
            $sizePart = '_'.$sizeString;
        }else{
            $sizePart = '';
        }

        $pathToSave = $cachePath.'/'.$subDirPath.'/'.$this->urlAlias.$sizePart.'.'.$fileExtension;

        BaseFileHelper::createDirectory(dirname($pathToSave), 0777, true);


        if($sizeString) {
            $size = $this->getModule()->parseSize($sizeString);
        }else{
            $size = false;
        }

            if($this->getModule()->graphicsLibrary == 'Imagick'){
                $image = new \Imagick($imagePath);
                $image->setImageCompressionQuality(100);
                
                // If the dimensions of the original image match the requested 
                // dimensions the original image is just copied to the new path
                if ($image->getImageWidth() == $size['width'] && $image->getImageHeight() == $size['height']) {
                    copy($imagePath, $pathToSave);
                    return $image;    
                }

                if($size){
                    if($size['height'] && $size['width']){
                        if ($crop) {
                            $image->cropThumbnailImage($size['width'], $size['height']);
                        } else {
                            $image->resizeImage($size['width'], $size['height'], Imagick::FILTER_HAMMING, 1, false);
                        }
                    }elseif($size['height']){
                        $image->thumbnailImage(0, $size['height']);
                    }elseif($size['width']){
                        $image->thumbnailImage($size['width'], 0);
                    }else{
                        throw new \Exception('Something wrong with this->module->parseSize($sizeString)');
                    }
                }

                $image->writeImage($pathToSave);
            }else{

                $image = new \abeautifulsite\SimpleImage($imagePath);

                // If the dimensions of the original image match the requested 
                // dimensions the original image is just copied to the new path
                if ($image->get_width() == $size['width'] && $image->get_height() == $size['height']) {
                    copy($imagePath, $pathToSave);
                    return $image;    
                }
                
                if($size){
                    if($size['height'] && $size['width']){

                        $image->thumbnail($size['width'], $size['height']);
                    }elseif($size['height']){
                        $image->fit_to_height($size['height']);
                    }elseif($size['width']){
                        $image->fit_to_width($size['width']);
                    }else{
                        throw new \Exception('Something wrong with this->module->parseSize($sizeString)');
                    }
                }

                //WaterMark
                if($this->getModule()->waterMark){

                    if(!file_exists(Yii::getAlias($this->getModule()->waterMark))){
                        throw new Exception('WaterMark not detected!');
                    }

                    $wmMaxWidth = intval($image->get_width()*0.4);
                    $wmMaxHeight = intval($image->get_height()*0.4);

                    $waterMarkPath = Yii::getAlias($this->getModule()->waterMark);

                    $waterMark = new \abeautifulsite\SimpleImage($waterMarkPath);



                    if(
                        $waterMark->get_height() > $wmMaxHeight
                        or
                        $waterMark->get_width() > $wmMaxWidth
                    ){

                        $waterMarkPath = $this->getModule()->getCachePath().DIRECTORY_SEPARATOR.
                            pathinfo($this->getModule()->waterMark)['filename'].
                            $wmMaxWidth.'x'.$wmMaxHeight.'.'.
                            pathinfo($this->getModule()->waterMark)['extension'];

                        //throw new Exception($waterMarkPath);
                        if(!file_exists($waterMarkPath)){
                            $waterMark->fit_to_width($wmMaxWidth);
                            $waterMark->save($waterMarkPath, 100);
                            if(!file_exists($waterMarkPath)){
                                throw new Exception('Cant save watermark to '.$waterMarkPath.'!!!');
                            }
                        }

                    }

                    $image->overlay($waterMarkPath, 'bottom right', .5, -10, -10);

                }

                $image->save($pathToSave, 100);
            }

        return $image;

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