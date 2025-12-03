# Time expressions

Time expressions help with current timestamps, bucketing, and differences across databases.

## Now
Driver-aware current timestamp (`current_timestamp`, `statement_timestamp()`, etc.). Wrapped where required so it can be used as a default value in migrations.

```php
use BuckhamDuffy\Expressions\Function\Time\Now;
use BuckhamDuffy\Expressions\Language\Alias;

$now = new Alias(new Now(), 'generated_at');
```

## TimestampBin
Buckets a timestamp into fixed intervals. Accepts a `DateInterval` step and optional origin epoch. Generates `FROM_UNIXTIME`, `to_timestamp`, `datetime(..., 'unixepoch')`, or `dateadd` depending on driver.

```php
use BuckhamDuffy\Expressions\Function\Time\TimestampBin;
use BuckhamDuffy\Expressions\Function\Aggregate\Count;
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\BlogVisit;

$binned = BlogVisit::query()->select([
    'url',
    new Alias(new TimestampBin('created_at', \DateInterval::createFromDateString('10 minutes')), 'bucket'),
    new Alias(new Count('*'), 'visits'),
])->groupBy('url', 'bucket')->get();
```

## TimestampDiff
Computes the difference between two timestamps in a given unit (`SECOND`, `MINUTE`, `HOUR`, `DAY`, `WEEK`, `MONTH`, `QUARTER`, `YEAR`) with driver-specific SQL.

```php
use BuckhamDuffy\Expressions\Function\Time\TimestampDiff;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Session;

$sessions = Session::query()->select([
    'id',
    new Alias(
        new TimestampDiff('MINUTE', 'started_at', new Value('2023-12-01 12:00:00')),
        'minutes_elapsed'
    ),
])->get();
```

`TimestampDiff` can also be combined with conditional expressions (e.g. `IfElse`) to flag long-running items based on duration thresholds.
