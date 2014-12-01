<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('login.php');

class Course extends CI_Controller {

	public function index(){
		$this->load->template('course/course_index');
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

	public function formToEditCourse(){
		$this->listAllCourses();
	}

	public function deleteCourse(){
		echo "<h2>Fazer pagina do delete course</h2>";
		$course_id = $this->input->post('id_course');
		$this->load->model('course_model');
		
		$deletedCourse = $this->course_model->deleteCourseById($course_id);
		redirect('/course/index');
		return $deletedCourse;
	}

	public function listAllCourses(){
		$this->load->model('course_model');
		$registeredCourses = $this->course_model->getAllCourses();

		return $registeredCourses;
		echo "<h2> Fazer pagina do edit course</h2>";
		//var_dump($registeredCourses);
	}

	function alpha_dash_space($str){
	    return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
	}

	/**
	 * Check if the course is finantiated by the checkbox value 
	 * @param $valueToCheck - Checkbox value (Expected TRUE OR FALSE)
	 * @return 1 if is finantiated or 0 if does not
	 */
	private function checkIfIsFinantiated($valueToCheck){
		
		$isFinantiated = 0;

		if($valueToCheck){
			$isFinantiated = 1;
		}else{
			$isFinantiated = 0;
		}

		return $isFinantiated;
	}

	/**
	 * Register a new course
	 */
	public function newCourse(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		$this->form_validation->set_rules("isFinantiated", "Finantiated");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataIsOk = $this->form_validation->run();

		if($courseDataIsOk){
			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');
			$courseIsFinantiated = $this->input->post('isFinantiated');
			$courseIsFinantiated = $this->checkIfIsFinantiated($courseIsFinantiated);

			// Course to be saved on database. Put the columns names on the keys
			$courseToRegister = array(
				'course_name' => $courseName,
				'course_type_id' => $courseType,
				//'is_finantiated' => $courseIsFinantiated
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
		
		redirect('/course/formToRegisterNewCourse');
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
	 * Get all the course types from database into an array.
	 * @return An array with all course types on database as id => course_type_name
	 */
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
