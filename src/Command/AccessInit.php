<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\event\RouteLoaded;
use think\facade\Db;
use XinFox\ThinkPHP\Model\AuthRule;

class AccessInit extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('access:init')->setDescription('App initialize')
            ->addArgument('dir', Argument::OPTIONAL, 'dir name .');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     * @throws \think\db\exception\DbException
     */
    protected function execute(Input $input, Output $output)
    {
        $dir = $input->getArgument('dir') ?: '';

        $filename = $this->app->getRootPath() . 'runtime' . DIRECTORY_SEPARATOR . ($dir ? $dir . DIRECTORY_SEPARATOR : '') . 'route_list.php';

        if (is_file($filename)) {
            unlink($filename);
        } elseif (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0755);
        }

        $this->app->route->setTestMode(true);
        $this->app->route->clear();

        if ($dir) {
            $path = $this->app->getRootPath() . 'route' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;
        } else {
            $path = $this->app->getRootPath() . 'route' . DIRECTORY_SEPARATOR;
        }

        $files = is_dir($path) ? scandir($path) : [];

        foreach ($files as $file) {
            if (strpos($file, '.php')) {
                include $path . $file;
            }
        }

        //触发路由载入完成事件
        $this->app->event->trigger(RouteLoaded::class);

        Db::table('xf_auth_rule')->where('1=1')->delete();

        foreach ($this->app->route->getRuleList() as $item) {
            $uri = preg_replace('/<(.*)>/Ui', ':$1', $item['rule']);
            AuthRule::create([
                'status' => 1,
                'name' => $item['name'],
                'uri' => $uri,
                'method' => strtoupper($item['method'])
            ]);
        }
    }
}