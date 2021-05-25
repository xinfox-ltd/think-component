<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Provider;

use XinFox\Auth\Guest;
use XinFox\Auth\VisitorInterface;

class Request extends \think\Request
{
    protected VisitorInterface $visitor;

    public function __construct()
    {
        parent::__construct();
        // 默认访客
        $this->setVisitor(new Guest());
    }

    public function getVisitor(): VisitorInterface
    {
        return $this->visitor;
    }

    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;
    }
}