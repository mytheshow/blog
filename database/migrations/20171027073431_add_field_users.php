<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AddFieldUsers extends Migrator
{
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('role_id', 'integer', ['limit' => 1])
            ->addColumn('name', 'string', ['limit' => 32, 'null' => false])
            ->addColumn('location', 'string',['limit' => 32])
            ->addColumn('about_me','text')
            ->addColumn('avatar', 'string',['limit' => 32,'comment'=>'头像存储路径'])
            ->save();
    }
    public function down()
    {
        $table = $this->table('users');
        $table->removeColumn('role_id', 'integer', ['limit' => 1])
            ->removeColumn('name', 'string', ['limit' => 32, 'null' => false])
            ->removeColumn('location', 'string',['limit' => 32])
            ->removeColumn('about_me','text')
            ->removeColumn('avatar', 'string',['limit' => 32,'comment'=>'头像存储路径'])
            ->save();
    }
}
