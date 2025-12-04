<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\Json\JsonAggregate;

it('can aggregate json values')
    ->expect(new JsonAggregate('payload', 'payloads'))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->json('payload');
    }, ['skip' => ['sqlsrv']])
    ->toBeMysql('JSON_ARRAYAGG(`payload`) as `payloads`')
    ->toBePgsql('json_agg("payload") as "payloads"')
    ->toBeSqlite('json_group_array("payload") as "payloads"');
