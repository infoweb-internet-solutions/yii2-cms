<?php

use yii\db\Schema;
use yii\db\Migration;

class m150107_074600_update_image extends Migration
{
    public function up()
    {
        $this->addColumn('image', 'position', Schema::TYPE_INTEGER . 'UNSIGNED NOT NULL');
        $this->addColumn('image', 'created_at', Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL');
        $this->addColumn('image', 'updated_at', Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL');

        $this->createIndex('itemId', '{{%image}}', 'itemId');
    }

    public function down()
    {
        $this->dropColumn('image', 'position');
        $this->dropColumn('image', 'created_at');
        $this->dropColumn('image', 'created_at');

        $this->dropIndex('itemId', '{{%image}}');
    }
}
