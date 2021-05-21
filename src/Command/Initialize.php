<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Initialize extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('app:init')->setDescription('App initialize');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     */
    protected function execute(Input $input, Output $output)
    {
        $destination = $this->app->getRootPath() . '/database/migrations/';
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        $source = __DIR__ . '/../../database/migrations/';
        $handle = dir($source);

        while ($entry = $handle->read()) {
            if (($entry != ".") && ($entry != "..")) {
                if (is_file($source . $entry)) {
                    copy($source . $entry, $destination . $entry);
                }
            }
        }

        if (!file_exists(config_path() . 'casbin_rbac_model.conf')) {
            copy(__DIR__ . '/../../config/casbin_rbac_model.conf', config_path() . 'casbin_rbac_model.conf');
        }
    }
}