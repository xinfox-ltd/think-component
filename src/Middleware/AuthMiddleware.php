<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Middleware;

use Casbin\Enforcer;
use Closure;
use think\helper\Str;
use XinFox\Auth\Auth;
use XinFox\Auth\Exception\ForbiddenException;
use XinFox\Auth\Exception\UnauthorizedException;
use XinFox\ThinkPHP\Provider\Request;

class AuthMiddleware
{
    protected Auth $auth;

    protected Enforcer $enforcer;

    public function __construct(Auth $auth, Enforcer $enforcer)
    {
        $this->auth = $auth;
        $this->enforcer = $enforcer;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed $roles
     * @return mixed
     * @throws UnauthorizedException
     * @throws ForbiddenException
     * @throws \Casbin\Exceptions\CasbinException
     */
    public function handle(Request $request, Closure $next, $roles = ['*'])
    {
        $visitor = $request->getVisitor();

        $visitorRole = Str::lower($visitor->getRole());
        if ($visitorRole == 'root' || in_array('*', $roles)) {
            $request->setVisitor($visitor);
            return $next($request);
        }

        if (in_array($visitorRole, $roles)) {
            // 管理员角色另外处理，TODO 统一使用casbin处理权限问题
            if (in_array($visitor->getRole(), ['admin', 'merchant_admin'])
                && !$this->enforcer->enforce($visitorRole, $request->pathinfo(), $request->method())) {
                throw new ForbiddenException();
            }

            $request->setVisitor($visitor);

            return $next($request);
        }

        throw new ForbiddenException();
    }
}