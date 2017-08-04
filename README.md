# Custom Number

## About

Custom Number is a Prestashop module for customizing invoice and credit slip numbers.

This module is currently used in production websites with Prestashop 1.6 and PHP 7+.

Warning: this module use some overrides.

## Installation

This module is best used with Composer managing your Prestashop project globally. This method follows best practices for managing external dependencies of a PHP project.

Create or edit `composer.json` in the Prestashop root directory:

```json
"repositories": [
  {
    "type": "git",
    "url": "https://github.com/creativewave/ps-cwcustomnumber"
  }
],
"require": {
  "creativewave/ps-cwmedia": "^1"
},

```

Then run `composer update`.

## Todo

* Fix: admin options titles/choices/descriptions translations.
* Improvement: try to remove overrides.
* Unit tests
