<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Component\Http;

class Request extends \XinFox\ThinkPHP\Component\Provider\Request
{
    public function __construct()
    {
        parent::__construct();
        $this->validate();
    }

    /**
     * @return mixed
     */
    protected function rules()
    {
        return [];
    }

    protected function message(): array
    {
        return [];
    }

    /**
     * 初始化验证
     */
    protected function validate()
    {
        $rules = $this->rules();
        if ($rules) {
            // 验证
            $message = $this->message();
            validate($rules, $message);
        }
    }
}