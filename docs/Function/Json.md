# JSON expressions

JSON expressions provide portable JSON path extraction with automatic quoting and driver-aware syntax.

## JsonExtract
Extracts a value from a JSON document using a JSON path (e.g. `$.user.name` or `$.items[0].price`). The `unquote` flag controls whether scalars are unwrapped (`JSON_UNQUOTE`/`JSON_VALUE`) or left as JSON text/objects.

```php
use BuckhamDuffy\Expressions\Function\Json\JsonExtract;
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\Profile;

$profiles = Profile::query()->select([
    new Alias(new JsonExtract('preferences', '$.language'), 'language'),
    new Alias(new JsonExtract('preferences', '$.notifications.email', unquote: true), 'email_notifications'),
])->get();
```

### Using inside other expressions
`JsonExtract` can be nested anywhere an expression is expected—for example inside `Coalesce` to fall back to a default when a path is missing.

```php
use BuckhamDuffy\Expressions\Function\Conditional\Coalesce;
use BuckhamDuffy\Expressions\Function\Json\JsonExtract;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Value\Value;

$timezone = new Alias(
    new Coalesce([
        new JsonExtract('preferences', '$.timezone'),
        new Value('UTC'),
    ]),
    'tz'
);
```

## JsonAggregate
Aggregates JSON values into an array: `JSON_ARRAYAGG` on MySQL/MariaDB, `json_agg` on PostgreSQL, and `json_group_array` on SQLite. SQL Server is not supported and will throw an `UnsupportedGrammarException`.

```php
use BuckhamDuffy\Expressions\Function\Json\JsonAggregate;
use BuckhamDuffy\Expressions\Language\Alias;
use App\Models\Order;

$orders = Order::query()->select([
    new Alias(new JsonAggregate('metadata', 'all_metadata'), 'all_metadata'),
])->first();
```

## JsonObject
Builds a JSON object from mixed columns and values: `JSON_OBJECT` on MySQL/MariaDB, `json_build_object` on PostgreSQL, and `json_object` on SQLite. SQL Server is not supported and will throw an `UnsupportedGrammarException`.

```php
use BuckhamDuffy\Expressions\Function\Json\JsonObject;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Value\Value;

$customer = Customer::query()->select([
    new Alias(
        JsonObject::make()
            ->item('name', 'full_name')
            ->item('vip', new Value(true)),
        'profile'
    ),
])->first();
```
