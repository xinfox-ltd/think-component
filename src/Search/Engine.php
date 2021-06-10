<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Search;

use think\db\Query;
use think\helper\Str;

abstract class Engine implements EngineInterface
{
    protected array $requestData = [];

    protected int $page = 1;

    protected int $pageSize = 10;

    protected Query $query;

    protected array $sortAlias = [];

    protected array $fields = [];

    public function __construct(array $data)
    {
        if (isset($data['page'])) {
            $this->page = intval($data['page']);
        }

        $pageSize = $data['pageSize'] ?? $data['page_size'] ??= 10;
        $this->pageSize = (int)$pageSize;

        if ($this->pageSize > 100) {
            throw new \InvalidArgumentException('pageSize 不能超过100');
        }

        unset($data['page'], $data['pageSize']);

        $this->query = $this->initialize();
        $this->requestData = $data;

        foreach ($data as $key => $val) {
            if (empty($val) && $val !== 0) {
                continue;
            }
            if ($key === 'sort') {
                $this->sort($val);
                continue;
            }
            $propertyName = 'screenBy' . Str::studly($key);
            if (method_exists($this, $propertyName)) {
                $this->$propertyName($val);
            }
        }
    }

    /**
     * @param string $sort
     */
    protected function sort(string $sort)
    {
        $arrSort = explode(',', $sort);
        foreach ($arrSort as $item) {
            if (stristr($item, '_') === false) {
                $field = $item;
            } else {
                [$field, $order] = explode('_', $item);
                $order = strtoupper($order);
            }
            if (!isset($order) || !in_array($order, ['ASC', 'DESC'])) {
                $order = 'DESC';
            }

            if (!empty($this->sortAlias) && isset($this->sortAlias[$field])) {
                $this->query->order($this->sortAlias[$field], $order);
            }
        }
    }

    /**
     * @param bool $paginate 是否分页
     * @return \think\Collection|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function search(bool $paginate = true)
    {
        $query = $this->query->field($this->fields);

        return $paginate ? $query->paginate($this->pageSize) : $query->select();
    }
}