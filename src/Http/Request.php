<?php

/**
 * [XinFox System] Copyright (c) 2011 - 2021 XINFOX.CN
 */
declare(strict_types=1);

namespace XinFox\ThinkPHP\Http;

use XinFox\ThinkPHP\Provider\Request as ProviderRequest;

class Request
{
    protected bool $batchValidate = false;

    protected ProviderRequest $request;

    public function __construct(ProviderRequest $request)
    {
        $this->request = $request;
        $this->validate();
    }

    protected function beforeValidate()
    {
    }

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    protected function message(): array
    {
        return [];
    }

    /**
     * 初始化验证
     * @param array|null $data
     */
    protected function validate(array $data = null)
    {
        $this->beforeValidate();

        $rules = $this->rules();
        if ($rules && ($this->request->isPost() || $this->request->isPut())) {
            // 验证
            $message = $this->message();
            validate($rules, $message, $this->batchValidate)->check($data ?? $this->request->param());
        }
    }
}