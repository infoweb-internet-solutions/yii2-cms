<?php
namespace infoweb\cms\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class Base64EncodeBehavior extends Behavior
{
    public $attributes;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'encode',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encode',
            ActiveRecord::EVENT_AFTER_INSERT  => 'decode',
            ActiveRecord::EVENT_AFTER_UPDATE  => 'decode',
            ActiveRecord::EVENT_AFTER_FIND    => 'decode',
        ];
    }

    public function encode($event)
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = base64_encode($this->owner->$attribute);
        }
    }

    public function decode($event)
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = base64_decode($this->owner->$attribute);
        }
    }

}