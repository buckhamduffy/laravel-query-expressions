<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\Json\JsonExtract;

it('can extract a scalar value from json')
    ->expect(new JsonExtract('data', '$.user.name'))
    ->toBeExecutable(function (Blueprint $table): void {
        $table->json('data');
    })
    ->toBeMysql("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.user.name'))")
    ->toBePgsql("\"data\" #>> '{user,name}'")
    ->toBeSqlite("json_extract(\"data\", '$.user.name')")
    ->toBeSqlsrv("JSON_VALUE([data], '$.user.name')");

it('can extract json without unquoting')
    ->expect(new JsonExtract('data', '$.items[0].price', unquote: false))
    ->toBeExecutable(function (Blueprint $table): void {
        $table->json('data');
    })
    ->toBeMysql("JSON_EXTRACT(`data`, '$.items[0].price')")
    ->toBePgsql("\"data\" #> '{items,0,price}'")
    ->toBeSqlite("json_extract(\"data\", '$.items[0].price')")
    ->toBeSqlsrv("JSON_QUERY([data], '$.items[0].price')");
