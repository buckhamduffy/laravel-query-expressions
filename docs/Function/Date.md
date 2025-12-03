# Date expressions

Date expressions let you format date/time values without writing driver-specific functions yourself.

## DateFormat
Formats a date/time expression using the correct function for each driver (`DATE_FORMAT`, `FORMAT`, `TO_CHAR`, or `STRFTIME`). Accepts a column name or nested expression plus a format string understood by the target database.

```php
use BuckhamDuffy\Expressions\Function\Date\DateFormat;
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\Order;

$orders = Order::query()->select([
    new Alias(new DateFormat('created_at', '%Y-%m-%d'), 'ordered_on'),
    new Alias(new DateFormat('created_at', '%H:%i'), 'ordered_hour'),
])->get();
```

You can also format computed timestamps:
```php
use BuckhamDuffy\Expressions\Function\Date\DateFormat;
use BuckhamDuffy\Expressions\Function\Time\Now;
use BuckhamDuffy\Expressions\Language\Alias;

$current = new Alias(new DateFormat(new Now(), '%Y-%m-%d %H:%i:%s'), 'current_time');
```
