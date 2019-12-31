# Next Day Delivery

[![Build Status](https://travis-ci.com/aeyoll/next-day-delivery.svg?branch=master)](https://travis-ci.com/aeyoll/next-day-delivery)

This repository is a small utility to help knowing if a company is able to ship something for the next day.

Requirements
---

- PHP 7.0+

Installation
---

```sh
composer require aeyoll/next-day-delivery
```

Usage
---

```php
use Aeyoll\NextDayDelivery;

$ndd = new NextDayDelivery();
$isNextDayDeliveryPossible = $ndd->isNextDayDeliveryPossible();
```

If the next day delivery is possible, it returns the amount of time before it is actually possible to ship. Otherwise, it returns `false`.

Options
---

Alternatively, you can pass an array of options to the constructor:

| option name | description | default value |
|-------------|-------|----------|
| *timeLimit* | Max hour in 24h format before being unable to ship | `15`, e.g. 3pm |
| *countryCode* | A 2 letter code picked from [cmixin/business-day](https://packagist.org/packages/cmixin/business-day), used to compute the country holidays | `'fr'` |
| *additionalHolidays* | an array of days where the company is not able to ship | `[]` |
| *allowSaturdayDelivery* | self-explanatory boolean | `false` |


Testing
---

You can run test by cloning the repository and executing PHPUnit:

```sh
composer install
./vendor/bin/phpunit
```
