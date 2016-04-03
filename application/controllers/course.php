<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('login.php');
require_once('module.php');
require_once('program.php');
require_once('graduation.php');
require_once('ead.php');
require_once('budgetplan.php');
require_once('enrollment.php');
require_once('usuario.php');
require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/exception/StudentRegistrationException.php");

class Course extends CI_Controller {

	public function index() {

		$this->load->model('course_model');

		$session = $this->session->userdata("current_user");
		$user = $session['user'];
		$userId = $user['id'];

		$group = new Module();
		$userIsAdmin = $group->checkUserGroup(GroupConstants::ADMIN_GROUP);

		if($userIsAdmin){
			$courses = $this->listAllCourses();
		}else{
			$courses = $this->getCoursesOfSecretary($userId);
		}

		$data = array(
			'courses' => $courses,
			'userData' => $user
		);

		loadTemplateSafelyByPermission("cursos",'course/course_index', $data);
	}

	public function getCourseTeachers($courseId){

		$this->load->model('course_model');

		$teachers = $this->course_model->getCourseTeachers($courseId);

		return $teachers;
	}

	public function enrollTeacherToCourse($teacherId, $courseId){

		$this->load->model('course_model');

		$wasEnrolled = $this->course_model->enrollTeacherToCourse($teacherId, $courseId);

		return $wasEnrolled;
	}

	public function removeTeacherFromCourse($teacherId, $courseId){

		$this->load->model('course_model');

		$wasRemoved = $this->course_model->removeTeacherFromCourse($teacherId, $courseId);

		return $wasRemoved;
	}

	public function defineTeacherSituation($courseId, $teacherId, $situation){

		$this->load->model('course_model');

		$defined = $this->course_model->defineTeacherSituation($courseId, $teacherId, $situation);

		return $defined;
	}

	public function courseStudents($courseId){

		$students = $this->getCourseStudents($courseId);
		$courseData = $this->getCourseById($courseId);

		$data = array(
			'students' => $students,
			'course' => $courseData
		);

		loadTemplateSafelyByPermission(PermissionConstants::STUDENT_LIST_PERMISSION, 'secretary/course_students', $data);
	}

	public function formToRegisterNewCourse(){

		$this->load->model('course_model');

		$courseTypes = $this->course_model->getAllCourseTypes();

		if($courseTypes !== FALSE){

			foreach($courseTypes as $courseType){
				$formCourseTypes[$courseType['id']] = $courseType['description'];
			}
		}else{
			$formCourseTypes = array('Nenhum tipo de curso cadastrado.');
		}

		$program = new Program();
		$registeredPrograms = $program->getAllPrograms();

		if($registeredPrograms !== FALSE){

			foreach ($registeredPrograms as $currentProgram){
				$registeredProgramsForm[$currentProgram['id_program']] = $currentProgram['program_name'];
			}
		}else{
			$registeredProgramsForm = FALSE;
		}

		$data = array(
			'form_course_types' => $formCourseTypes,
			'registeredPrograms' => $registeredProgramsForm
		);

		loadTemplateSafelyByPermission("cursos",'course/register_course', $data);
	}

	/**
	 * Function to load the page of a course that will be updated
	 * @param int $id
	 */
	public function formToEditCourse($courseId){

		$this->load->model('course_model');
		$course = $this->course_model->getCourseById($courseId);

		$user = new Usuario();
		$userToBeSecretaries = $user->getUsersToBeSecretaries();

		if($userToBeSecretaries !== FALSE){

			foreach($userToBeSecretaries as $user){
				$formUserSecretary[$user['id']] = $user['name'];
			}
		}else{
			$formUserSecretary = FALSE;
		}

		$course_controller = new Course();
		$secretaryRegistered = $course_controller->getCourseSecrecretary($course['id_course']);

		$course_types = $this->db->get('course_type')->result_array();

		foreach ($course_types as $ct) {
			$formCourseType[$ct['id']] = $ct['description'];
		}

		$originalCourseType = $this->course_model->getCourseTypeByCourseId($courseId);
		$originalCourseTypeId = $originalCourseType['id'];

		$program = new Program();
		$registeredPrograms = $program->getAllPrograms();

		if($registeredPrograms !== FALSE){

			foreach ($registeredPrograms as $currentProgram){
				$registeredProgramsForm[$currentProgram['id_program']] = $currentProgram['program_name'];
			}
		}

		$data = array(
			'course' => $course,
			'formUserSecretary' => $formUserSecretary,
			'secretary_registered' => $secretaryRegistered,
			'form_course_types' => $formCourseType,
			'original_course_type' => $originalCourseTypeId,
			'registeredPrograms' => $registeredProgramsForm
		);

		loadTemplateSafelyByPermission("cursos",'course/update_course', $data);
	}

	/**
	 * Register a new course
	 */
	public function newCourse(){

		$courseDataIsOk = $this->validatesNewCourseData();

		if($courseDataIsOk){

			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');
			$courseProgram = $this->input->post('courseProgram');
			$courseDuration = $this->input->post('course_duration');
			$totalCredits = $this->input->post('course_total_credits');
			$courseHours = $this->input->post('course_hours');
			$courseClass = $this->input->post('course_class');
			$courseDescription = $this->input->post('course_description');

			$course = array(
				'course_name' => $courseName,
				'course_type_id' => $courseType,
				'duration' => $courseDuration,
				'total_credits' => $totalCredits,
				'workload' => $courseHours,
				'start_class' => $courseClass,
				'description' => $courseDescription,
				'id_program' => $courseProgram
			);

			$this->load->model('course_model');

			$wasSaved = $this->course_model->saveCourse($course);

			/**
			 * DEPRECATED CODE
			 * $this->load->model('module_model');
			 * if($courseWasSaved){
			 * $groupsWereSaved = $this->module_model->saveNewCourseGroups($courseName);
			 * }
			 */

			if($wasSaved){
				$insertStatus = "success";
				$insertMessage =  "Curso \"{$courseName}\" cadastrado com sucesso";
			}else{
				$insertStatus = "danger";
				$insertMessage = "Curso \"{$courseName}\" já existe.";
			}

		}else{

			$insertStatus = "danger";
			$insertMessage = "Dados na forma incorreta.";
		}

		$this->session->set_flashdata($insertStatus, $insertMessage);

		redirect('cursos');
	}

	public function saveAcademicSecretary(){

		$academicSecretary = $this->input->post('academic_secretary');
		$idCourse = $this->input->post('id_course');
		$courseName = $this->input->post('course_name');

		$this->load->model('course_model');
		try{
			$wasSaved = $this->course_model->saveCourseAcademicSecretary($academicSecretary, $idCourse, $courseName);

			if($wasSaved){
				$saveStatus = "success";
				$saveMessage = "Secretário acadêmico salvo com sucesso.";
			}else{
				$saveStatus = "danger";
				$saveMessage = "Não foi possível salvar o secretário informado. Tente novamente.";
			}

		}catch(SecretaryException $caughtException){
			$saveStatus = "danger";
			$saveMessage = $caughtException->getMessage();
		}

		$this->session->set_flashdata($saveStatus, $saveMessage);
		redirect('/course/formToEditCourse/'.$idCourse);
	}

	public function saveFinancialSecretary(){

		$financialSecretary = $this->input->post('financial_secretary');
		$idCourse = $this->input->post('id_course');
		$courseName = $this->input->post('course_name');

		$this->load->model('course_model');

		try{
			$wasSaved = $this->course_model->saveCourseFinancialSecretary($financialSecretary, $idCourse, $courseName);

			if($wasSaved){
				$saveStatus = "success";
				$saveMessage = "Secretário financeiro salvo com sucesso.";
			}else{
				$saveStatus = "danger";
				$saveMessage = "Não foi possível salvar o secretário informado. Tente novamente.";
			}

		}catch(SecretaryException $caughtException){
			$saveStatus = "danger";
			$saveMessage = $caughtException->getMessage();
		}

		$this->session->set_flashdata($saveStatus, $saveMessage);
		redirect('/course/formToEditCourse/'.$idCourse);
	}

	/**
	 * Validates the data submitted on the new course form
	 */
	private function validatesNewCourseData(){

		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		$this->form_validation->set_rules("course_duration", "Course duration", "required");
		$this->form_validation->set_rules("course_total_credits", "Course total credits", "required");
		$this->form_validation->set_rules("course_hours", "Course hours", "required");
		$this->form_validation->set_rules("course_class", "Course class", "required");
		$this->form_validation->set_rules("course_description", "Course description", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}

	/**
	 * Function to update a registered course data
	 */
	public function updateCourse(){

		$courseDataIsOk = $this->validatesNewCourseData();

		if ($courseDataIsOk) {

			$idCourse = $this->input->post('id_course');
			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');
			$courseProgram = $this->input->post('courseProgram');
			$courseDuration = $this->input->post('course_duration');
			$totalCredits = $this->input->post('course_total_credits');
			$courseHours = $this->input->post('course_hours');
			$courseClass = $this->input->post('course_class');
			$courseDescription = $this->input->post('course_description');

			$course = array(
				'course_name' => $courseName,
				'course_type_id' => $courseType,
				'duration' => $courseDuration,
				'total_credits' => $totalCredits,
				'workload' => $courseHours,
				'start_class' => $courseClass,
				'description' => $courseDescription,
				'id_program' => $courseProgram
			);

			$this->load->model('course_model');

			$courseWasUpdated = $this->course_model->updateCourse($idCourse, $course);
			/**
			 * DEPRECATED CODE
			 * $secretaryWasUpdated = $this->course_model->updateSecretary($secretaryToRegister['id_secretary'], $secretaryToUpdate);
			 */


			// $dataIsOk = $courseWasUpdated && $secretaryWasUpdated;

			if($courseWasUpdated){
				$updateStatus = "success";
				$updateMessage = "Curso \"{$courseName}\" alterado com sucesso";
			}else{
				$updateStatus = "danger";
				$updateMessage = "Não foi possível alterar o curso \"{$courseName}\". Talvez o nome informado já exista. Tente novamente.";
			}

		} else {
			$updateStatus = "danger";
			$updateMessage = "Dados na forma incorreta.";
		}

		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect('cursos');
	}

	public function getCourseResearchLines($courseId){

		$this->load->model('course_model');

		$researchLines = $this->course_model->getCourseResearchLines($courseId);

		$researchLineNames = array();

		if($researchLines !== FALSE){

			foreach ($researchLines as $researchLine) {
				$researchLineId = $researchLine['id_research_line'];
				$researchLineNames = $this->getResearchLineNameById($researchLineId);
			}
		}

		return $researchLineNames;

	}

	public function getResearchLineNameById($researchLinesId){
		$this->load->model('course_model');

		$researchLinesName = $this->course_model->getResearchLineNameById($researchLinesId);

		return $researchLinesName['description'];
	}

	private function cleanUpOldCourseData($idCourse, $oldCourseType){

		// define("GRADUATION", "graduation");
		// define("EAD", "ead");
// 		define("ACADEMIC_PROGRAM", "academic_program");
// 		define("PROFESSIONAL_PROGRAM", "professional_program");

		$this->load->model('course_model');
		switch($oldCourseType){
			case GRADUATION:

				$this->cleanCourseDependencies($idCourse);
				$this->course_model->deleteCourseById($idCourse);
				break;

			case EAD:

				$this->cleanCourseDependencies($idCourse);
				$this->course_model->deleteCourseById($idCourse);
				break;

			default:

				break;
		}

	}

	private function cleanCourseDependencies($idCourse){

		// Clean all course dependencies
		$this->cleanBudgetplan($idCourse);
		$this->cleanEnrolledStudents($idCourse);
	}

	private function cleanEnrolledStudents($idCourse){
		$this->load->model('course_model');

		$this->course_model->cleanEnrolledStudents($idCourse);
	}

	private function cleanBudgetplan($idCourse){

		$budgetplan = new Budgetplan();

		$budgetplan->deleteBudgetplanByCourseId($idCourse);
	}

	/**
	 * Function to update courses that had their course types changed
	 * @param int $id_course
	 * @param array $secretaryToRegister
	 * @param array $courseType
	 * @param array $courseToUpdate
	 * @param array $commonAttributes
	 * @param string $post_graduation_type
	 */
	private function updateCourseToOtherCourseType($id_course, $courseType, $courseToUpdate, $commonAttributes=NULL, $post_graduation_type=NULL){

		switch ($courseType){
			case GRADUATION:
				try{

					$graduation = new Graduation();
					$insertionWasMade = $graduation->saveGraduationCourse($courseToUpdate);
					$updateStatus = "success";
					$updateMessage = "Curso \"{$courseToUpdate['course_name']}\" alterado com sucesso";

				}catch(CourseNameException $caughtException){
					$updateStatus = "danger";
					$updateMessage = $caughtException->getMessage();
				}
				$this->session->set_flashdata($updateStatus, $updateMessage);

				break;

			case EAD:
				try{

					$ead = new Ead();
					$insertionWasMade = $ead->saveEadCourse($courseToUpdate);
					$updateStatus = "success";
					$updateMessage = "Curso \"{$courseToUpdate['course_name']}\" alterado com sucesso";

				}catch(CourseNameException $caughtException){
					$updateStatus = "danger";
					$updateMessage = $caughtException->getMessage();
				}
				$this->session->set_flashdata($updateStatus, $updateMessage);

				break;

			case POST_GRADUATION:
				try{
					$post_graduation = new PostGraduation();
					$insertionWasMade = $post_graduation->savePostGraduationCourse($post_graduation_type, $commonAttributes, $courseToUpdate);
					$updateStatus = "success";
					$updateMessage = "Curso \"{$commonAttributes['course_name']}\" alterado com sucesso";

				}catch(CourseNameException $caughtException){
					$updateStatus = "danger";
					$updateMessage = $caughtException->getMessage();
				}
				$this->session->set_flashdata($updateStatus, $updateMessage);

				break;

			default:
				break;
		}

	}

	/**
	 * Validates the data submitted on the update course form
	 */
	private function validatesUpdateCourseData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		// $this->form_validation->set_rules("course_duration", "Course duration", "required");
		// $this->form_validation->set_rules("course_total_credits", "Course total credits", "required");
		// $this->form_validation->set_rules("course_hours", "Course hours", "required");
		// $this->form_validation->set_rules("course_class", "Course class", "required");
		// $this->form_validation->set_rules("course_description", "Course description", "required");
		//$this->form_validation->set_rules("secretary_type", "Secretary Type", "required");
		//$this->form_validation->set_rules("user_secretary", "User Secretary", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}

	/**
	 * Function to delete a registered course
	 */
	public function deleteCourse($courseId){

		$courseWasDeleted = $this->deleteCourseFromDb($courseId);

		if($courseWasDeleted){
			$deleteStatus = "success";
			$deleteMessage = "Curso excluído com sucesso.";
		}else{
			$deleteStatus = "danger";
			$deleteMessage = "Não foi possível excluir este curso.";
		}

		$this->session->set_flashdata($deleteStatus, $deleteMessage);

		redirect('cursos');
	}

	public function getCourseSecrecretary($id_course){

		$this->load->model('course_model');
		$secretary = $this->course_model->getSecretaryByCourseId($id_course);

		return $secretary;
	}


	public function getCourseAcademicSecretaryName($id_course){

		$this->load->model('course_model');
		$secretaryName = $this->course_model->getAcademicSecretaryName($id_course);

		return $secretaryName;
	}

	public function deleteSecretary(){
		$course_id = $this->input->post('id_course');
		$secretary_id = $this->input->post('id_secretary');
		$secretaryWasDeleted = $this->deleteSecretaryFromDb($course_id, $secretary_id);

		if($secretaryWasDeleted){
			$deleteStatus = "success";
			$deleteMessage = "Secretário excluído com sucesso.";
		}else{
			$deleteStatus = "danger";
			$deleteMessage = "Não foi possível excluir este secretário.";
		}

		$this->session->set_flashdata($deleteStatus, $deleteMessage);

		redirect('/course/formToEditCourse/'.$course_id);


	}

	private function deleteSecretaryFromDb($course_id, $secretary_id){
		$this->load->model('course_model');
		$deletedSecretary = $this->course_model->deleteSecretary($course_id,$secretary_id);

		return $deletedSecretary;
	}

	public function getCoursesOfSecretary($userId){

		$this->load->model('course_model');
		$courses = $this->course_model->getCoursesOfSecretary($userId);

		return $courses;
	}

	public function getCourseStudents($courseId){

		$this->load->model('course_model');

		$courseStudents = $this->course_model->getCourseStudents($courseId);

		return $courseStudents;
	}

	public function getCourseByName($courseName){

		$this->load->model('course_model');

		$course = $this->course_model->getCourseByName($courseName);

		return $course;
	}

	public function getCourseById($courseId){

		$this->load->model('course_model');

		$course = $this->course_model->getCourse(array('id_course' => $courseId));

		return $course;
	}

	public function checkIfCourseExists($courseId){

		$this->load->model('course_model');

		$courseExists = $this->course_model->checkIfCourseExists($courseId);

		return $courseExists;
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

	public function getCourseTypeByCourseId($courseId){

		$this->load->model('course_model');

		$courseType = $this->course_model->getCourseTypeByCourseId($courseId);

		return $courseType;
	}

	public function getCoursesToProgram($programId){

		$this->load->model('course_model');

		$programCourses = $this->course_model->getCoursesToProgram($programId);

		return $programCourses;
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

	function alpha_dash_space($str){
	    return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
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
