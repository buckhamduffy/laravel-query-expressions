# Conditional expressions

Conditional expressions let you branch or select fallback values in SQL without hand-written `CASE` statements. All classes accept columns or nested expressions, quote column names automatically, and render dialect-specific syntax when needed.

## Available classes
- `Coalesce`: return the first non-null expression from a non-empty array.
- `Greatest`: return the maximum expression across drivers (emulated on SQLite and SQL Server).
- `Least`: return the minimum expression across drivers (emulated on SQLite and SQL Server).
- `IfElse`: simple boolean branch (`IF` on MySQL/MariaDB, `CASE WHEN` elsewhere).
- `NotIn`: check that a column or expression is not present in a provided set.
- `NotInOrNull`: `NotIn` with an additional `IS NULL` allowance.

## Fallbacks and ordering
```php
use BuckhamDuffy\Expressions\Function\Conditional\{
    Coalesce, Greatest,
};
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\User;

$users = User::query()->select([
    // pick the first available contact detail
    new Alias(
        new Coalesce(['primary_email', 'backup_email', new Value('unavailable')]),
        'contact'
    ),
    // latest activity across multiple timestamps
    new Alias(
        new Greatest(['last_login_at', 'last_purchase_at', 'last_profile_update_at']),
        'last_activity_at'
    ),
])->get();
```

## Branching and membership
```php
use BuckhamDuffy\Expressions\Function\Conditional\{
    IfElse, NotIn, NotInOrNull,
};
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Comparison\GreaterThanOrEqual;
use BuckhamDuffy\Expressions\Value\Number;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Invoice;

$invoices = Invoice::query()->select([
    // label invoices as high or standard value
    new Alias(
        new IfElse(
            new GreaterThanOrEqual('total_cents', new Number(50_00)), // >= $50
            new Value('high'),
            new Value('standard')
        ),
        'value_band'
    ),
])->where(
    // exclude test customers, but allow null customer_id
    NotInOrNull::make('customer_id')
        ->value(new Number(1))
        ->value(new Number(2))
        ->value(new Number(3))
)->get();
```

`NotIn` and `NotInOrNull` support a fluent `->value()` builder as shown, or you can pass the values array directly to the constructor if you already have the list.
