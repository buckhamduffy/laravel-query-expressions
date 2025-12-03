# String expressions

String expressions offer portable concatenation, casing, formatting, and UUID generation without raw SQL.

## Available classes
- `Concat`: concatenate expressions (`concat()` on MySQL/MariaDB/SQL Server, `||` on PostgreSQL/SQLite).
- `ConcatWs`: concatenate with a separator; always uses `CONCAT_WS`.
- `Format`: number formatting via `FORMAT()` on MySQL/MariaDB.
- `Lower` / `Upper`: force case on a column or expression.
- `SplitPart`: extract the Nth chunk from a delimited string (`SPLIT_PART` on PostgreSQL, `SUBSTRING_INDEX` on MySQL/MariaDB) with optional casting.
- `Uuid4`: generate a UUID v4 value across drivers (requires PostgreSQL 13+ or MariaDB 10.10+).
- `Wrap`: wrap any expression or subquery in parentheses.

## Concatenation and normalization
```php
use BuckhamDuffy\Expressions\Function\String\{
    Concat, ConcatWs, Lower, SplitPart, Upper
};
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\User;

$users = User::query()->select([
    new Alias(new Concat(['first_name', ' ', 'last_name']), 'full_name'),
    new Alias(new ConcatWs('-', ['country_code', 'area_code', 'number']), 'phone'),
    // grab the domain from an email and normalize to lowercase
    new Alias(
        new Lower(new SplitPart('email', '@', 2)),
        'email_domain'
    ),
])->get();
```

## UUID generation and formatting
```php
use BuckhamDuffy\Expressions\Function\String\{
    Format, Uuid4, Upper
};
use Illuminate\Database\Schema\Blueprint;
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\Invoice;

// use as a default for new rows
Schema::table('invoices', function (Blueprint $table): void {
    $table->uuid()->default(new Uuid4());
});

$invoices = Invoice::query()->select([
    'id',
    // human-friendly amount with two decimals on MySQL/MariaDB
    new Alias(new Format('total', 2), 'total_formatted'),
    // uppercase UUID for display while storing lowercase
    new Alias(new Upper(new Uuid4()), 'display_uuid'),
])->get();
```

> `Format` is only available on MySQL/MariaDB. If you need cross-database formatting, consider using application-layer formatting or casts instead.
