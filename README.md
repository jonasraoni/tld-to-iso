# TLD <=> ISO

Mappings from top level domains (TLDs) to ISO 3166-1 alpha-2 and vice-versa.

## Usage

Install the package:

```
composer require jonasraoni/tld-to-iso
```

The package provides 2 separated mappings (just to decrease the amount of processing/bloat data loaded into memory):

- [JonasRaoni\TldToIso\IsoToTld](src/IsoToTld.php)
- [JonasRaoni\TldToIso\TldToIso](src/TldToIso.php)

Choose the one that fits your needs and call the static method `get`, it might return the right mapping or null if it's invalid.

```php
use JonasRaoni\TldToIso\TldToIso;

echo 'UK = ' . TldToIso::get('UK');
```

## Data Generation

To regenerate the classes, ensure the submodules are updated, then call the [build.php](build.php) script (just a simple code generator) and it will overwrite the existing classes.

## Data Source

- The list of countries/TLDs was retrieved from this link: https://datahub.io/core/country-codes (the .csv is linked to this repository as a submodule). The list isn't complete, as it's missing IDN entries (e.g. `.рф`) and custom domains (e.g. `.barcelona`), but let it be... The [Wikipedia's entry](https://en.wikipedia.org/wiki/List_of_Internet_top-level_domains#Internationalized_country_code_top-level_domains) looks more complete, but I'll be unable to keep it synchronized.
