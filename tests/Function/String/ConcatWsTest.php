<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\String\ConcatWs;

it('concats values with a separator')
    ->expect(new ConcatWs('-', ['first', 'second', new Expression("'third'")]))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('first');
        $table->string('second');
    })
    ->toBeMysql("(CONCAT_WS('-', `first`,`second`,'third'))")
    ->toBePgsql("(CONCAT_WS('-', \"first\",\"second\",'third'))")
    ->toBeSqlite("(CONCAT_WS('-', \"first\",\"second\",'third'))")
    ->toBeSqlsrv("(CONCAT_WS('-', [first],[second],'third'))");
