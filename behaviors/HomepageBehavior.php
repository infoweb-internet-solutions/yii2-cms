<?php
namespace infoweb\cms\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

class HomepageBehavior extends AttributeBehavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'checkValue',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'checkValue'
        ];
    }
    
    public function checkValue($event)
    {
        $node = $this->owner;

        // Set all nodes show_on_homepage to 0
        if ($node->show_on_homepage == 1) {
            $node::updateAll(['show_on_homepage' => 0]);
        }
    }
}
