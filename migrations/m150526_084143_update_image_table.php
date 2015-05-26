<?php

use yii\db\Migration;

class m150526_084143_update_image_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('image', 'isMain', 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\'');
    }

    public function safeDown()
    {
        $this->alterColumn('image', 'isMain', 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'');
    }
}
