<?php
require_once('module.php');
class Module_Test extends CI_controller{

	public function __construct(){
		parent::__construct();
		
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
	}

	private function testing(){
		
		$module = new Module();
		
		$test = $module->testThisShit();
		
		$this->unit->run($test,'is_array');
	}

	private function testing2(){
		
		$module = new Module();
		
		$test = $module->testThisShit();
		
		$this->unit->run($test,'is_object');
	}

	public function index(){

		// Call all your testing functions here to display on the test report
		$this->testing();
		$this->testing2();

		$test_report = array('unit_report' => $this->unit->report());

		$this->load->view('module/module_test_report', $test_report);
	}

}