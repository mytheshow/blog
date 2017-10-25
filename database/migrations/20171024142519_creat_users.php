<?php

use think\migration\Migrator;


class CreatUsers extends Migrator
{

    public function up()
    {
        $table = $this->table('users',array('engine'=>'MyISAM'));
        $table->addColumn('username', 'string', ['limit' => 16, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 32, 'null' => false])
            ->addColumn('password', 'string',array('limit' => 32,'default'=>md5('123456'),'comment'=>'用户密码'))
            ->addColumn('confirmed','integer',['limit' => 1, 'default' => 0, 'comment' => '邮件是否确认'])
            ->addColumn('last_login_ip', 'integer',array('limit' => 11,'default'=>0,'comment'=>'最后登录IP'))
            ->addColumn('last_seen', 'datetime',array('default'=>0,'comment'=>'最后登录时间'))
            ->addColumn('create_time', 'datetime',array('default'=>0,'comment'=>'最后登录时间'))
            ->addIndex(array('email'), array('unique' => true))
            ->create();
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
