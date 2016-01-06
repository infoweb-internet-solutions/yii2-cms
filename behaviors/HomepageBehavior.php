<?php
namespace infoweb\cms\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

class HomepageBehavior extends AttributeBehavior
{
    /**
     * The maximum amount of items that are allowed to be displayed on the homepage
     * @var int
     */
    public $maxItems = null;

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

        // If the item is set as the homepage and the maximum allowed amount is
        // 1, change the value of this attribute for all other items
        if ($this->maxItems == 1 && $node->show_on_homepage == 1) {
            $node::updateAll(['show_on_homepage' => 0]);
        }
    }
}
