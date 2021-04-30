<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Provider;

use DomainException;

class Paginator extends \think\Paginator
{
    public function render()
    {
        // TODO: Implement render() method.
    }

    public function toArray(): array
    {
        try {
            $total = $this->total();
        } catch (DomainException $e) {
            $total = null;
        }

        return [
            'total' => $total,
            'per_page' => $this->listRows(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage,
            'rows' => $this->items->toArray(),
        ];
    }
}