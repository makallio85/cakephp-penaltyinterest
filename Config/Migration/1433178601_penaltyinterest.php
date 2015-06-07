<?php
class penaltyinterest extends CakeMigration
{
    /**
     * Migration description
     *
     * @var string
     */
    public $description = 'PenaltyInterest';

    /**
     * Actions to be performed
     *
     * @var array $migration
     */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'variable_interests' => array(
                    'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
                    'percent' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'index'),
                    'first_date' => array('type' => 'date', 'null' => true, 'default' => null, 'key' => 'index'),
                    'last_date' => array('type' => 'date', 'null' => true, 'default' => null, 'key' => 'index'),
                    'country' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'percent' => array('column' => 'percent', 'unique' => 0),
                        'start_date' => array('column' => 'first_date', 'unique' => 0),
                        'end_date' => array('column' => 'last_date', 'unique' => 0),
                        'type' => array('column' => 'country', 'unique' => 0),
                    ),
                    'tableParameters' => array('charset' => 'utf8_bin', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
                ),
            ),
        ),
        'down' => array(
            'drop_table' => array(
                'variable_interests',
            ),
        ),
    );

    /**
     * Before migration callback
     *
     * @param  string $direction Direction of migration process (up or down)
     * @return bool   Should process continue
     */
    public function before($direction)
    {
        return true;
    }

    /**
     * After migration callback
     *
     * @param  string $direction Direction of migration process (up or down)
     * @return bool   Should process continue
     */
    public function after($direction)
    {
        $VariableInterest = ClassRegistry::init('VariableInterest');
        if ($direction === 'up') {
            $data = [
                ['percent' => 160000, 'first_date' => null, 'last_date' => '1995-04-30', 'country' => 'FIN'],
                ['percent' => 130000, 'first_date' => '1995-05-01', 'last_date' => '1995-12-31', 'country' => 'FIN'],
                ['percent' => 130000, 'first_date' => '1996-01-01', 'last_date' => '1996-12-31', 'country' => 'FIN'],
                ['percent' => 110000, 'first_date' => '1997-01-01', 'last_date' => '1997-12-31', 'country' => 'FIN'],
                ['percent' => 100000, 'first_date' => '1998-01-01', 'last_date' => '1998-12-31', 'country' => 'FIN'],
                ['percent' => 110000, 'first_date' => '1999-01-01', 'last_date' => '1999-12-31', 'country' => 'FIN'],
                ['percent' => 100000, 'first_date' => '2000-01-01', 'last_date' => '2000-12-31', 'country' => 'FIN'],
                ['percent' => 110000, 'first_date' => '2001-01-01', 'last_date' => '2001-12-31', 'country' => 'FIN'],
                ['percent' => 110000, 'first_date' => '2002-01-01', 'last_date' => '2002-06-30', 'country' => 'FIN'],
                ['percent' => 105000, 'first_date' => '2002-07-01', 'last_date' => '2002-12-31', 'country' => 'FIN'],
                ['percent' => 100000, 'first_date' => '2003-01-01', 'last_date' => '2003-06-30', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2003-07-01', 'last_date' => '2003-12-31', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2004-01-01', 'last_date' => '2004-06-30', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2004-07-01', 'last_date' => '2004-12-31', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2005-01-01', 'last_date' => '2005-06-30', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2005-07-01', 'last_date' => '2005-12-31', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2006-01-01', 'last_date' => '2006-06-30', 'country' => 'FIN'],
                ['percent' => 100000, 'first_date' => '2006-07-01', 'last_date' => '2006-12-31', 'country' => 'FIN'],
                ['percent' => 110000, 'first_date' => '2007-01-01', 'last_date' => '2007-06-30', 'country' => 'FIN'],
                ['percent' => 115000, 'first_date' => '2007-07-01', 'last_date' => '2007-12-31', 'country' => 'FIN'],
                ['percent' => 115000, 'first_date' => '2008-01-01', 'last_date' => '2008-06-30', 'country' => 'FIN'],
                ['percent' => 115000, 'first_date' => '2008-07-01', 'last_date' => '2008-12-31', 'country' => 'FIN'],
                ['percent' => 95000, 'first_date' => '2009-01-01', 'last_date' => '2009-06-30', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2009-07-01', 'last_date' => '2009-12-31', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2010-01-01', 'last_date' => '2010-06-30', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2010-07-01', 'last_date' => '2010-12-31', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2011-01-01', 'last_date' => '2011-06-30', 'country' => 'FIN'],
                ['percent' => 85000, 'first_date' => '2011-07-01', 'last_date' => '2011-12-31', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2012-01-01', 'last_date' => '2012-06-30', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2012-07-01', 'last_date' => '2012-12-31', 'country' => 'FIN'],
                ['percent' => 80000, 'first_date' => '2013-01-01', 'last_date' => '2013-06-30', 'country' => 'FIN'],
                ['percent' => 75000, 'first_date' => '2013-07-01', 'last_date' => '2013-12-31', 'country' => 'FIN'],
                ['percent' => 75000, 'first_date' => '2014-01-01', 'last_date' => '2014-06-30', 'country' => 'FIN'],
                ['percent' => 75000, 'first_date' => '2014-07-01', 'last_date' => '2014-12-31', 'country' => 'FIN'],
                ['percent' => 75000, 'first_date' => '2015-01-01', 'last_date' => '2015-06-30', 'country' => 'FIN'],
            ];

            $VariableInterest->create();
            if ($VariableInterest->saveAll($data)) {
                $this->callback->out('variable_interests table has been initialized');
            }
        } elseif ($direction === 'down') {
            // do struff
        }

        return true;
    }
}
