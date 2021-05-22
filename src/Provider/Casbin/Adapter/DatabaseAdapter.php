<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Provider\Casbin\Adapter;

use Casbin\Model\Model;
use Casbin\Persist\Adapter;
use Casbin\Persist\AdapterHelper;
use XinFox\ThinkPHP\Model\RoleRule;

class DatabaseAdapter implements Adapter
{
    use AdapterHelper;

    /**
     * Rules model.
     *
     * @var RoleRule
     */
    protected RoleRule $model;

    protected string $modelCacheKey = "xinfox_casbin_rule";

    /**
     * the DatabaseAdapter constructor.
     *
     * @param RoleRule $model
     */
    public function __construct(RoleRule $model)
    {
        $this->model = $model;
    }

    /**
     * savePolicyLine function.
     *
     * @param string $ptype
     * @param array  $rule
     *
     * @return void
     */
    public function savePolicyLine($ptype, array $rule)
    {
        $col['ptype'] = $ptype;
        foreach ($rule as $key => $value) {
            $col['v'.strval($key).''] = $value;
        }
        $this->model->cache($this->modelCacheKey)->save($col);
    }

    /**
     * loads all policy rules from the storage.
     *
     * @param Model $model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function loadPolicy(Model $model): void
    {
        $rows = $this->model->cache($this->modelCacheKey)->select()->toArray();
        foreach ($rows as $row) {
            $line = implode(', ', array_filter(array_slice($row, 1), function ($val) {
                return '' != $val && !is_null($val);
            }));
            $this->loadPolicyLine(trim($line), $model);
        }
    }

    /**
     * saves all policy rules to the storage.
     *
     * @param Model $model
     */
    public function savePolicy(Model $model): void
    {
        foreach ($model['p'] as $ptype => $ast) {
            foreach ($ast->policy as $rule) {
                $this->savePolicyLine($ptype, $rule);
            }
        }

        foreach ($model['g'] as $ptype => $ast) {
            foreach ($ast->policy as $rule) {
                $this->savePolicyLine($ptype, $rule);
            }
        }
    }

    /**
     * adds a policy rule to the storage.
     * This is part of the Auto-Save feature.
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     */
    public function addPolicy(string $sec, string $ptype, array $rule): void
    {
        $this->savePolicyLine($ptype, $rule);
    }

    /**
     * This is part of the Auto-Save feature.
     *
     * @param string $sec
     * @param string $ptype
     * @param array $rule
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function removePolicy(string $sec, string $ptype, array $rule): void
    {
        $count = 0;

        $instance = $this->model->where('ptype', $ptype);

        foreach ($rule as $key => $value) {
            $instance->where('v'.strval($key), $value);
        }

        foreach ($instance->select() as $model) {
            if ($model->cache($this->modelCacheKey)->delete()) {
                ++$count;
            }
        }
    }

    /**
     * RemoveFilteredPolicy removes policy rules that match the filter from the storage.
     * This is part of the Auto-Save feature.
     *
     * @param string $sec
     * @param string $ptype
     * @param int $fieldIndex
     * @param string ...$fieldValues
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function removeFilteredPolicy(string $sec, string $ptype, int $fieldIndex, string ...$fieldValues): void
    {
        $count = 0;

        $instance = $this->model->where('ptype', $ptype);
        foreach (range(0, 5) as $value) {
            if ($fieldIndex <= $value && $value < $fieldIndex + count($fieldValues)) {
                if ('' != $fieldValues[$value - $fieldIndex]) {
                    $instance->where('v'.strval($value), $fieldValues[$value - $fieldIndex]);
                }
            }
        }

        foreach ($instance->select() as $model) {
            if ($model->cache($this->modelCacheKey)->delete()) {
                ++$count;
            }
        }
    }
}