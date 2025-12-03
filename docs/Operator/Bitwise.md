# Bitwise operators

Bitwise operators provide cross-database bit arithmetic with quoting and driver-specific fallbacks.

## Available classes
- `BitAnd`, `BitOr`, `BitXor`: standard bit operators; `BitXor` uses `#` on PostgreSQL and an emulation on SQLite.
- `BitNot`: unary bitwise NOT.
- `ShiftLeft`, `ShiftRight`: left/right shifts; emulate with multiplication/division on drivers lacking native shift.

## Examples
```php
use BuckhamDuffy\Expressions\Operator\Bitwise\{
    BitAnd, BitNot, BitOr, BitXor, ShiftLeft, ShiftRight
};
use BuckhamDuffy\Expressions\Value\Value;
use App\Models\User;

// Check and set bit flags
$admins = User::where(new BitAnd('permissions', new Value(0b1000)), new Value(0b1000))->get();

// Combine flags and shift for storage
$mask = new BitOr(new ShiftLeft(new Value(1), new Value(3)), new Value(0b0101));

// Clear bits with NOT
$cleared = new BitAnd('permissions', new BitNot(new Value(0b0010)));
```

`ShiftLeft`/`ShiftRight` use `power(2, n)` multiplication/division on MariaDB/MySQL/SQL Server, and native `<<`/`>>` on PostgreSQL/SQLite.
