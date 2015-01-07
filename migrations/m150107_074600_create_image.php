<?php

use yii\db\Schema;
use yii\db\Migration;

class m150107_074600_image extends Migration
{
    public function up()
    {
        // Drop image table
        if ($this->db->schema->getTableSchema('image', true)) {
            $this->dropTable('image');
        }

        $this->createTable('image', [
            'id' => 'pk',
            'filePath' => 'VARCHAR(400) NOT NULL',
            'itemId' => 'int(20) NOT NULL',
            'isMain' => 'int(1)',
            'modelName' => 'VARCHAR(150) NOT NULL',
            'urlAlias' => 'VARCHAR(400) NOT NULL',
            'position' => Schema::TYPE_INTEGER . 'UNSIGNED NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ]);

        $this->createIndex('itemId', '{{%image}}', 'itemId');
    }

    public function down()
    {
        $this->dropTable('image');
    }
}
