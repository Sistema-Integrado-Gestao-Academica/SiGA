<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends CI_Controller {

	public function formToRegisterNewCourse(){
		
		$logged_user = $this->session->userdata('usuario_logado');

		$this->load->template('course/register_course');
	}

}
