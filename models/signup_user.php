<?php

// prevent direct loading of this file
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    die("This file cannot be loaded directly.");
}

class Signup_user extends codeigniter_init {

    function setUp() {
        $this->CI->load->model('user_model');
    }

    public function test_signup() {
        $userModel = new candidates_model();
        $data = array(
            "uName" => "New User",
            "uEmail=" => "testemail@domain.com",
        );
        $userModel->user_signup($data); // calling model function
        //first create a fixture of a table, visit the link below for more details
        // http://phpunit.de/manual/3.7/en/database.html
        $expectedTable = $this->createFlatXmlDataSet(APPPATH . 'tests/models/data/dataset/save.xml')->getTable("tbl_users");
        // get record from table to match
        $queryTable = $this->db->createQueryTable('tbl_users', 'SELECT uName,uEmail FROM tbl_users');
        //now match both dataset
        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    function tearDown() {
        // after successfull run truncate table 
        $this->CI->db_test->query('Truncate tbl_users;');
    }

}
