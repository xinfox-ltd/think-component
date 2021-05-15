<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Middleware;

use Closure;
use think\helper\Str;
use XinFox\Auth\Auth;
use XinFox\Auth\Exception\ForbiddenException;
use XinFox\Auth\Exception\UnauthorizedException;
use XinFox\ThinkPHP\Provider\Request;

class AuthMiddleware
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed $roles
     * @return mixed
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function handle(Request $request, Closure $next, $roles = ['*'])
    {
        $authorization = $request->header('Authorization');
        if ($authorization !== null) {
            $token = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $authorization));

            $visitor = $this->auth->user($token);
            $visitorRole = Str::lower($visitor->getRole());
            if (in_array('*', $roles) || in_array($visitorRole, $roles)) {
                $request->setVisitor($visitor);

                return $next($request);
            }

            throw new ForbiddenException();
        }

        throw new UnauthorizedException();
    }
}