<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Provider;

use think\facade\Log;
use XinFox\Auth\Auth;
use XinFox\Auth\Guest;
use XinFox\Auth\VisitorInterface;

class Request extends \think\Request
{
    protected VisitorInterface $visitor;

    public function __construct()
    {
        parent::__construct();

        // 默认访客
        $visitor = new Guest();

        $authorization = $this->header('Authorization');
        if ($authorization !== null) {
            $token = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $authorization));
            if ($token) {
                try {
                    $visitor = app(Auth::class)->user($token);
                } catch (\Exception $e) {
                    Log::info($e);
                }
            }
        }

        $this->setVisitor($visitor);
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