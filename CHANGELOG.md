# Changelog

- - -
## v1.7.0 - 2025-07-23
#### Chore
- update .editorconfig - (6fe19dc) - BuckhamBot
#### Features
- add date_format and concat_ws - (5416886) - Aaron Florey

- - -

## v1.6.1 - 2025-07-03
#### Bug Fixes
- OrValues should be Conditional - (36b66b8) - Aaron Florey

- - -

## v1.6.0 - 2025-07-03
#### Features
- fork repository, add new expressions - (7dc63e9) - Aaron Florey

- - -


## [1.5.0] - 2025-02-14
### Added
* Laravel 12 support

## [1.4.0] - 2024-08-29
### Added
* `IS NULL` and `IS NOT NULL` conditions

## [1.3.0] - 2024-05-31
### Added
* `Cast` language construct

## [1.2.0] - 2024-03-12
### Added
* Laravel 11 compatibility

## [1.1.0] - 2024-02-12
### Added
* Arithmetic expressions support variadic arguments

## [1.0.0] - 2024-01-30
### Added
* Declared semver stability for the package

## [0.9.0] - 2023-11-30
### Added
* Case-when syntax

## [0.8.0] - 2023-11-10
### Added
* `Abs` mathematical function

## [0.7.1] - 2023-09-18
### Added
* Argument type of ManyArgumentsExpressions missed passing column names as string

## [0.7.0] - 2023-09-01
### Added
* `Upper` and `Lower` string transformations

## [0.6.0] - 2023-08-31
### Added
* `Count` aggregate has an optional distinct parameter

## [0.5.1] - 2023-06-26
### Fixed
* Alias names with dots did not work

## [0.5.0] - 2023-06-09
### Added
* Conditional and comparison expressions can directly be used as sole where() parameter
* StrListContains expression to emulate MySQL's FIND_IN_SET() for all databases
* Concat expression to harmonize string concatenation

## [0.4.0] - 2023-06-01
### Added
* `Value` class to embed any escaped value into a query

## [0.3.0] - 2023-05-16
### Added
* Current time
* Timestamp binning

## [0.2.0] - 2023-04-21
### Added
* UUIDv4 generation

## [0.1.0] - 2023-03-16
### Added
* Initial release of query expressions
