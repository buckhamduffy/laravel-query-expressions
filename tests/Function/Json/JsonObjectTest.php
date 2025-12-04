<?php

declare(strict_types=1);

use BuckhamDuffy\Expressions\Value\Value;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\Json\JsonObject;

it('can build a json object from columns and values')
    ->expect(
        JsonObject::make()
            ->item('name', 'name')
            ->item('status', new Value('active'))
    )
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('name');
    }, ['skip' => ['sqlsrv']])
    ->toBeMysql("JSON_OBJECT('name', `name`, 'status', 'active')")
    ->toBePgsql("json_build_object('name', \"name\", 'status', 'active')")
    ->toBeSqlite("json_object('name', \"name\", 'status', 'active')");
