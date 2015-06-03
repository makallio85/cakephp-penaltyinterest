# cakephp-penaltyinterest
CakePHP2 plugin to calculate penalty interests for desired time periods. Calculation also supports payments to base value.

Plugin currently supports variable penalty interest calculating based on Finland rates. Variable percent should be given as penalty interest per year.

With fixed percent there are no country limitations.

It is easy to add new countries, by adding db-migration for it, and adding it to supported countries.

Some notes:
- All money values are hundreths of original value, so 100 converts to 10000 and 1 converts to 100.
- All penalty interest values are ten thousands of original value, so 10 converts to 100000

Steps to usage:
- Migrate database with CakeDC migration plugin
- Load plugin in app/Config/bootstrap.php with command: CakePlugin::load('PenaltyInterest', ['bootstrap' => true]);
- Use code below, to calculate interests

```php
App::import('Model', 'PenaltyInterest.InterestPeriod');

$data = [
    'interestPercentType' => 'variable', // Variable or fixed
    'hundrethBaseValue' => 10000, // Base capital hundreth value
    'tenThousandthPercent' => 100000, // Yearly penalty interest ten thousandth percent 
    'firstDate' => '2014-01-01', // Date to start calculating penalty interests (due date of capital)
    'lastDate' => '2014-12-31', // Last date to end to calculate penalty interests (typically current date)
    'valuePayments' => [ // Array of possible payments
        [
            'hundrethValue' => 100, // Payment hundreth value
            'date' => '2014-05-15', // Payment date
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

$Intr = new InterestPeriod();
$Intr->interestDate = $data['lastDate'];
$results = $PR->preparePeriods($data);
```