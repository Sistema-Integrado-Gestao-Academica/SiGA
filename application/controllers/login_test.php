<?php

class Login_Test extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('unit_test');
	}

	public function index(){

		// Tests here
		// $this->unit->run(4,4,"4 equals to 4");
		// $this->unit->run(5,5,"5 equals to 5");

		$this->load->view('login/login_test_report');
	}

}