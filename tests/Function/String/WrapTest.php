<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\String\Wrap;
use BuckhamDuffy\Expressions\Function\String\Concat;

it('wraps another expression')
    ->expect(new Wrap(new Concat(['first', 'last'])))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('first');
        $table->string('last');
    })
    ->toBeMysql('((concat(`first`,`last`)))')
    ->toBePgsql('(("first"||"last"))')
    ->toBeSqlite('(("first"||"last"))')
    ->toBeSqlsrv('((concat([first],[last])))');
