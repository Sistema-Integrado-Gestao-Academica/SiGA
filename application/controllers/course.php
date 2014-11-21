<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends CI_Controller {

	/**
	 * Check if the logged user is the admin.
	 * If so, load the page of Courses.
	 * If doesn't, is made the logout and redirected to home.
	 */	 
	public function formToRegisterNewCourse(){
		$logged_user_data = $this->session->userdata('usuario_logado');	
		$logged_user = $logged_user_data['user_type'];
	
		$adminIsLogged = $this->isAdmin($logged_user);

		if($adminIsLogged){
			$this->load->template('course/register_course');
		}else{
			$this->session->unset_userdata("usuario_logado", $usuario);
			$this->session->set_flashdata("danger", "Você deve ter permissão do administrador.");
			redirect('/');
		}
	}

	/**
	 * Check if an user has admin permissions
	 * @param $user_type_ids - An array with the user types of an user
	 * @return True if in the array passed has an id of admin
	 */
	private function isAdmin($user_type_ids){

		$this->load->model('usuarios_model');
		foreach($user_type_ids as $id_user_type){
			$isAdminId = $this->usuarios_model->checkIfIdIsOfAdmin($id_user_type);
			if($isAdminId){
				break; // Try not to do this
			}
		}

		return $isAdminId;
	}

}
