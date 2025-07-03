<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Function\String\Lower;

it('can lowercase a column')
    ->expect(new Lower('val'))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('val');
    })
    ->toBeMysql('(lower(`val`))')
    ->toBePgsql('lower("val")')
    ->toBeSqlite('(lower("val"))')
    ->toBeSqlsrv('lower([val])');

it('can lowercase an expression')
    ->expect(new Lower(new Expression("'foo'")))
    ->toBeExecutable()
    ->toBeMysql("(lower('foo'))")
    ->toBePgsql("lower('foo')")
    ->toBeSqlite("(lower('foo'))")
    ->toBeSqlsrv("lower('foo')");
