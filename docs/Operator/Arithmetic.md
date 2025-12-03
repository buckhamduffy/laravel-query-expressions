# Arithmetic operators

Arithmetic operators wrap basic math so you can compose calculations without raw SQL. They accept columns or nested expressions and quote names automatically.

## Available classes
- `Add`, `Subtract`, `Multiply`, `Divide`, `Modulo`: infix operators, rendered with parentheses to preserve order.
- `Power`: exponentiation across drivers (`^` on PostgreSQL, `power()` chains elsewhere) with support for more than two operands.

## Examples
```php
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Arithmetic\{
    Add, Divide, Multiply, Power, Subtract
};
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Product;

$products = Product::query()->select([
    'id',
    new Alias(new Subtract('price_cents', new Value(500)), 'discounted_cents'),
    new Alias(
        new Multiply(
            new Subtract('price_cents', 'discount_cents'),
            new Divide(new Value(20), new Value(100))
        ),
        'vat_cents'
    ),
    new Alias(new Power('length', 'width', 'height'), 'volume_power_chain'),
])->get();
```

`Power` chains multiple operands by nesting `power(a, b)` calls on drivers that lack a multi-operand `^` operator; PostgreSQL uses native `^`.
