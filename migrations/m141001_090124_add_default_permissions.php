<?php

use yii\db\Schema;
use yii\db\Migration;

class m141001_090124_add_default_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showRightsModule',
            'type'          => 2,
            'description'   => 'Show rights module icon in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        $this->insert('{{%auth_item}}', [
            'name'          => 'showUsersModule',
            'type'          => 2,
            'description'   => 'Show users module icon in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showRightsModule'
        ]);
        
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showUsersModule'
        ]);
    }

    public function down()
    {
        echo "m141001_090124_add_default_permissions cannot be reverted.\n";

        return false;
    }
}
