<?php

use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\String\SplitPart;

it('can combine multiple columns')
    ->expect(
        new SplitPart('val')
    )
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    }, ['skip' => ['sqlite', 'sqlsrv']])
    ->toBeMysql('SUBSTRING_INDEX(SUBSTRING_INDEX(`val`, \'-\', 1), \'-\', -1)')
    ->toBePgsql('SPLIT_PART("val", \'-\', 1)');
