<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Operator\Comparison\NotEqual;

it('can compare two columns by not equality check')
    ->expect(new NotEqual('val1', 'val2'))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->string('val1');
        $table->string('val2');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val1` != `val2`)')
    ->toBePgsql('("val1" != "val2")')
    ->toBeSqlite('("val1" != "val2")')
    ->toBeSqlsrv('([val1] != [val2])');

it('can compare two expressions by not equality check')
    ->expect(new NotEqual(new Expression(1), new Expression(2)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 != 2)')
    ->toBePgsql('(1 != 2)')
    ->toBeSqlite('(1 != 2)')
    ->toBeSqlsrv('(1 != 2)');

it('can compare an expression and a column by not equality check')
    ->expect(new NotEqual('val', new Expression(0)))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` != 0)')
    ->toBePgsql('("val" != 0)')
    ->toBeSqlite('("val" != 0)')
    ->toBeSqlsrv('([val] != 0)');

it('can compare a column and an expression by not equality check')
    ->expect(new NotEqual(new Expression(0), 'val'))
    ->toBeExecutable(function(Blueprint $table): void {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(0 != `val`)')
    ->toBePgsql('(0 != "val")')
    ->toBeSqlite('(0 != "val")')
    ->toBeSqlsrv('(0 != [val])');
