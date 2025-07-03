<?php

declare(strict_types=1);

namespace BuckhamDuffy\Expressions\Function\Comparison;

use Illuminate\Database\Grammar;
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Concerns\IdentifiesDriver;
use BuckhamDuffy\Expressions\Concerns\StringizeExpression;
use Illuminate\Contracts\Database\Query\ConditionExpression;

class StrListContains implements ConditionExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $strList,
        private readonly string|Expression $str,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $strList = $this->stringize($grammar, $this->strList);
        $str = $this->stringize($grammar, $this->str);

        // PostgreSQL: The string_to_array is not used because citext values would be cast to case-sensitive text type
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => \sprintf('FIND_IN_SET(%s, %s) > 0', $str, $strList),
            'pgsql', 'sqlite' => "({$strList} like {$str} or {$strList} like (({$str})||',%') or {$strList} like ('%,'||({$str})||',%') or {$strList} like ('%,'||({$str})))",
            'sqlsrv' => "({$strList} like {$str} or {$strList} like concat({$str},',%') or {$strList} like concat('%,',{$str},',%') or {$strList} like concat('%,',{$str}))",
        };
    }
}
