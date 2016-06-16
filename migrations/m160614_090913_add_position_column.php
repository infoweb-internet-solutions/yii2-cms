<?php

use yii\db\Migration;
use yii\db\Schema;

class m160614_090913_add_position_column extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('{{%image}}', 'text_position', Schema::TYPE_STRING.'(255) NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%image}}', 'text_position');  
    }
}