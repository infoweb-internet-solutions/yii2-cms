<?php

namespace infoweb\cms\components;

use Yii;
use yii\base\Component;

use infoweb\alias\models\AliasLang;
use infoweb\pages\models\Page;
use infoweb\menu\models\MenuItem;

class Frontend extends Component {

    public $menuItem;
    public $page;
    public $parentMenuItem;
    public $parentPage;

    public function init()
    {
        // Must be included at the beginning
        parent::init();

        $this->page = $this->activePage;
        $this->menuItem = $this->activeMenuItem;
        $this->parentMenuItem = $this->activeParentMenuItem;
        $this->parentPage = $this->activeParentPage;
    }

    /**
     * Returns a page, based on the alias that is provided in the request or,
     * if no alias is provided, the homepage
     *
     * @return  Page
     */
    public function getActivePage()
    {
        // An alias is provided
        if (Yii::$app->request->get('alias')) {

            // Load the alias translation
            $aliasLang = AliasLang::findOne([
                'url'       => Yii::$app->request->get('alias'),
                'language'  => Yii::$app->language
            ]);

            if (!$aliasLang) {
                return Yii::$app->response->redirect('@web/404');
            }

            // Get the alias
            $alias = $aliasLang->alias;

            // Get the page
            $page = $alias->entityModel;

            // The page must be active
            if ($page->active != 1) {
                return Yii::$app->response->redirect('@web/404');
            }


        } else {
            // Load the page that is marked as the 'homepage'
            $page = Page::findOne(['homepage' => 1]);
        }

        return $page;
    }

    /**
     * Get the active parent page
     *
     * @return Page
     */
    public function getActiveParentPage()
    {
        if (isset($this->parentMenuItem->page)) {
            return $this->parentMenuItem->page;
        } else {
            return new Page;
        }
    }

    /**
     * Get the active menu item
     *
     * @return null|static
     */
    public function getActiveMenuItem()
    {
        $menuItem = MenuItem::findOne([
            'entity' => MenuItem::ENTITY_PAGE,
            'entity_id' => $this->page->id,
            'active' => 1,
            //'menu_id' => 1, @todo Set correnct menu id?
        ]);

        return $menuItem;
    }

    /**
     * Get the active parent menu item
     *
     * @return MenuItem
     */
    public function getActiveParentMenuItem()
    {
        $menuItem = $this->menuItem->parent;

        if ($menuItem && $menuItem->active = 1) {
            return $menuItem;
        } else {
            return new MenuItem;
        }
    }
}