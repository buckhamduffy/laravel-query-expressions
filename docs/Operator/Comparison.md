# Comparison operators

Comparison operators implement common predicates as `ConditionExpression` objects, ready for `where()`, conditional expressions, or filtered aggregates.

## Available classes
- Equality/ordering: `Equal`, `NotEqual`, `GreaterThan`, `GreaterThanOrEqual`, `LessThan`, `LessThanOrEqual`.
- Null checks: `IsNull`, `NotIsNull`.
- Set membership: `In`, `Between`, `OrValues`.
- Null-safe equality: `DistinctFrom`, `NotDistinctFrom` (driver-aware fallbacks for databases without native support).

## Examples
```php
use BuckhamDuffy\Expressions\Operator\Comparison\{
    Between, DistinctFrom, Equal, In, IsNull, NotDistinctFrom
};
use BuckhamDuffy\Expressions\Value\Number;
use App\Models\Order;

$orders = Order::query()
    ->where(new Between('total_cents', new Number(10_00), new Number(50_00)))
    ->where(new In('status', ['pending', 'paid']))
    ->where(new DistinctFrom('coupon_code', new Number(0))) // handle NULLs safely
    ->get();
```

### Fluent `In` builder
```php
use BuckhamDuffy\Expressions\Operator\Comparison\In;
use BuckhamDuffy\Expressions\Value\Number;

$ids = In::make('user_id')
    ->value(new Number(1))
    ->value(new Number(2))
    ->value(new Number(3));
```

`DistinctFrom`/`NotDistinctFrom` ensure predictable NULL handling, emulating `IS [NOT] DISTINCT FROM` on drivers that lack it.
