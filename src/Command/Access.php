<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use XinFox\ThinkPHP\Model\AuthRule;

class Access extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('access:init')->setDescription('App initialize');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        AuthRule::destroy(0);
        foreach ($this->app->route->getRuleList() as $item) {
            $uri = preg_replace('/<(.*)>/Ui', ':$1', $item['rule']);
            AuthRule::create([
                'status' => 1,
                'name' => $item['name'],
                'uri' => $uri,
            ]);
        }
    }
}