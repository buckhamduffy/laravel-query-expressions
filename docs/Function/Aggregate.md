# Aggregate expressions

Aggregate expressions wrap common SQL aggregate functions so you can compose them like any other expression object without dropping to raw SQL. Every aggregate accepts either a column name or another expression; column names are quoted automatically, and nested expressions are rendered in the correct order for the connected database.

## Available classes
- `Avg`, `Max`, `Min`, `Sum`: share the `AggregateExpression` base and render as `aggregate(value)`.
- `Count`: counts a column or expression, with optional `distinct` support.
- `CountFilter`: counts only rows matching a boolean `Expression` filter. Uses native `filter` syntax on databases that support it and falls back to equivalent `sum/case` patterns elsewhere.
- `SumFilter`: sums a value only when a boolean `Expression` filter matches. Uses native `filter` syntax on PostgreSQL/SQLite and emulates the behaviour with `case` on other drivers.

> The filter-based aggregates require an `Expression` that evaluates to a boolean (e.g. `Equal`, `GreaterThan`, `CaseGroup`). They are driver-aware and render the appropriate SQL for MariaDB, MySQL, PostgreSQL, SQLite, and SQL Server.

## Basic usage
```php
use BuckhamDuffy\Expressions\Function\Aggregate\{
    Avg, Count, Max, Min, Sum,
};
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\Order;

$stats = Order::query()->select([
    new Alias(new Count('*'), 'order_count'),
    new Alias(new Count('customer_id', distinct: true), 'unique_customers'),
    new Alias(new Avg('total'), 'average_total'),
    new Alias(new Max('total'), 'largest_order'),
    new Alias(new Min('total'), 'smallest_order'),
    new Alias(new Sum('total'), 'gross_revenue'),
])->first();
```

## Filtering during aggregation
Filter-aware aggregates let you keep conditional logic inside the expression instead of sprinkling `when` clauses throughout your query builder.

```php
use BuckhamDuffy\Expressions\Function\Aggregate\{CountFilter, SumFilter};
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Comparison\GreaterThanOrEqual;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Order;

$dashboard = Order::query()->select([
    new Alias(new CountFilter(new Equal('status', new Value('paid'))), 'paid_orders'),
    new Alias(
        new SumFilter(
            'total',
            new GreaterThanOrEqual('total', new Value(100_00)) // $100.00 and above
        ),
        'high_value_revenue'
    ),
])->first();
```

The example above:
- counts only orders where `status = 'paid'` using `CountFilter`;
- sums `total` only for orders with totals of at least `$100.00` using `SumFilter`;
- still benefits from database-specific SQL rendering, so the same query works across supported drivers.
