# Logical operators

Logical operators combine other condition expressions, letting you compose complex boolean logic portably.

## Available classes
- `CondAnd`, `CondOr`: binary logical connectors.
- `CondNot`: unary negation.
- `CondXor`: exclusive OR; emulates via boolean algebra on PostgreSQL/SQLite/SQL Server and uses native `xor` on MariaDB/MySQL.

## Examples
```php
use BuckhamDuffy\Expressions\Operator\Logical\{
    CondAnd, CondNot, CondOr, CondXor
};
use BuckhamDuffy\Expressions\Operator\Comparison\{
    GreaterThanOrEqual, LessThan
};
use BuckhamDuffy\Expressions\Value\Number;
use App\Models\Invoice;

$midRange = new CondAnd(
    new GreaterThanOrEqual('total_cents', new Number(10_00)),
    new LessThan('total_cents', new Number(50_00))
);

$notMidRange = new CondNot($midRange);

$exclusiveCondition = new CondXor(
    new GreaterThanOrEqual('discount_percent', new Number(10)),
    new GreaterThanOrEqual('loyalty_points', new Number(1000))
);

$invoices = Invoice::where($exclusiveCondition)->get();
```

All logical operators expect other `ConditionExpression` instances, so you can freely nest comparisons, conditionals, and filtered aggregates.
