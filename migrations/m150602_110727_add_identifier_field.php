<?php

use yii\db\Schema;
use yii\db\Migration;

class m150602_110727_add_index_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%image}}', 'indentifier', Schema::TYPE_STRING.'(255) NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%image}}', 'indentifier');  
    }
}
