<?php

/**
 * PHP File
 */

App::uses('CakeTime', 'Utility');
App::import('Model', 'VariableInterest');

/**
 * InterePeriod model
 */
class InterestPeriod extends PenaltyInterestAppModel
{
    /**
     * Table to use
     * @var string
     */
    public $useTable = false;

    /**
     * Date to where interests are calculated
     * @var string
     */
    public $interestDate = null;

    /**
     * Type to use in interest calculation
     * @var string
     */
    public $interestCalculationType = 'english';

    /**
     * Country to use, when interests are calculated with variable rate
     * @var string
     */
    public $variableInterestCountry = null;

    /**
     * List of supported countries when requesting calculation with variable percent
     * @var array
     */
    public $supportedCountries = ['FIN'];

    /**
     * InterestPeriod model constructor
     *
     * @param  bool|int $id    Model id
     * @param  string          $table Name of database table to use.
     * @param  string          $ds    DataSource connection name.
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        $this->interestDate = date('Y-m-d');
        parent::__construct($id, $table, $ds);
    }

    /**
     * This method checks that are used dates are in valid format
     *
     * @param  array   $data Data that contains dates to chek
     * @return bool
     */
    public function datesAreValid($data)
    {
        if (!isset($data['firstDate']) || !Validation::date($data['firstDate'], 'ymd')) {
            $this->setStatus('INVALID_FIRST_DATE');

            return false;
        }
        if (!Validation::date($this->interestDate, 'ymd')) {
            $this->setStatus('INVALID_INTEREST_DATE');

            return false;
        }

        if (isset($data['valuePayments'])) {
            foreach ($data['valuePayments'] as $listItem) {
                if (!Validation::date($listItem['date'], 'ymd')) {
                    $this->setStatus('INVALID_PAYMENT_DATE');

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Method creates periods based on payments.
     *
     * @param  array $data Data to use
     * @return array
     */
    public function preparePaymentPeriods($data)
    {
        $data['preparedPaymentPeriods'] = [];
        $valueBackup = (int) $data['hundrethBaseValue'];
        foreach ($data['valuePayments'] as $key => $listItem) {
            if ($key == 0) {
                $data['preparedPaymentPeriods'][] = [
                    'firstDate' => $data['firstDate'],
                    'lastDate' => date('Y-m-d', strtotime($listItem['date'].' - 1 days')),
                    'hundrethBaseValue' => (int) $valueBackup,
                ];
            } elseif ($key + 1 < count($data['valuePayments'])) {
                $data['preparedPaymentPeriods'][] = [
                    'firstDate' => $data['valuePayments'][$key - 1]['date'],
                    'lastDate' => date('Y-m-d', strtotime($listItem['date'].' - 1 days')),
                    'hundrethBaseValue' => (int) $valueBackup,
                ];
            } elseif ($key + 1 == count($data['valuePayments'])) {
                $data['preparedPaymentPeriods'][] = [
                    'firstDate' => $data['valuePayments'][$key - 1]['date'],
                    'lastDate' => date('Y-m-d', strtotime($listItem['date'].' - 1 days')),
                    'hundrethBaseValue' => (int) $valueBackup,
                ];
                $valueBackup -= $listItem['hundrethValue'];
                $data['preparedPaymentPeriods'][] = [
                    'firstDate' => $listItem['date'],
                    'lastDate' => $this->interestDate,
                    'hundrethBaseValue' => (int) $valueBackup,
                ];
                continue;
            }
            $valueBackup -= $listItem['hundrethValue'];
        }

        return $data;
    }

    /**
     * Method creates periods from payment periods based on variable interest rate
     *
     * @param  array $data Data to use
     * @return array
     */
    public function resolveSplitPoints($data)
    {
        $data['splitPoints'] = [];
        $datesAdded[] = [];
        foreach ($data['preparedPaymentPeriods'] as $key => $listItem) {
            if (!in_array($listItem['firstDate'], $datesAdded)) {
                $data['splitPoints'][] = [
                    'date' => $listItem['firstDate'],
                    'hundrethBaseValue' => (int) $listItem['hundrethBaseValue'],
                ];
                $datesAdded[] = $listItem['firstDate'];
            }
            $VariableInterest = new VariableInterest();
            if ($data['interestPercentType'] == 'variable') {
                $periodsInPaymentRange = $VariableInterest->find(
                    'all',
                    [
                        'conditions' => [
                            'VariableInterest.first_date >=' => $listItem['firstDate'],
                            'VariableInterest.first_date <' => $this->interestDate,
                            'VariableInterest.country' => $this->variableInterestCountry,
                        ],
                        'order' => 'VariableInterest.first_date ASC'
                    ]
                );
                foreach ($periodsInPaymentRange as $subItem) {
                    if ($data['preparedPaymentPeriods'][$key + 1]['firstDate'] > $subItem['VariableInterest']['first_date']) {
                        if (!in_array($subItem['VariableInterest']['first_date'], $datesAdded)) {
                            $data['splitPoints'][] = [
                                'date' => $subItem['VariableInterest']['first_date'],
                                'hundrethBaseValue' => (int) $listItem['hundrethBaseValue'],
                            ];
                            $datesAdded[] = $subItem['VariableInterest']['first_date'];
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Method inserts period last dates based on next periods start date
     *
     * @param  array $data Data to use
     * @return array
     */
    public function resolveLastDates($data)
    {
        $data['preparedPeriods'] = [];
        foreach ($data['splitPoints'] as $key => $listItem) {
            if (count($data['splitPoints']) > $key + 1) {
                $data['preparedPeriods'][] = [
                    'firstDate' => $listItem['date'],
                    'lastDate' => date('Y-m-d', strtotime($data['splitPoints'][$key + 1]['date'].' - 1 days')),
                    'hundrethBaseValue' => (int) $listItem['hundrethBaseValue'],
                ];
            } else {
                $data['preparedPeriods'][] = [
                    'firstDate' => $listItem['date'],
                    'lastDate' => $this->interestDate,
                    'hundrethBaseValue' => (int) $listItem['hundrethBaseValue'],
                ];
            }
        }

        return $data;
    }

    /**
     * Method performs actual interest calculation
     *
     * @param  array $data Data to use
     * @return array
     */
    public function calculateInterests($data)
    {
        foreach ($data['preparedPeriods'] as &$listItem) {
            $date1 = new DateTime($listItem['firstDate']);
            $date2 = new DateTime($listItem['lastDate']);
            $diff = $date2->diff($date1)->format("%a");
            switch ($this->interestCalculationType) {
                case 'english':
                    $listItem['tenThousandthPercent'] = (int) $data['tenThousandthPercent'];
                    $listItem['hundrethInterestValue'] = (int) round($listItem['hundrethBaseValue'] * 
                        ($data['tenThousandthPercent'] / 1000000) / 365 * $diff);
                    $listItem['interestDays'] = (int) $diff;
                    break;
                case 'french':
                case 'german':
                    $listItem['tenThousandthPercent'] = (int) $data['tenThousandthPercent'];
                    $listItem['hundrethInterestValue'] = (int) round($listItem['hundrethBaseValue'] * 
                        ($data['tenThousandthPercent'] / 1000000) / 360 * $diff);
                    $listItem['interestDays'] = (int) $diff;
                    break;
            }
        }

        return $data;
    }

    /**
     * Method validates given data and sets error codes
     *
     * @param  array   $data Data to use
     * @return bool
     */
    public function checkRequiredData($data)
    {
        /* These indexes as required at least */
        if (
            !isset($data['interestPercentType']) ||
            !isset($data['hundrethBaseValue']) ||
            !isset($data['firstDate']) ||
            !isset($data['lastDate'])
        ) {
            $this->setStatus('BAD_REQUEST');

            return false;
        }

        /* type must be valid */
        if (!in_array($data['interestPercentType'], ['variable', 'fixed'])) {
            $this->setStatus('INVALID_INTEREST_PERCENT_TYPE');

            return false;
        }

        /* If interest type is fixed, percent must be present */
        if ($data['interestPercentType'] == 'fixed') {
            if (!isset($data['tenThousandthPercent']) || !is_numeric($data['tenThousandthPercent'])) {
                $this->setStatus('INVALID_INTEREST_PERCENT');

                return false;
            }
        }

        /* If interest type is variable, country must be present */
        if ($data['interestPercentType'] == 'variable') {
            if (!isset($data['country']) || !in_array(strtoupper($data['country']), $this->supportedCountries)) {
                $this->setStatus('INVALID_COUNTRY');

                return false;
            }
        }

        $dateCheck = $this->datesAreValid($data);
        if (!$dateCheck) {
            return false;
        }

        return true;
    }

    /**
     * Main method. Callable outside class
     *
     * @param  array         $data Data to use in calculations
     * @return bool|array
     */
    public function preparePeriods($data)
    {
        $dataCheck = $this->checkRequiredData($data);
        if (!$dataCheck) {
            return false;
        }
        $data = $this->preparePaymentPeriods($data);
        $data = $this->resolveSplitPoints($data);
        $data = $this->resolveLastDates($data);
        $data = $this->calculateInterests($data);

        $this->setStatus('SUCCESS');

        return $data['preparedPeriods'];
    }
}
