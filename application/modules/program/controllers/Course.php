<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/exception/StudentRegistrationException.php");

class Course extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("program/course_model");
	}

	public function index() {

		$session = getSession();
		$user = $session->getUserData();
		$userId = $user->getId();
		
		$this->load->module("auth/module");
		$userIsAdmin = $this->module->checkUserGroup(GroupConstants::ADMIN_GROUP);

		if($userIsAdmin){
			$courses = $this->course_model->getAllCourses();
		}
		else{
			$courses = $this->course_model->getCoursesOfSecretary($userId);
		}

		$data = array(
			'courses' => $courses,
			'userData' => $user,
			'isAdmin' => $userIsAdmin
		);

		loadTemplateSafelyByPermission(PermissionConstants::COURSES_PERMISSION, 'program/course/course_index', $data);
	}

	public function getCourseTeachers($courseId){

		$teachers = $this->course_model->getCourseTeachers($courseId);

		return $teachers;
	}

	public function enrollTeacherToCourse($teacherId, $courseId){

		$wasEnrolled = $this->course_model->enrollTeacherToCourse($teacherId, $courseId);

		return $wasEnrolled;
	}

	public function removeTeacherFromCourse($teacherId, $courseId){

		$wasRemoved = $this->course_model->removeTeacherFromCourse($teacherId, $courseId);

		return $wasRemoved;
	}

	public function defineTeacherSituation($courseId, $teacherId, $situation){

		$defined = $this->course_model->defineTeacherSituation($courseId, $teacherId, $situation);

		return $defined;
	}

	public function courseStudents($courseId){

		$students = $this->getCourseStudents($courseId);
		
		$students = $this->addStatusCourseStudents($students);
		
		$courseData = $this->getCourseById($courseId);

		$data = array(
			'students' => $students,
			'course' => $courseData
		);

		loadTemplateSafelyByPermission(PermissionConstants::STUDENT_LIST_PERMISSION, 'program/course/course_students', $data);
	}

	private function addStatusCourseStudents($students){

		if($students !== FALSE){
			foreach ($students as $key => $student) {
				$this->load->model("auth/usuarios_model");

				$id = $student['id'];
				$status = $this->usuarios_model->getUserStatus($id);
				$students[$key]['status'] = $status;
			}
		}

		return $students;
	}

	public function formToRegisterNewCourse(){

		$courseTypes = $this->course_model->getAllCourseTypes();

		if($courseTypes !== FALSE){

			foreach($courseTypes as $courseType){
				$formCourseTypes[$courseType['id']] = $courseType['description'];
			}
		}else{
			$formCourseTypes = array('Nenhum tipo de curso cadastrado.');
		}

		$this->load->model("program/program_model");
		$registeredPrograms = $this->program_model->getAllPrograms();

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

		loadTemplateSafelyByPermission(PermissionConstants::COURSES_PERMISSION,'program/course/register_course', $data);
	}

	/**
	 * Function to load the page of a course that will be updated
	 * @param int $id
	 */
	public function formToEditCourse($courseId){

		$course = $this->course_model->getCourseById($courseId);

		$this->load->module("auth/userController");
		$userToBeSecretaries = $this->usercontroller->getUsersToBeSecretaries();

		if($userToBeSecretaries !== FALSE){

			foreach($userToBeSecretaries as $user){
				$formUserSecretary[$user['id']] = $user['name'];
			}
		}else{
			$formUserSecretary = FALSE;
		}

		$secretaryRegistered = $this->getCourseSecretaries($course['id_course']);
		$secretaryRegistered = $this->addSecretariesName($secretaryRegistered);

		$course_types = $this->db->get('course_type')->result_array();

		foreach ($course_types as $ct) {
			$formCourseType[$ct['id']] = $ct['description'];
		}

		$originalCourseType = $this->course_model->getCourseTypeByCourseId($courseId);
		$originalCourseTypeId = $originalCourseType['id'];

		$this->load->model("program/program_model");
		$registeredPrograms = $this->program_model->getAllPrograms();

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

		loadTemplateSafelyByPermission(PermissionConstants::COURSES_PERMISSION,'program/course/update_course', $data);
	}


	private function addSecretariesName($secretariesRegistered){

		if($secretariesRegistered !== FALSE){

			foreach ($secretariesRegistered as $key => $secretaryRegistered) {

				$id = $secretaryRegistered['id_user'];
				$userName = $this->usercontroller->getUserNameById($id);
				$secretariesRegistered[$key]['user_name'] = $userName;

			}

		}

		return $secretariesRegistered;

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

			

			$wasSaved = $this->course_model->saveCourse($course);

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

		$session = getSession();
		$session->showFlashMessage($insertStatus, $insertMessage);

		redirect(PermissionConstants::COURSES_PERMISSION);
	}

	public function saveAcademicSecretary(){

		$academicSecretary = $this->input->post('academic_secretary');
		$idCourse = $this->input->post('id_course');
		$courseName = $this->input->post('course_name');

		
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

		$session = getSession();
		$session->showFlashMessage($saveStatus, $saveMessage);
		redirect('program/course/formToEditCourse/'.$idCourse);
	}

	public function saveFinancialSecretary(){

		$financialSecretary = $this->input->post('financial_secretary');
		$idCourse = $this->input->post('id_course');
		$courseName = $this->input->post('course_name');

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

		$session = getSession();
		$session->showFlashMessage($saveStatus, $saveMessage);
		redirect('program/course/formToEditCourse/'.$idCourse);
	}

	/**
	 * Validates the data submitted on the new course form
	 */
	private function validatesNewCourseData(){

		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|valid_name");
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
		$idCourse = $this->input->post('id_course');

		$session = getSession();
		if ($courseDataIsOk) {

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

			

			$courseWasUpdated = $this->course_model->updateCourse($idCourse, $course);

			if($courseWasUpdated){
				$updateStatus = "success";
				$updateMessage = "Curso \"{$courseName}\" alterado com sucesso";
				$session->showFlashMessage($updateStatus, $updateMessage);
				redirect('cursos');	
			}
			else{
				$updateStatus = "danger";
				$updateMessage = "Não foi possível alterar o curso \"{$courseName}\". Talvez o nome informado já exista. Tente novamente.";
				$session->showFlashMessage($updateStatus, $updateMessage);
				redirect('program/cursos/formToEditCourse/{$idCourse}');
			}

		} else {

			$this->formToEditCourse($idCourse);
		}
	}

	public function getCourseResearchLines($courseId){

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
		
		$researchLinesName = $this->course_model->getResearchLineNameById($researchLinesId);

		return $researchLinesName['description'];
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

		$session = getSession();
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
				$session->showFlashMessage($updateStatus, $updateMessage);

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
				$session->showFlashMessage($updateStatus, $updateMessage);

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
				$session->showFlashMessage($updateStatus, $updateMessage);

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
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|valid_name");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		$this->form_validation->set_rules("course_duration", "Course duration", "required");
		$this->form_validation->set_rules("course_total_credits", "Course total credits", "required");
		$this->form_validation->set_rules("course_hours", "Course hours", "required");
		$this->form_validation->set_rules("course_class", "Course class", "required");
		$this->form_validation->set_rules("course_description", "Course description", "required");
		$this->form_validation->set_rules("secretary_type", "Secretary Type", "required");
		$this->form_validation->set_rules("user_secretary", "User Secretary", "required");
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

		$session = getSession();
		$session->showFlashMessage($deleteStatus, $deleteMessage);

		redirect('cursos');
	}

	public function getCourseSecretaries($courseId){

		$secretary = $this->course_model->getCourseSecretaries($courseId);

		return $secretary;
	}


	public function getCourseAcademicSecretaryName($id_course){

		
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

		$session = getSession();
		$session->showFlashMessage($deleteStatus, $deleteMessage);

		redirect('program/course/formToEditCourse/'.$course_id);

	}

	private function deleteSecretaryFromDb($course_id, $secretary_id){
		
		$deletedSecretary = $this->course_model->deleteSecretary($course_id,$secretary_id);

		return $deletedSecretary;
	}

	public function getCourseStudents($courseId){

		$courseStudents = $this->course_model->getCourseStudents($courseId);

		return $courseStudents;
	}

	public function getCourseByName($courseName){

		$course = $this->course_model->getCourseByName($courseName);

		return $course;
	}

	public function getCourseById($courseId){

		$course = $this->course_model->getCourse(array('id_course' => $courseId));

		return $course;
	}

	public function checkIfCourseExists($courseId){

		$courseExists = $this->course_model->checkIfCourseExists($courseId);

		return $courseExists;
	}

	/**
	 * Delete a registered course on DB
	 * @param $course_id - The id from the course to be deleted
	 * @return true if the exclusion was made right and false if does not
	 */
	public function deleteCourseFromDb($course_id){

		$deletedCourse = $this->course_model->deleteCourseById($course_id);

		return $deletedCourse;
	}

	public function getCoursesType($courses){

		if($courses !== FALSE){
			foreach ($courses as $key => $course) {
				$id = $course['id_course'];
				
				$courseType = $this->course_model->getCourseTypeByCourseId($id);
				$courses[$key]['type'] = $courseType['description'];
			}
		}

		return $courses;

	}

	public function getCourseTypeByCourseId($courseId){

		$courseType = $this->course_model->getCourseTypeByCourseId($courseId);

		return $courseType;
	}

	public function getCoursesToProgram($programId){

		$programCourses = $this->course_model->getCoursesToProgram($programId);

		return $programCourses;
	}

	public function createCourseResearchLine(){
		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		if($secretaryCourses !== FALSE){

			foreach ($secretaryCourses as $key => $courses){
				$course[$courses['id_course']] = $courses['course_name'];
			}
		}else{
			$course = FALSE;
		}

		$data = array(
			'courses'=> $course
		);

		loadTemplateSafelyByPermission(PermissionConstants::RESEARCH_LINES_PERMISSION, 'program/course/create_research_line', $data);
	}


	public function updateCourseResearchLine($researchId, $courseId){

		$actualCourse = $this->course_model->getCourseById($courseId);
		$actualCourseForm = $actualCourse['id_course'];

		$description = $this->course_model->getResearchDescription($researchId,$courseId);

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		foreach ($secretaryCourses as $key => $courses){
			$course[$courses['id_course']] = $courses['course_name'];
		}

		$data = array(
			'researchId' => $researchId,
			'description' => $description,
			'actualCourse' => $actualCourseForm,
			'courses' => $course
		);

		loadTemplateSafelyByPermission(PermissionConstants::RESEARCH_LINES_PERMISSION, 'program/course/update_research_line', $data);
	}

	public function removeCourseResearchLine($researchLineId,$course){

		$wasRemoved = $this->course_model->removeCourseResearchLine($researchLineId);

		if($wasRemoved){
			$status = "success";
			$message = "Linha de pesquisa removida do curso {$course} com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover o linha de pesquisa do curso {$course}";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("research_lines/");

	}

	public function research_lines(){
		
		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$secretaryCourses = $this->course_model->getCoursesOfSecretary($userId);

		$this->loadResearchLinesPage($secretaryCourses);
	}

	public function loadResearchLinesPage($secretaryCourses){

		if($secretaryCourses !== FALSE){

			foreach ($secretaryCourses as $key => $course){

				$researchLines[$key] = $this->course_model->getCourseResearchLines($course['id_course']);
				$courses[$key] = $course;
			}
		}else{
			$researchLines = FALSE;
			$courses = FALSE;
		}

		$data = array(
			'research_lines' => $researchLines,
			'courses' => $courses
		);

		loadTemplateSafelyByPermission(PermissionConstants::RESEARCH_LINES_PERMISSION, 'secretary/secretary/secretary_research_lines', $data);
	}

	public function saveResearchLine(){

		$success = $this->validateReseachLineData();
		$session = getSession();
		if ($success) {
			$researchLine  = $this->input->post("researchLine");
			$researchCourse   = $this->input->post("research_course");

			$newResearchLine = array(
					'description'    => $researchLine,
					'id_course' => $researchCourse
			);


			$wasSaved = $this->course_model->saveResearchLine($newResearchLine);
			if ($wasSaved){
				$status = "success";
				$message = "Linha de pesquisa salva do curso ".$course." com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível salvar o linha de pesquisa do curso ". $course;
			}

			$session->showFlashMessage($status,$message);
			redirect("research_lines/");
		} 
		else {

			$this->createCourseResearchLine();

		}
	}

	private function validateReseachLineData(){		

		$this->load->library("form_validation");
		$this->form_validation->set_rules("researchLine", "Linha de Pesquisa", "required|trim|valid_name");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		return $success;
	}

	public function updateResearchLine(){
		
		$success = $this->validateReseachLineData();
		$session = getSession();
		$researchCourse   = $this->input->post("research_course");
		$researchLineId = $this->input->post("id_research_line");
		
		if ($success) {
			$researchLine  = $this->input->post("researchLine");

			$updateResearchLine = array(
					'description'    => $researchLine,
					'id_course' => $researchCourse
			);


			$wasSaved = $this->course_model->updateResearchLine($updateResearchLine, $researchLineId);
			if ($wasSaved){
				$status = "success";
				$message = "Linha de pesquisa alterada com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível alterar o linha de pesquisa.";
			}

			$session->showFlashMessage($status,$message);
			redirect("research_lines/");
		} else {
			$this->updateCourseResearchLine($researchLineId, $researchCourse);
		}
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
