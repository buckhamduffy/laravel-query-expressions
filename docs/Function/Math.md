# Math expressions

Math expressions expose numeric helpers with consistent quoting and driver-aware output.

## Abs
Returns the absolute value of a column or nested expression. On MariaDB/MySQL/SQLite it is wrapped to remain usable as a default value; other drivers render the plain function.

```php
use BuckhamDuffy\Expressions\Function\Math\Abs;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Operator\Arithmetic\Subtract;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Transaction;

$transactions = Transaction::query()->select([
    'id',
    new Alias(
        new Abs(new Subtract('debit', new Value(1000))),
        'offset_from_threshold'
    ),
])->get();
```

`Abs` can be combined freely with arithmetic operators or used inside aggregates if you need absolute values before summing.
