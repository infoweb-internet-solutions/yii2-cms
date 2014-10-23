<?php

namespace infoweb\cms;

use Yii;
use \yii\helpers\Url;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'infoweb\cms\controllers';
    
    /**
     * @var array   The items that are shown in the sidebar navigation
     */
    public $sideBarItems = [];
    
    public function init()
    {
        parent::init();
    }
    
    /**
     * Returns the items for the sidebar, formatted for usage in the SideNav widget
     * 
     * @param   string  $group      The group that contains the items
     * @param   string  $template   The template that is used to render the html of the items
     * @return  array
     */
    public function getSideBarItems($group = '', $template = '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>')
    {
        $items = [];
        
        if (!isset($this->sideBarItems[$group]))
            return $items;
        
        foreach ($this->sideBarItems[$group] as $item) {
            $items[] = [
                'label'     => $item['label'],
                'url'       => Url::toRoute($item['url']),
                'template'  => $template,
                'visible'   => (isset($item['authItem']) && Yii::$app->user->can($item['authItem'])) ? true : false
            ];    
        }
        
        return $items;    
    }
}