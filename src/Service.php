<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP;

use Casbin\Enforcer;
use Casbin\Model\Model;
use XinFox\Auth\Auth;
use XinFox\ThinkPHP\Command\AccessInit;
use XinFox\ThinkPHP\Command\Initialize;
use XinFox\ThinkPHP\Provider\Casbin\Adapter\DatabaseAdapter;

class Service extends \think\Service
{
    public function register()
    {
        // 注册数据迁移服务
        $this->app->register(\think\migration\Service::class);

        // 绑定 Casbin决策器
        $this->app->bind(
            'enforcer',
            function () {
                $model = new Model();
                $model->loadModel(config_path() . 'casbin_rbac_model.conf');

                return new Enforcer($model, app(DatabaseAdapter::class));
            }
        );

        // 绑定 Auth
        $config = $this->app->config->get('auth');
        $this->app->bind(
            Auth::class,
            fn() => new Auth($config)
        );
    }

    /**
     * Boot function.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            'app:init' => Initialize::class,
            'access:init' => AccessInit::class
        ]);
    }
}