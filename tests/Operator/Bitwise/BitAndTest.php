<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Operator\Bitwise\BitAnd;

it('can bitwise AND two columns')
    ->expect(new BitAnd('val1', 'val2'))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('(`val1` & `val2`)')
    ->toBePgsql('("val1" & "val2")')
    ->toBeSqlite('("val1" & "val2")')
    ->toBeSqlsrv('([val1] & [val2])');

it('can bitwise AND two expressions')
    ->expect(new BitAnd(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 & 2)')
    ->toBePgsql('(1 & 2)')
    ->toBeSqlite('(1 & 2)')
    ->toBeSqlsrv('(1 & 2)');

it('can bitwise AND an expression and a column')
    ->expect(new BitAnd('val', new Expression(0)))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    })
    ->toBeMysql('(`val` & 0)')
    ->toBePgsql('("val" & 0)')
    ->toBeSqlite('("val" & 0)')
    ->toBeSqlsrv('([val] & 0)');

it('can bitwise AND a column and an expression')
    ->expect(new BitAnd(new Expression(0), 'val'))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    })
    ->toBeMysql('(0 & `val`)')
    ->toBePgsql('(0 & "val")')
    ->toBeSqlite('(0 & "val")')
    ->toBeSqlsrv('(0 & [val])');
