<?php
namespace infoweb\cms\widgets;

use Yii;
use yii\bootstrap\Widget;
use infoweb\news\models\News as NewsItem;

class FlexmailSubscribe extends Widget
{
    public $template = '_fields';
    public $publicKey = '';
    public $mailingLists = [];

    public function init()
    {
        parent::init();        
    }
    
    public function run()
    {

        return $this->render('subscribe', [
            'publicKey' => $this->publicKey,
            'mailingLists' => $this->mailingLists,
            'template' => $this->template,
        ]);
    }
}
