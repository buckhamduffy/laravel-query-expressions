<?php

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Value\Number;
use BuckhamDuffy\Expressions\Function\Conditional\NotIn;

it('can check value not in a set of values')
    ->expect(
        NotIn::make('val')
            ->value(new Number(1))
            ->value(new Number(2))
            ->value(new Number(3))
    )
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    })
    ->toBeMysql('`val` NOT IN (1, 2, 3)')
    ->toBePgsql('"val" NOT IN (1, 2, 3)')
    ->toBeSqlite('"val" NOT IN (1, 2, 3)')
    ->toBeSqlsrv('[val] NOT IN (1, 2, 3)');
