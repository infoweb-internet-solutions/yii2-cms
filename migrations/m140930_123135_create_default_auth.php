<?php

use yii\db\Schema;
use yii\db\Migration;

class m140930_123135_create_default_auth extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'Superadmin',
            'type'          => 1,
            'description'   => 'Superadmin',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        $this->insert('{{%auth_item}}', [
            'name'          => '/*',
            'type'          => 2,
            'description'   => '',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Assign the auth item to the main user
        $this->insert('{{%auth_assignment}}', [
            'item_name'     => 'Superadmin',
            'user_id'       => 1,
            'created_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_assignment}}', [
            'parent'        => 'Superadmin',
            'child'         => '/*'
    }

    public function down()
    {
        echo "m140930_123135_create_default_permissions cannot be reverted.\n";

        return false;
    }
}
