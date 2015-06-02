# cakephp-penaltyinterest
CakePHP2 plugin to calculate penalty interests for desired time periods. Calculation also supports payments to base value.

Plugin currently supports variable penalty interest calculating based on Finland rates.
It is easy to add new countries, by adding db-migration for it, and adding it to supported countries.

Some notes:
- All money values are hundreths of original value, so 100 converts to 10000 and 1 converts to 100.
- All interest values are ten thousands of original value, so 10 converts to 100000

Steps to usage:
- Migrate database
- Use code below

```php
$PR = new PenaltyInterest();

$data = [
    'country' => 'FIN',
    'interestPercentType' => 'variable',
    'interestCalculationType' => 'english',
    'hundrethBaseValue' => 10000,
    'tenThousandthPercent' => 100000,
    'firstDate' => '2014-01-01',
    'lastDate' => '2014-12-31',
    'valuePayments' => [
        [
            'hundrethValue' => 100,
            'date' => '2014-05-15',
        ],
        [
            'hundrethValue' => 100,
            'date' => '2014-07-22',
        ],
        [
            'hundrethValue' => 100,
            'date' => '2014-09-30',
        ],
    ],
];

$PR->interestCalculationType = $data['interestCalculationType']; // Variable of fixed
$PR->interestDate = $data['lastDate']; // Date to calculate interests to
$PR->variableInterestCountry = $data['country']; // Country to use

$results = $PR->preparePeriods($data);
```