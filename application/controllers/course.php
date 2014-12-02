<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('login.php');
require_once(APPPATH."/exception/CourseNameException.php");
class Course extends CI_Controller {

	public function index(){

		$logged_user_data = $this->session->userdata('usuario_logado');	
		$permissions_for_logged_user = $logged_user_data['user_permissions'];

		$user_has_the_permission = $this->haveCoursesPermission($permissions_for_logged_user);

		if($user_has_the_permission){
			$this->load->template('course/course_index');
		}else{
			$login = new Login();
			$login->logout("Você deve ter permissão para acessar essa página.
					      Você foi deslogado por motivos de segurança.", "danger", '/');
		}
	}

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
	 * Function to load the page of a course that will be updated
	 * @param int $id
	 */
	public function formToEditCourse($id){
		autoriza();
		$this->load->model('course_model');
		$course_searched = $this->course_model->getCourseById($id);
		$data = array('course' => $course_searched);
		$this->load->template('course/update_course', $data);
	}
	
	/**
	 * Register a new course
	 */
	public function newCourse(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataIsOk = $this->form_validation->run();

		if($courseDataIsOk){
			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');

			// Course to be saved on database. Put the columns names on the keys
			$courseToRegister = array(
				'course_name' => $courseName,
				'course_type_id' => $courseType,
			);

			$this->load->model("course_model");
			$insertionWasMade = $this->course_model->saveCourse($courseToRegister);

			if($insertionWasMade){
				$this->session->set_flashdata("success", "Curso \"{$courseName}\" cadastrado com sucesso");
			}else{
				$this->session->set_flashdata("danger", "Curso \"{$courseName}\" já existe.");
			}

		}else{
			$this->session->set_flashdata("danger", "Dados na forma incorreta.");
		}
		
		redirect('/course/index');
	}

	/**
	 * Function to update a registered course data
	 */
	public function updateCourse(){
		autoriza();
		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataIsOk = $this->form_validation->run();
		
		if($courseDataIsOk){
			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');
			$idCourse = $this->input->post('id_course');
			
			// Course to be saved on database. Put the columns names on the keys
			$courseToUpdate = array(
					'course_name' => $courseName,
					'course_type_id' => $courseType,
			);
		
			try{
				$this->load->model("course_model");
				$this->course_model->updateCourse($idCourse, $courseToUpdate);
				$this->session->set_flashdata("success", "Curso \"{$courseName}\" alterado com sucesso");

			}catch(CourseNameException $e){

				$this->session->set_flashdata("danger", $e->getMessage());
			}
		
		
		}else{
			$this->session->set_flashdata("danger", "Dados na forma incorreta.");
		}
		
		redirect('/course/index');
		
	}

	/**
	 * Function to delete a registered course
	 */
	public function deleteCourse(){
		$course_id = $this->input->post('id_course');
		$courseWasDeleted = $this->deleteCourseFromDb($course_id);

		if($courseWasDeleted){
			$this->session->set_flashdata("success", "Curso excluído com sucesso.");
		}else{
			$this->session->set_flashdata("danger", "Não foi possível excluir este curso.");
		}

		redirect('/course/index');
	}

	/**
	 * Delete a registered course on DB
	 * @param $course_id - The id from the course to be deleted
	 * @return true if the exclusion was made right and false if does not
	 */
	public function deleteCourseFromDb($course_id){
		
		$this->load->model('course_model');

		$deletedCourse = $this->course_model->deleteCourseById($course_id);
		
		return $deletedCourse;
	}
	
	/**
	 * Function to get the list of all registered courses
	 * @return array $registeredCourses
	 */
	public function listAllCourses(){
		$this->load->model('course_model');
		$registeredCourses = $this->course_model->getAllCourses();

		return $registeredCourses;
	}

	/**
	 * Get all the course types from database into an array.
	 * @return An array with all course types on database as id => course_type_name
	 */
	public function getCourseTypes(){
		
		$this->load->model('course_model');
		
		$course_types = $this->course_model->getAllCourseTypes();
		
		$course_types_form = $this->turnCourseTypesToArray($course_types);
		
		return $course_types_form;
		
	}
	
	function alpha_dash_space($str){
	    return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
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
	
	/**
	 * Join the id's and names of course types into an array as key => value.
	 * Used to the course type form
	 * @param $course_types - The array that contains the tuples of course_type
	 * @return An array with the id's and course types names as id => course_type_name
	 */
	private function turnCourseTypesToArray($course_types){
		// Quantity of course types registered
		$quantity_of_course_types = sizeof($course_types);
	
		for($cont = 0; $cont < $quantity_of_course_types; $cont++){
			$keys[$cont] = $course_types[$cont]['id_course_type'];
			$values[$cont] = ucfirst($course_types[$cont]['course_type_name']);
		}
	
		$form_course_types = array_combine($keys, $values);
	
		return $form_course_types;
	}
	
}
