<?php

namespace UnstoppableCarl\StaticDBData\Exceptions;

use Exception;
use Throwable;

class StaticDBDataNotFoundException extends Exception
{
    public function __construct(
        string $staticDBDataClass,
        $value,
        string $key,
        int $code = 0,
        Throwable $previous = null
    ) {
        $message = "{$key}: {$value}, not found in StaticDBData class: {$staticDBDataClass}.";

        parent::__construct($message, $code, $previous);
    }
}
