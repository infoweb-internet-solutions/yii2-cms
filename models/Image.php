<?php
namespace infoweb\cms\models;

use Yii;
use rico\yii2images\models\Image as BaseImage;
use creocoder\translateable\TranslateableBehavior;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;

class Image extends BaseImage
{
    const TEXT_POSITION_LEFT = 'left';
    const TEXT_POSITION_RIGHT = 'right';
    const TEXT_POSITION_CENTER = 'center';

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
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'alt',
                    'title',
                    'subtitle',
                    'description',
                    'link'
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
            'name' => Yii::t('app', 'Image'),
            'filePath' => Yii::t('app', 'File Path'),
            'itemId' => Yii::t('app', 'Item ID'),
            'isMain' => Yii::t('app', 'Main image'),
            'modelName' => Yii::t('app', 'Attached to'),
            'urlAlias' => Yii::t('app', 'Url alias'),
            'active' => Yii::t('app', 'Active'),
            'text_position' => Yii::t('app', 'Text position')
        ];
    }

    public function textPositionLabels() {
        return [
            self::TEXT_POSITION_LEFT => Yii::t('app', 'Left'),
            self::TEXT_POSITION_RIGHT => Yii::t('app', 'Right'),
            self::TEXT_POSITION_CENTER => Yii::t('app', 'Center'),
        ];
    }

    public function getUrl($size = false, $crop = true, $fitImageInCanvas = false)
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
            $this->createVersion($origin, $size, $crop, $fitImageInCanvas);

            if(!file_exists($filePath)){
                throw new \Exception(Yii::t('app', 'The image does not exist'));
            }
        }

        $httpPath = \Yii::getAlias('@uploadsBaseUrl').'/img/cache/'.$sub.'/'.$this->urlAlias.$urlSize.'.'.pathinfo($origin, PATHINFO_EXTENSION);

        return $httpPath;
    }

    /**
     * Returns the path to the image
     *
     * @param   string|boolean      The size of the image
     * @param   boolean             Does cropping have to be applied
     * @return  string
     * @uses    infoweb\cms\models\Image::getUrl()
     */
    public function getPath($size = false, $crop = true)
    {
        $url = $this->getUrl($size, $crop);

        // Replace the baseUrl with the basePath
        return str_replace(\Yii::getAlias('@uploadsBaseUrl'), \Yii::getAlias('@uploadsBasePath'), $url);
    }

    public function getBaseUrl()
    {
        $base = $this->getModule()->getStorePath();
        $sub = $this->getSubDur();
        $origin = $this->getPathToOrigin();

        return $base.'/'.$this->filePath;
    }

    public function createVersion($imagePath, $sizeString = false, $crop = true, $fitImageInCanvas = false)
    {
        if(strlen($this->urlAlias)<1){
            throw new \Exception('Image without urlAlias!');
        }

        $cachePath = $this->getModule()->getCachePath();
        $subDirPath = $this->getSubDur();
        $fileExtension =  pathinfo($this->filePath, PATHINFO_EXTENSION);

        if($sizeString){
            $sizePart = '_'.$sizeString;
        }
        else{
            $sizePart = '';
        }

        $pathToSave = $cachePath.'/'.$subDirPath.'/'.$this->urlAlias.$sizePart.'.'.$fileExtension;

        BaseFileHelper::createDirectory(dirname($pathToSave), 0777, true);

        if($sizeString) {
            $size = $this->getModule()->parseSize($sizeString);
        }
        else{
            $size = false;
        }

        if($this->getModule()->graphicsLibrary == 'Imagick'){            
            // Fixes interlaced images
            $interlaceFix = $this->interlaceFix($imagePath);

            if($interlaceFix) {
                $image = new \Imagick();
                $image->readImageBlob($interlaceFix);
            }
            else {
                $image = new \Imagick($imagePath);
            }

            $image->setImageCompressionQuality(100);

            // Fixes image rotations
            $this->ImagickAutoRotateImage($image);

            // If the dimensions of the original image match the requested
            // dimensions the original image is just copied to the new path
            if ($image->getImageWidth() == $size['width'] && $image->getImageHeight() == $size['height']) {
                copy($imagePath, $pathToSave);
                return $image;
            }

            if($size){
                if($size['height'] && $size['width']){
                    if(!$crop && $fitImageInCanvas) {
                        $image = $this->fitImageInCanvas($image, $size['width'], $size['height']);
                    } elseif ($crop) {
                        $image->cropThumbnailImage($size['width'], $size['height']);
                    } else {
                        $image->resizeImage($size['width'], $size['height'], \Imagick::FILTER_HAMMING, 1, false);
                    }
                } elseif($size['height']){
                    $image->thumbnailImage(0, $size['height']);
                } elseif($size['width']){
                    $image->thumbnailImage($size['width'], 0);
                } else{
                    throw new \Exception('Something wrong with this->module->parseSize($sizeString)');
                }
            }

            $image->writeImage($pathToSave);
        } else {                
            $image = new \abeautifulsite\SimpleImage($imagePath);

            // If the dimensions of the original image match the requested
            // dimensions the original image is just copied to the new path
            if ($image->get_width() == $size['width'] && $image->get_height() == $size['height']) {
                copy($imagePath, $pathToSave);
                return $image;
            }

            if($size){
                if($size['height'] && $size['width']){
                    if($crop) {
                        $image->thumbnail($size['width'], $size['height']);
                    }
                    else {
                        $image->resize($size['width'], $size['height']);
                    }
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

    public function fitImageInCanvas(\Imagick $image, $width, $height) {
        $imageHeight = $image->getImageHeight();
        $imageWidth = $image->getImageWidth();

        if ($imageWidth > $width) {
            $image->scaleImage($width, $height, true);
        }
        if ($imageHeight > $height) {
            $image->scaleImage($width, $height, true);
        }

        $oldWidth = $image->getImageWidth();
        $oldHeight = $image->getImageHeight();

        #coords to center image inside fixed width/height canvas
        $x = ($width - $oldWidth) / 2;
        $y = ($height - $oldHeight) / 2;

        #create new image with the user image centered
        $newImage = new \Imagick();
        $bgColor = ($image->getImageFormat() == 'png') ? 'none' : 'white';
        $newImage->newImage($width, $height, new \ImagickPixel($bgColor));
        $newImage->compositeImage($image, \Imagick::COMPOSITE_OVER, $x, $y);

        return $newImage;
    }


    
    /**
     * Fix interlaceFix
     * 
     * @param type $file
     * @return boolean
     */
    public function interlaceFix($file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($ext == 'png') {
            $phpimage = imagecreatefrompng($file);

            imagealphablending($phpimage, false);
            imagesavealpha($phpimage, true);

            if ($phpimage) {
                imageinterlace($phpimage, 0);
                ob_start();
                imagepng($phpimage);
            }
            else {
                unset($phpimage);
            }
        }
        elseif ($ext == 'jpeg') {
            $phpimage = imagecreatefromjpeg($file);

            if ($phpimage) {
                imageinterlace($phpimage, 0);
                ob_start();
                imagejpeg($phpimage);
            }
            else {
                unset($phpimage);
            }
        }

        if (isset($phpimage)) {
            imagedestroy($phpimage);
            return ob_get_clean();
        }

        return false;
    }

    /**
     * Automatic rotation fix
     * 
     * @param \Imagick $image
     */
    public function ImagickAutoRotateImage(\Imagick $image) {
        $orientation = $image->getImageOrientation();
        switch ($orientation) {
            case \imagick::ORIENTATION_BOTTOMRIGHT :
                $image->rotateimage("#000", 180);
                // rotate 180 degrees
                break;

            case \imagick::ORIENTATION_RIGHTTOP :
                $image->rotateimage("#000", 90);
                // rotate 90 degrees CW
                break;

            case \imagick::ORIENTATION_LEFTBOTTOM :
                $image->rotateimage("#000", -90);
                // rotate 90 degrees CCW
                break;
        }

        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT);
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
            [['modelName', 'identifier', 'text_position'], 'string', 'max' => 255],
            ['text_position', 'default', 'value' => self::TEXT_POSITION_LEFT]
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