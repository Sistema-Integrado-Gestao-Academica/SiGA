<?php
require_once(MODULESPATH.'auth/controllers/Module.php');
class Module_Test extends MX_controller{

	public function __construct(){
		parent::__construct();
		
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
	}

	public function index(){

		// Call all your testing functions here to display on the test report

		$test_report = array('unit_report' => $this->unit->report());

		$this->load->view('auth/module/module_test_report', $test_report);
	}

}