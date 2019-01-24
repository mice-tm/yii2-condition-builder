<?php

namespace micetm\conditions\models\constructor\exceptions;

use Throwable;

class WrongComparison extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Wrong comparison type!", $code, $previous);
    }
}