<?php

use think\migration\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['name' => 'Administrator','permission'=>255],
            ['name' => 'User','permission'=>7]
            ];
        $this->table('roles')->insert($rows)->save();
    }
}