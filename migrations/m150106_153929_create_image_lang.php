<?php

use yii\db\Schema;
use yii\db\Migration;

class m150106_153929_create_image_lang extends Migration
{
    public function up()
    {

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Create 'image_lang' table
        $this->createTable('{{%image_lang}}', [
            'image_id'              => Schema::TYPE_INTEGER . '(10) UNSIGNED NOT NULL',
            'language'              => Schema::TYPE_STRING . '(2) NOT NULL',
            'alt'                   => Schema::TYPE_STRING . ' NOT NULL',
            'title'                 => Schema::TYPE_STRING . ' NOT NULL',
            'description'           => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . '(10) UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . '(10) UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->addPrimaryKey('image_lang_image_id_language', '{{%image_lang}}', ['image_id', 'language']);
        $this->createIndex('language', '{{%image_lang}}', 'language');
        $this->addForeignKey('FK_IMAGE_LANG_IMAGE_ID', '{{%image_lang}}', 'image_id', '{{%image}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function down()
    {
        $this->dropTable('image_lang');

        return false;
    }
}
