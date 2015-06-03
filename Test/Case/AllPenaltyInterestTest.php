<?php

class AllPenaltyInterestTestTest extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $Suite = new CakeTestSuite('All Plugin tests');
        $path = dirname(__FILE__);
        $Suite->addTestDirectory($path.DS.'Model');

        return $Suite;
    }
}
