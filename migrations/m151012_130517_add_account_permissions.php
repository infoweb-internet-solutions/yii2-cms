<?php

use yii\db\Migration;
use yii\db\Schema;

class m151012_130517_add_account_permissions extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%auth_item}}', [
            'name'          => 'showProfile',
            'type'          => 2,
            'description'   => 'Show profile icon in user dropdown',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);

        $this->insert('{{%auth_item}}', [
            'name'          => 'showAccount',
            'type'          => 2,
            'description'   => 'Show account icon in user dropdown',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);

        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showProfile',
        ]);

        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showAccount',
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showProfile'
        ]);

        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showAccount'
        ]);

        $this->delete('{{%auth_item}}', [
            'name'          => 'showProfile',
            'type'          => 2,
        ]);

        $this->delete('{{%auth_item}}', [
            'name'          => 'showAccount',
            'type'          => 2,
        ]);
    }
}
