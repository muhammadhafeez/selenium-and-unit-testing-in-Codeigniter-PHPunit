<?php

/*
 *
 * @property-read resource	$db		Reference to database
 */

abstract class codeigniter_init extends PHPUnit_Extensions_Database_TestCase {

    /**
     * Reference to CodeIgniter
     * 
     * @var resource
     */
    protected $CI;

    /**
     * Only instantiate pdo once for test clean-up/fixture load
     * 
     * @internal
     * @var resource
     */
    static private $pdo = null;

    /**
     * Only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
     * 
     * @internal
     * @var resource
     */
    private $conn = null;

    /**
     * Call parent constructor and initialize reference to CodeIgniter
     * 
     * @internal
     */
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->CI = & get_instance();
        $this->CI->load->dbutil(); // load CI db utitility class
        // i have create a seprate database for testing 
        if (!$this->CI->dbutil->database_exists('phpunit_test')) {
            echo "\n Creating database \n";
            $this->CI->dbforge->create_database('phpunit_test');
            echo "Connecting to database \n";
            //init configuration set with name test in config/database.php
            $this->CI->db_test = $this->CI->load->database('test', TRUE);
            echo "Creating signup table \n";
            // you can also run migration here visit the link for details
            // http://ellislab.com/codeigniter/user-guide/libraries/migration.html
            $this->CI->db_test->query('
                           CREATE TABLE IF NOT EXISTS `tbl_users` (
                          `uId` int(255) NOT NULL AUTO_INCREMENT,
                          `uName` varchar(255) NOT NULL,
                          `uEmail` varchar(50) NOT NULL,
                          `uPassword` varchar(50) NOT NULL,
                          `uDate` date DEFAULT NULL,
                          PRIMARY KEY (`uId`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;');
        } else {
            $this->CI->db_test = $this->CI->load->database('test', TRUE);
        }
    }

    /**
     * Initialize database connection (same one used by CodeIgniter)
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    final public function getConnection() {
        if ($this->conn === null) {
            if (self::$pdo == null) {

                $dsn = $this->CI->db_test->dbdriver . ':dbname=' . $this->CI->db_test->database . ';host=' . $this->CI->db_test->hostname;
                self::$pdo = new PDO($dsn, $this->CI->db_test->username, $this->CI->db_test->password);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $this->CI->db_test->database);
        }

        return $this->conn;
    }

    /**
     * @internal
     */
    public function __get($name) {
        if ($name == 'db') {
            return $this->getConnection();
        }
    }

    /**
     * Returns the DataSet
     * 
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet(Array $tableNames = NULL) {
        return $this->getConnection()->createDataSet($tableNames);
    }

}