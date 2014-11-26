<?php

namespace infoweb\cms\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class ImageUploadForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $image;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['image'], 'safe'],
            [['image'], 'image', 'skipOnEmpty' => false, /*'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png',*/],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image' => Yii::t('app', 'Image')
        ];
    }
}