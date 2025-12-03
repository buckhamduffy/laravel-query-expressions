# Comparison expressions

Comparison expressions extend the built-in operators with driver-aware helpers. They implement `ConditionExpression`, so you can drop them into `where()` clauses, filtered aggregates, or other expressions that expect a boolean condition.

## StrListContains
Checks whether a comma-separated string list contains a value. It targets the correct SQL for each driver (`FIND_IN_SET` on MySQL/MariaDB, `like` patterns on PostgreSQL/SQLite, and `concat` + `like` on SQL Server) while automatically quoting columns or rendering nested expressions.

### Basic filtering
```php
use BuckhamDuffy\Expressions\Function\Comparison\StrListContains;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Post;

// posts.tags holds a comma-separated list like "php,laravel,databases"
$posts = Post::query()
    ->where(new StrListContains('tags', new Value('laravel')))
    ->get();
```

### Combining with another expression
You can embed `StrListContains` into other expressions, such as `CountFilter`, to aggregate only rows whose list contains a value.

```php
use BuckhamDuffy\Expressions\Function\Aggregate\CountFilter;
use BuckhamDuffy\Expressions\Function\Comparison\StrListContains;
use BuckhamDuffy\Expressions\Language\Alias;
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\Post;

// Count how many posts include the "laravel" tag
$tagCounts = Post::query()->select([
    new Alias(
        new CountFilter(
            new StrListContains('tags', new Value('laravel'))
        ),
        'laravel_posts'
    ),
])->first();
```

> If you can, prefer normalized relational data over comma-separated lists. `StrListContains` is useful when working with legacy schemas or third-party data you cannot change.
