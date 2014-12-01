<?php

class Test_Report extends CI_Controller{

	public function index(){
		
		// TENTAR JUNTAR TODAS AS VIEW DE TEST REPORT EM UM SO ARQUIVO

		$this->load->view('usuario/user_test_report');
		// $this->load->view('departamentos/departments_test_report');
		// $this->load->view('funcionarios/employees_test_report');
		// $this->load->view('funcoes/functions_test_report');
		// $this->load->view('login/login_test_report');
		// $this->load->view('module/module_test_report');
		// $this->load->view('permission/permission_test_report');
		// $this->load->view('setores/setores_test_report');
	}

}