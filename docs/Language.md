# Language helpers

Language helpers provide building blocks that wrap or transform other expressions. They handle quoting, driver-specific casting, and conditional branches so you can compose complex SQL without raw strings.

## Available classes
- `Alias`: rename any expression with database-aware quoting for the alias.
- `Cast`: cast an expression to `int`, `bigint`, `float`, or `double` using driver-specific syntax and workarounds.
- `CaseGroup` + `CaseRule`: build `CASE WHEN … THEN … ELSE … END` structures fluently.

## Aliasing expressions
```php
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Arithmetic\Add;
use BuckhamDuffy\Expressions\Value\Value;

$alias = new Alias(new Add('quantity', new Value(5)), 'quantity_with_buffer');
```

## Casting values
```php
use BuckhamDuffy\Expressions\Language\Cast;
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\Invoice;

$invoices = Invoice::query()->select([
    'id',
    new Alias(new Cast('invoice_number', 'int'), 'invoice_no_int'),
])->get();
```

`Cast` handles differences like MySQL 5.7’s lack of floating-point casts by using numeric coercion, and SQL Server’s need for explicit precision.

## Case expressions
You can construct `CASE` expressions either directly with `CaseGroup` + `CaseRule` or fluently via `when()/then()` on `CaseGroup`.

```php
use BuckhamDuffy\Expressions\Language\{CaseGroup, CaseRule};
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Comparison\GreaterThan;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\User;

$tier = new CaseGroup(
    when: [
        new CaseRule(new Value('gold'), new GreaterThan('reward_points', new Value(500_000))),
        new CaseRule(new Value('silver'), new GreaterThan('reward_points', new Value(100_000))),
        new CaseRule(new Value('bronze'), new GreaterThan('reward_points', new Value(50_000))),
        new CaseRule(new Value('basic'), new GreaterThan('reward_points', new Value(0))),
    ],
    else: new Value('none'),
);

$users = User::query()->select([
    'id',
    new Alias($tier, 'tier'),
])->get();
```

### Fluent `when/then` builder
```php
use BuckhamDuffy\Expressions\Language\CaseGroup;
use BuckhamDuffy\Expressions\Operator\Comparison\Equal;
use BuckhamDuffy\Expressions\Value\Value;

$status = CaseGroup::make()
    ->when(new Equal('role', new Value(3)), new Value('Admin'))
    ->when(new Equal('role', new Value(2)), new Value('Editor'))
    ->when(new Equal('role', new Value(1)), new Value('Viewer'))
    ->then(new Value('Unknown'));
```

Wrap the `CaseGroup` in an `Alias` when selecting to name the computed column.
