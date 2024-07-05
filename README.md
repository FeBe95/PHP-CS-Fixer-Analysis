# PHP-CS-Fixer Analysis

Copy whole folder to root of any repo that uses [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer).
Run from repo root folder. 

## Impact

Analyses how many files are impacted by testing each fixer individually.

```
php .\PHP-CS-Fixer-Analysis\impact.procedural.php
```

## Overridden Methods

Analyses if code base overrides any vanilla-PHP methods.

```
php .\PHP-CS-Fixer-Analysis\overridden-methods.php
php .\PHP-CS-Fixer-Analysis\overridden-methods.php -v
php .\PHP-CS-Fixer-Analysis\overridden-methods.php --verbose
php .\PHP-CS-Fixer-Analysis\overridden-methods.php -vv
php .\PHP-CS-Fixer-Analysis\overridden-methods.php --very-verbose
```

## Risky Descriptions

Prints all reasons why a fixer is risky, alphabetically and one-by-one.

```
php .\PHP-CS-Fixer-Analysis\risky-descriptions.php
```

## Rule Defaults

Prints all default configurations, defined by the fixers themselves.

```
php .\PHP-CS-Fixer-Analysis\rule-defaults.php
```

## Rule Set Configs

Prints all rule set configs (expands sub-presets).

```
php .\PHP-CS-Fixer-Analysis\rule-set-configs.php Symfony
php .\PHP-CS-Fixer-Analysis\rule-set-configs.php Symfony Symfony:risky PSR12 PSR12:risky
```
