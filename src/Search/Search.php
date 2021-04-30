<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Search;

use think\helper\Str;
use XinFox\Auth\VisitorInterface;

abstract class Search implements SearchInterface
{
    private EngineInterface $engine;

    public function __construct(array $searchItems, VisitorInterface $visitor = null)
    {
        $this->engine = $this->createEngine($searchItems, $visitor);
    }

    public function createEngine(array $searchItems, VisitorInterface $visitor = null): EngineInterface
    {
        $namespace = $this();
        $className = $namespace . '\\DefaultEngine';
        if ($visitor) {
            $className = $namespace . "\\" . Str::studly($visitor->getRole()) . "Engine";
        }
        if (!class_exists($className)) {
            throw new \DomainException("$className Not Exists");
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