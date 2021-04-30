<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Http;

class Request extends \XinFox\ThinkPHP\Provider\Request
{
    protected bool $batchValidate = false;

    public function __construct()
    {
        parent::__construct();
        $this->validate();
    }

    protected function beforeValidate()
    {
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
        $this->beforeValidate();

        $rules = $this->rules();
        if ($rules && $this->isPost()) {
            // 验证
            $message = $this->message();
            validate($rules, $message, $this->batchValidate)->check($this->post());
        }
    }
}