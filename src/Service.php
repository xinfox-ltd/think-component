<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP;

use XinFox\Auth\Auth;
use XinFox\ThinkPHP\Middleware\AuthMiddleware;

class Service extends \think\Service
{
    public function register()
    {
        $config = $this->getAuthConfig();
        $this->app->bind(
            Auth::class,
            fn() => new Auth($config)
        );
    }

    public function getAuthConfig()
    {
        $config = $this->app->config->get('auth');
        if (empty($config)) {
            $config = [
                'inject' => [
                    'enable' => true,
                    'namespaces' => ["XinFox"],
                ],
                'route' => [
                    'enable' => true,
                    'controllers' => [],
                    'auth' => [
                        'enable' => true,
                        'middleware' => AuthMiddleware::class
                    ]
                ],
                'model' => [
                    'enable' => true,
                ],
                'ignore' => [],
                'store' => null,//缓存store
            ];
        }

        return $config;
    }
}