<?php

declare(strict_types=1);

namespace App\Utils\Aws;

use Aws\Sdk;

abstract class AwsUtil
{
    protected $sdk;

    public function __construct(Sdk $sdk)
    {
        $this->sdk = $sdk;
    }
}