# Laravel Query Expressions to replace DB::raw() calls

![Supported PHP Versions](https://img.shields.io/badge/PHP-8.1%2B-blue?style=flat-square)
![Supported Laravel Versions](https://img.shields.io/badge/Laravel-10%2B-blue?style=flat-square)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/buckhamduffy/laravel-expressions.svg?style=flat-square)](https://packagist.org/packages/buckhamduffy/laravel-expressions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/buckhamduffy/laravel-query-expressions/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/buckhamduffy/laravel-query-expressions/actions/workflows/tests.yml?query=workflow%3Atests+branch%3Amain)
[![GitHub Static Analysis Action Status](https://img.shields.io/github/actions/workflow/status/buckhamduffy/laravel-query-expressions/static-analysis.yml?branch=main&label=static%20analysis&style=flat-square)](https://github.com/buckhamduffy/laravel-query-expressions/actions/workflows/static-analysis.yml?query=workflow%3Atests+branch%3Amain)

Laravel's database implementation provides a good way of working with multiple databases while abstracting away their inner workings.
You don't have to consider minor syntax differences when using a query builder or how each database handles specific operations slightly differently.

However, when we want to use more database functionality than Laravel provides, we must fall back to raw SQL expressions and write database-specific code.
The Query Expressions package builds on new features introduced in Laravel 10 to solve that problem.
All provided implementations abstract some SQL functionality that is automatically transformed to the correct syntax with the same behaviour for your used database engine.
And if your version is still supported by Laravel but is missing a feature, it is emulated by the implementations.
So you can even do things that were not possible before.

You can make your queries database independent:
```php
// Instead of:
User::query()
    ->when(isPostgreSQL(), fn ($query) => $query->selectRaw('coalesce("user", "admin") AS "value"'))
    ->when(isMySQL(), fn ($query) => $query->selectRaw('coalesce(`user`, `admin`) AS `value`'))

// You can use:
User::select(new Alias(new Coalesce(['user', 'admin']), 'value'));
```

And you can also create new powerful queries:
```php
// Aggregate multiple statistics with one query for dashboards:
Movie::select([
    new CountFilter(new Equal('released', new Value(2021))),
    new CountFilter(new Equal('released', new Value(2022))),
    new CountFilter(new Equal('genre', new Value('Drama'))),
    new CountFilter(new Equal('genre', new Value('Comedy'))),
])->where('streamingservice', 'netflix');
```

## Installation

You can install the package via composer:

```bash
composer require buckhamduffy/laravel-expressions
```

## Usage

This package implements a lot of expressions you can use for selecting data, do better filtering or ordering of rows.
For an overview of the in-depth docs, see `docs/README.md`.
Every expression can be used exactly as stated by the documentation, but you can also combine them as shared in the example before.
Whenever an expression class needs a `string|Expression` parameter, you can pass a column name or another (deeply nested) expression object.

> **Note**
> A string passed for a `string|Expression` parameter is always used as a column name that will be automatically quoted.

> **Warning**
> The generated SQL statements of the examples are only for explanatory purposes.
> The real ones will be automatically tailored to your database using proper quoting and its specific syntax.

### Language

#### Values

As stated before, an expression is always a column name.
But if you want to e.g. do an equality check, you may want to compare something to a specific value.
That's where you should use the `Value` class.
Its values will always be automatically escaped within the query.

```php
use BuckhamDuffy\Expressions\Value\Value;

new Value(42);
new Value("Robert'); DROP TABLE students;--");
```

> **Note**
> The `Value` class in isolation is not that usefull.
> But it will be used more in the next examples.

#### Alias

```php
use Illuminate\Contracts\Database\Query\Expression;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Value\Value;

new Alias(string|Expression $expression, string $name)

User::select([
    new Alias('last_modified_at', 'modification_date'),
    new Alias(new Value(21), 'min_age_threshold'),
])->get();
```

> **Note**
> The `Alias` class in isolation is not that usefull because Eloquent can already do this.
> It's useful in conjunction with other expressions though.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [tpetry](https://github.com/tpetry)
- [aaronflorey](https://github.com/aaronflorey)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
