<?php

declare(strict_types=1);

use BuckhamDuffy\Expressions\Value\Value;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Arithmetic\Add;
use BuckhamDuffy\Expressions\Function\Aggregate\Count;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Function\Conditional\IfElse;
use Illuminate\Database\Query\Expression as RawExpression;
use BuckhamDuffy\Expressions\Function\Conditional\Coalesce;
use BuckhamDuffy\Expressions\Function\Aggregate\CountFilter;
use BuckhamDuffy\Expressions\Operator\Comparison\GreaterThan;

it('can compose multiple expressions together')
    ->expect(
        new Alias(
            new IfElse(
                new GreaterThan(
                    new CountFilter(new Equal('status', new Value('vip'))),
                    new Value(0),
                ),
                new Add(
                    new Value(100),
                    new Count(new RawExpression('*'))
                ),
                new Coalesce([
                    new CountFilter(new Equal('status', new Value('active'))),
                    new Value(0),
                ]),
            ),
            'score'
        )
    )
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('status');
    })
    ->toBeMysql("IF((sum((`status` = 'vip')) > 0), (100 + count(*)), coalesce(sum((`status` = 'active')), 0)) as `score`")
    ->toBePgsql("CASE WHEN (count(*) filter (where (\"status\" = 'vip')) > 0) THEN (100 + count(*)) ELSE coalesce(count(*) filter (where (\"status\" = 'active')), 0) END as \"score\"")
    ->toBeSqlite("CASE WHEN (count(*) filter (where (\"status\" = 'vip')) > 0) THEN (100 + count(*)) ELSE coalesce(count(*) filter (where (\"status\" = 'active')), 0) END as \"score\"")
    ->toBeSqlsrv("CASE WHEN (sum(case when ([status] = 'vip') then 1 else 0 end) > 0) THEN (100 + count(*)) ELSE coalesce(sum(case when ([status] = 'active') then 1 else 0 end), 0) END as [score]");
