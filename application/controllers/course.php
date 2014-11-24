<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('login.php');

class Course extends CI_Controller {

	/**
	 * Check if the logged user is the admin.
	 * If so, load the page of Courses.
	 * If doesn't, is made the logout and redirected to home.
	 */	 
	public function formToRegisterNewCourse(){
		$logged_user_data = $this->session->userdata('usuario_logado');	
		$permissions_for_logged_user = $logged_user_data['user_permissions'];

		$user_has_the_permission = $this->haveCoursesPermission($permissions_for_logged_user);

		if($user_has_the_permission){
			$this->load->template('course/register_course');
		}else{
			$login = new Login();
			$login->logout("Você deve ter permissão para acessar essa página.
					      Você foi deslogado por motivos de segurança.", "danger", '/');
		}
	}

	/**
	 * Evaluates if in a given array of permissions the courses one is on it
	 * @param permissions_array - Array with the permission names
	 * @return True if there is the courses permission on this array, or false if does not.
	 */
	private function haveCoursesPermission($permissions_array){
		
		define("COURSE_PERMISSION_NAME","cursos");

		$arrarIsNotEmpty = is_array($permissions_array) && !is_null($permissions_array);
		if($arrarIsNotEmpty){
			$existsThisPermission = FALSE;
			foreach($permissions_array as $permission_name){
				if($permission_name === COURSE_PERMISSION_NAME){
					$existsThisPermission = TRUE;
				}
			}
		}else{
			$existsThisPermission = FALSE;
		}

		return $existsThisPermission;
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
	
	public function getCourseTypes(){
		
		$this->load->model('course_model');
		
		$course_types = $this->course_model->getAllCourseTypes();
		
		$course_types_form = $this->turnCourseTypesToArray($course_types);
		
		return $course_types_form;
		
	}
	
	/**
	 * Join the id's and names of course types into an array as key => value.
	 * Used to the course type form
	 * @param $course_types - The array that contains the tuples of course_type
	 * @return An array with the id's and user types names as key => value
	 */
	private function turnCourseTypesToArray($course_types){
		// Quantity of course types registered
		$quantity_of_course_types = sizeof($course_types);
	
		for($cont = 0; $cont < $quantity_of_course_types; $cont++){
			$keys[$cont] = $course_types[$cont]['id_course_type'];
			$values[$cont] = ucfirst($course_types[$cont]['name_course_type']);
		}
	
		$form_course_types = array_combine($keys, $values);
	
		return $form_course_types;
	}
	
}
