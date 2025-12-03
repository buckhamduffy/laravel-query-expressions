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
