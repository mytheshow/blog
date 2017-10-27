<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateRoles extends Migrator
{
    /**
     *  permission：二进制权限控制 如，1：读 2：写 4：关注 8：删除贴子
     */
    public function up()
    {
        $table = $this->table('roles',array('engine'=>'MyISAM'));
        $table->addColumn('name', 'string', ['limit' => 32, 'null' => false])
            ->addColumn('permission', 'integer')
            ->create();
    }
    public function down()
    {
        $this->dropTable('roles');
    }
}
