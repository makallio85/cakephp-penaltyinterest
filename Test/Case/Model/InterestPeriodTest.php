<?php

/**
 * PHP file
 */

App::import('Model', 'PenaltyInterest.InterestPeriod');

/**
 * InterestPeriod model test class
 *
 * @author Kallio, Marko
 *
 */
class InterestPeriodTestCase extends CakeTestCase
{
    public function testDatesAreValid()
    {
        $dataSets = [
            [
                'assert' => false,
                'firstDate' => '2014-01-01ss xx',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'date' => '2014-05-15',
                    ],
                ],
            ],
            [
                'assert' => false,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'date' => '2014-05-152',
                    ],
                ],
            ],
            [
                'assert' => false,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-13-22',
                'valuePayments' => [
                    [
                        'date' => '2014-05-15',
                    ],
                ],
            ],
            [
                'assert' => true,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'date' => '2014-05-15',
                    ],
                ],
            ],
        ];
        foreach ($dataSets as $listItem) {
            $InterestPeriod = new InterestPeriod();
            $InterestPeriod->interestDate = $listItem['lastDate'];
            $result = $InterestPeriod->datesAreValid($listItem);
            if ($listItem['assert']) {
                $this->assertTrue($result);
            } else {
                $this->assertFalse($result);
            }
        }
    }

    public function testPreparePaymentPeriods()
    {
        $dataSets = [
            [
                'hundrethBaseValue' => (int) 10000,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-05-15',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-07-22',
                    ],
                ],
                'assertedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                ],
            ],
            [
                'hundrethBaseValue' => 10000,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-05-15',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-07-22',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-09-30',
                    ],
                ],
                'assertedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700,
                    ],
                ]
            ],
        ];
        foreach ($dataSets as $listItem) {
            $InterestPeriod = new InterestPeriod();
            $InterestPeriod->interestDate = $listItem['lastDate'];
            $result = $InterestPeriod->preparePaymentPeriods($listItem);
            $this->assertTrue($listItem['assertedPaymentPeriods'] == $result['preparedPaymentPeriods']);
        }
    }

    public function testResolveSplitPoints()
    {
        $dataSets = [
            [
                'interestPercentType' => 'fixed',
                'country' => 'FIN',
                'lastDate' => '2014-12-31',
                'preparedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                ],
                'assertedSplitPoints' => [
                    [
                        'date' => '2014-01-01',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'date' => '2014-05-15',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'date' => '2014-07-22',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                ],
            ],
            [
                'interestPercentType' => 'fixed',
                'country' => 'FIN',
                'lastDate' => '2014-12-31',
                'preparedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => 9800,
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => 9700,
                    ],
                ],
                'assertedSplitPoints' => [
                    [
                        'date' => '2014-01-01',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'date' => '2014-05-15',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'date' => '2014-07-22',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                    [
                        'date' => '2014-09-30',
                        'hundrethBaseValue' => (int) 9700,
                    ],
                ],
            ],
        ];

        foreach ($dataSets as $listItem) {
            $InterestPeriod = new InterestPeriod();
            $InterestPeriod->interestDate = $listItem['lastDate'];
            $InterestPeriod->variableInterestCountry = $listItem['country'];
            $result = $InterestPeriod->resolveSplitPoints($listItem);
            $this->assertTrue($listItem['assertedSplitPoints'] == $result['splitPoints']);
        }
    }

    public function testResolveLastDates()
    {
        $dataSets = [
            [
                'lastDate' => '2014-12-31',
                'splitPoints' => [
                    [
                        'date' => '2014-01-01',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'date' => '2014-05-15',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'date' => '2014-07-01',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'date' => '2014-07-22',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                ],
                'assertedPreparedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800
                    ],
                ],
            ],
            [
                'lastDate' => '2014-12-31',
                'splitPoints' => [
                    [
                        'date' => '2014-01-01',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'date' => '2014-05-15',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'date' => '2014-07-01',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'date' => '2014-07-22',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                    [
                        'date' => '2014-09-30',
                        'hundrethBaseValue' => (int) 9700,
                    ],
                ],
                'assertedPreparedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700
                    ],
                ],
            ],
        ];

        foreach ($dataSets as $listItem) {
            $InterestPeriod = new InterestPeriod();
            $InterestPeriod->interestDate = $listItem['lastDate'];
            $result = $InterestPeriod->resolveLastDates($listItem);
            $this->assertTrue($listItem['assertedPreparedPeriods'] == $result['preparedPeriods']);
        }
    }

    public function testCalculateInterests()
    {
        $dataSets = [
            [
                'tenThousandthPercent' => 100000,
                'preparedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800
                    ],
                ],
                'assertedPreparedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 364,
                        'interestDays' => (int) 133,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 125,
                        'interestDays' => (int) 46
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 54,
                        'interestDays' => (int) 20
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 435,
                        'interestDays' => (int) 162
                    ],
                ],
            ],
            [
                'tenThousandthPercent' => 100000,
                'preparedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700
                    ],
                ],
                'assertedPreparedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 364,
                        'interestDays' => (int) 133,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 125,
                        'interestDays' => (int) 46
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 54,
                        'interestDays' => (int) 20
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 185,
                        'interestDays' => (int) 69
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 244,
                        'interestDays' => (int) 92
                    ],
                ],
            ],
        ];

        foreach ($dataSets as $listItem) {
            $InterestPeriod = new InterestPeriod();
            $result = $InterestPeriod->calculateInterests($listItem);
            $this->assertTrue($listItem['assertedPreparedPeriods'] == $result['preparedPeriods']);
        }
    }

    public function testPreparePeriods()
    {
        $dataSets = [
            [
                'assert' => false,
                'country' => 'FIN',
                'interestCalculationType' => 'english',
                'interestPercentType' => 'variable',
                'hundrethBaseValue' => (int) 10000,
                'tenThousandthPercent' => (int) 100000,
                'firstDate' => '2014-01-01s',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-05-15',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-07-22',
                    ],
                ],
                'preparedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                ],
                'assertedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 364,
                        'interestDays' => (int) 133,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 125,
                        'interestDays' => (int) 46
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 54,
                        'interestDays' => (int) 20
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 435,
                        'interestDays' => (int) 162
                    ],
                ],
            ],
            [
                'assert' => true,
                'country' => 'FIN',
                'interestPercentType' => 'variable',
                'interestCalculationType' => 'english',
                'hundrethBaseValue' => (int) 10000,
                'tenThousandthPercent' => (int) 100000,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-05-15',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-07-22',
                    ],
                ],
                'preparedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                ],
                'assertedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 364,
                        'interestDays' => (int) 133,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 125,
                        'interestDays' => (int) 46
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 54,
                        'interestDays' => (int) 20
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9800,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 435,
                        'interestDays' => (int) 162
                    ],
                ],
            ],
            [
                'assert' => true,
                'country' => 'FIN',
                'interestPercentType' => 'variable',
                'interestCalculationType' => 'english',
                'hundrethBaseValue' => (int) 10000,
                'tenThousandthPercent' => (int) 100000,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-05-15',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-07-22',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-09-30',
                    ],
                ],
                'preparedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700,
                    ],
                ],
                'assertedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 364,
                        'interestDays' => (int) 133,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 125,
                        'interestDays' => (int) 46
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 54,
                        'interestDays' => (int) 20
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 185,
                        'interestDays' => (int) 69
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 244,
                        'interestDays' => (int) 92
                    ],
                ],
            ],
            [
                'assert' => true,
                'country' => 'FIN',
                'interestPercentType' => 'variable',
                'interestCalculationType' => 'english',
                'hundrethBaseValue' => (int) 10000,
                'tenThousandthPercent' => (int) 100000,
                'firstDate' => '2014-01-01',
                'lastDate' => '2014-12-31',
                'valuePayments' => [
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-05-15',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-07-22',
                    ],
                    [
                        'hundrethValue' => (int) 100,
                        'date' => '2014-09-30',
                    ],
                ],
                'preparedPaymentPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800,
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700,
                    ],
                ],
                'assertedPeriods' => [
                    [
                        'firstDate' => '2014-01-01',
                        'lastDate' => '2014-05-14',
                        'hundrethBaseValue' => (int) 10000,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 364,
                        'interestDays' => (int) 133,
                    ],
                    [
                        'firstDate' => '2014-05-15',
                        'lastDate' => '2014-06-30',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 125,
                        'interestDays' => (int) 46
                    ],
                    [
                        'firstDate' => '2014-07-01',
                        'lastDate' => '2014-07-21',
                        'hundrethBaseValue' => (int) 9900,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 54,
                        'interestDays' => (int) 20
                    ],
                    [
                        'firstDate' => '2014-07-22',
                        'lastDate' => '2014-09-29',
                        'hundrethBaseValue' => (int) 9800,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 185,
                        'interestDays' => (int) 69
                    ],
                    [
                        'firstDate' => '2014-09-30',
                        'lastDate' => '2014-12-31',
                        'hundrethBaseValue' => (int) 9700,
                        'tenThousandthPercent' => (int) 100000,
                        'hundrethInterestValue' => (int) 244,
                        'interestDays' => (int) 92
                    ],
                ],
            ],
        ];

        foreach ($dataSets as $listItem) {
            $InterestPeriod = new InterestPeriod();
            $InterestPeriod->interestCalculationType = $listItem['interestCalculationType'];
            $InterestPeriod->interestDate = $listItem['lastDate'];
            $InterestPeriod->variableInterestCountry = $listItem['country'];
            $result = $InterestPeriod->preparePeriods($listItem);
            if ($listItem['assert']) {
                $this->assertTrue($listItem['assertedPeriods'] == $result);
            } else {
                $this->assertFalse($result);
            }
        }
    }
}
