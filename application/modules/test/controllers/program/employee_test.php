<?php
require_once(MODULESPATH.'program/controllers/staffs.php');
class Employee_Test extends MX_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
	}

	public function index(){

		// Call your test functions here

		$test_report = array('unit_report' => $this->unit->report());

		$this->load->view('program/staffs/employee_test_report', $test_report);
	}

}