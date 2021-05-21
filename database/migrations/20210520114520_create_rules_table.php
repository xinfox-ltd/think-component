<?php

use think\migration\Migrator;

class CreateRulesTable extends Migrator
{
    public function up()
    {
        $table = $this->table('role_rule', ['collation' => 'utf8mb4_general_ci', 'comment' => '权限规则']);
        $table->addColumn('ptype', 'string', ['null' => false, 'default' => ''])
            ->addColumn('v0', 'string', ['null' => false, 'default' => ''])
            ->addColumn('v1', 'string', ['null' => false, 'default' => ''])
            ->addColumn('v2', 'string', ['null' => false, 'default' => ''])
            ->addColumn('v3', 'string', ['null' => false, 'default' => ''])
            ->addColumn('v4', 'string', ['null' => false, 'default' => ''])
            ->addColumn('v5', 'string', ['null' => false, 'default' => ''])
            ->create();
    }

    public function down()
    {
        $table = $this->table('role_rule');
        $table->drop();
    }
}