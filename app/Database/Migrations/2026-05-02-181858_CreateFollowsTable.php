<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFollowsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'follower_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'followed_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['follower_id', 'followed_id'], false, true);
        $this->forge->addForeignKey('follower_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('followed_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('follows');
    }

    public function down()
    {
        $this->forge->dropTable('follows');
    }
}
