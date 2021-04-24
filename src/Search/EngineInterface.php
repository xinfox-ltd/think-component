<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Component\Search;

use think\db\Query;

interface EngineInterface
{
    public function initialize(): Query;
}