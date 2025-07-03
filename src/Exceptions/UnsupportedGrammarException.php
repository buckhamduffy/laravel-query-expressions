<?php

namespace BuckhamDuffy\Expressions\Exceptions;

use Exception;
use Illuminate\Contracts\Database\Query\Expression;

class UnsupportedGrammarException extends Exception
{
    public function __construct(string $grammar, Expression $expression)
    {
        parent::__construct(
            \sprintf('Unsupported grammar "%s" for expression "%s".', $grammar, class_basename($expression)),
        );
    }
}
