<?php

use think\migration\Migrator;
use think\migration\db\Column;

class InterfaceUser extends Migrator
{

    public function up()
    {
        $table = $this->table('user',array('id'=>'user_id','engine'=>'MyISAM'));
        $table->addColumn('user_phone', 'string', ['limit' => 11, 'null' => false])
            ->addColumn('user_nickname', 'string',array('limit' => 255,'null' => false,'comment'=>'昵称'))
            ->addColumn('user_email','string',['limit' => 255,'null' => false])
            ->addColumn('user_rtime','integer',['limit' => 11,'null' => false,'comment'=>'注册时间'])
            ->addColumn('user_pwd','string',['limit' => 32,'null' => false,'comment'=>'密码'])
            ->addColumn('user_icon','string',['limit' => 255,'null' => false,'comment'=>'头像'])
            ->create();
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
