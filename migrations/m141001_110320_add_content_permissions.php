<?php

use yii\db\Schema;
use yii\db\Migration;

class m141001_110320_add_content_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showContentModule',
            'type'          => 2,
            'description'   => 'Show content module icon in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        $this->insert('{{%auth_item}}', [
            'name'          => 'showPagesModule',
            'type'          => 2,
            'description'   => 'Show pages module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        $this->insert('{{%auth_item}}', [
            'name'          => 'showPagePartialsModule',
            'type'          => 2,
            'description'   => 'Show partials module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showContentModule'
        ]);
        
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagesModule'
        ]);
        
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagePartialsModule'
        ]);
    }

    public function down()
    {
        // Delete the relations
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showContentModule'
        ]);
        
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagesModule'
        ]);
        
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagePartialsModule'
        ]);
        
        // Delete the items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showContentModule',
            'type'          => 2,
        ]);
        
        $this->delete('{{%auth_item}}', [
            'name'          => 'showPagesModule',
            'type'          => 2,
        ]);
        
        $this->delete('{{%auth_item}}', [
            'name'          => 'showPagePartialsModule',
            'type'          => 2,
        ]);
    }
}
