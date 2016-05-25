<?php
require_once(MODULESPATH.'auth/controllers/UserController.php');
class User_Test extends MX_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('unit_test');
		$this->unit->use_strict(TRUE);
	}

	public function index(){

		// Call your test functions here

		$test_report = array('unit_report' => $this->unit->report());

		$this->load->view('auth/user/user_test_report', $test_report);
	}

}