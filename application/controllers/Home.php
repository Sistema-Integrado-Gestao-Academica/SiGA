<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller{
	
	public function index(){

		$this->db->select('id, password');
        $users = $this->db->get('users')->result_array();        
 
 		$this->load->module("program/program");
		
		$data = $this->program->getInformationAboutPrograms();
		$this->load->template('home/home', $data);
	}
}