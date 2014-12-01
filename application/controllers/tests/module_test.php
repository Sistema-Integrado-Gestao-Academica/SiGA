<?php
require_once(APPPATH.'/controllers/module.php');
class Module_Test extends CI_controller{

	public function __construct(){
		parent::__construct();
		
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
	}

	public function index(){

		// Call all your testing functions here to display on the test report

		$test_report = array('unit_report' => $this->unit->report());

		$this->load->view('module/module_test_report', $test_report);
	}

}