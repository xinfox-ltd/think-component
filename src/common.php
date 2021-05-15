<?php
/**
 * 应用公共文件
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */

declare(strict_types=1);

use think\response\Json;

if (!function_exists('json_response')) {
    /**
     * @param $data
     * @param $message
     * @param $code
     * @return Json
     */
    function json_response($data, $message, $code): Json
    {
        return json(
            [
                'data' => $data,
                'code' => $code,
                'message' => $message,
            ],
            200
        );
    }
}

if (!function_exists('error_response')) {
    function error_response($message = '数据错误', $code = 400, $data = null): Json
    {
        return json_response($data, $message, $code);
    }
}

if (!function_exists('error401_response')) {
    function error401_response(): Json
    {
        return json_response(null, 'Unauthorized', 401);
    }
}

if (!function_exists('error403_response')) {
    function error403_response(): Json
    {
        return json_response(null, '没有权限', 403);
    }
}

if (!function_exists('error404_response')) {
    function error404_response($message = '数据不存在'): Json
    {
        return error_response($message, 404, []);
    }
}

if (!function_exists('success_response')) {
    function success_response($data = null, string $message = 'success', int $code = 0): Json
    {
        return json_response($data, $message, $code);
    }
}
