<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Provider;

use think\App;
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
        $this->setVisitor($visitor);
    }

    public static function __make(App $app): Request
    {
        $request = parent::__make($app);

        $authorization = $request->header('Authorization');
        if ($authorization !== null && $authorization != 'Bearer' && is_string($authorization)) {
            $token = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $authorization));
            if ($token) {
              try {
                $visitor = app(Auth::class)->user($token);
                $request->setVisitor($visitor);
              } catch (\Throwable $e) {
                Log::error("解析token异常：" . $e->getMessage());
              }
            }
        }

        return $request;
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