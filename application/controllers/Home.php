<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller{
	
	public function index(){

		$this->load->module("program/program");
		
		$data = $this->program->getInformationAboutPrograms();

		$this->load->template('home/home', $data);
	}
}