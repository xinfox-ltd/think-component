<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Search;

use think\helper\Str;

abstract class Search implements SearchInterface
{
    private EngineInterface $engine;

    public function __construct(array $searchItems)
    {
        $this->engine = $this->createEngine($searchItems);
    }

    public function createEngine(array $searchItems): EngineInterface
    {
        $namespace = $this();
        $className = $namespace . '\\DefaultEngine';
        $client = app()->request->header('Client-ID');
        if ($client === null) {
            $role = app()->request->visitor->getRole();
            //TODO 从角色反推客户端
        }
        #TODO 使用配置
        if (in_array($client, ['admin', 'merchant', 'miniProgram'])) {
            $_className = $namespace . "\\" . Str::studly($client) . "Engine";
            if (class_exists($_className)) {
                $className = $_className;
            }
        }

        return new $className($searchItems);
    }

    /**
     */
    public function execute(): \think\Paginator
    {
        return $this->engine->search();
    }
}