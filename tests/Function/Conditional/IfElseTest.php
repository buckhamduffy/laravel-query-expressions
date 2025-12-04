<?php

declare(strict_types=1);

use BuckhamDuffy\Expressions\Value\Value;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Function\Conditional\IfElse;

it('can branch between columns')
    ->expect(new IfElse(
        new Equal('status', new Value('paid')),
        new Expression('amount'),
        new Expression('backup_amount')
    ))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('status');
        $table->integer('amount');
        $table->integer('backup_amount');
    })
    ->toBeMysql("IF((`status` = 'paid'), `amount`, `backup_amount`)")
    ->toBePgsql("CASE WHEN (\"status\" = 'paid') THEN \"amount\" ELSE \"backup_amount\" END")
    ->toBeSqlite("CASE WHEN (\"status\" = 'paid') THEN \"amount\" ELSE \"backup_amount\" END")
    ->toBeSqlsrv("CASE WHEN ([status] = 'paid') THEN [amount] ELSE [backup_amount] END");

it('can branch using literal expressions')
    ->expect(new IfElse(
        new Equal(new Value(1), new Value(1)),
        new Value('yes'),
        new Value('no')
    ))
    ->toBeExecutable()
    ->toBeMysql("IF((1 = 1), 'yes', 'no')")
    ->toBePgsql("CASE WHEN (1 = 1) THEN 'yes' ELSE 'no' END")
    ->toBeSqlite("CASE WHEN (1 = 1) THEN 'yes' ELSE 'no' END")
    ->toBeSqlsrv("CASE WHEN (1 = 1) THEN 'yes' ELSE 'no' END");
