<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Command;

use Casbin\Enforcer;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class PermissionSet extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('permission:set')->setDescription('Permission setting')
            ->addOption('role', null, Argument::OPTIONAL, 'role')
            ->addOption('uri', null, Argument::OPTIONAL, 'uri')
            ->addOption('method', null, Argument::OPTIONAL, 'method');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $role = $input->getOption('role');
        $uri = $input->getOption('uri');
        $method = $input->getOption('method');

        /** @var Enforcer $enforcer */
        $enforcer = $this->app->get(Enforcer::class);
        $enforcer->addPermissionForUser($role, $uri, strtoupper($method));

        $output->writeln("success");
    }
}