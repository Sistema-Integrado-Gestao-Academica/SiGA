<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('login.php');
require_once('module.php');
require_once('postgraduation.php');
require_once('masterdegree.php');
require_once('doctorate.php');
require_once('graduation.php');
require_once('ead.php');
require_once('budgetplan.php');
require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/exception/MasterDegreeException.php");
require_once(APPPATH."/exception/DoctorateException.php");

class Course extends CI_Controller {

	public function index(){

		loadTemplateSafelyByPermission("cursos",'course/course_index');
	}

	public function enrollStudentToCourse($courseId){

		$this->load->model('course_model');
		$course = $this->course_model->getCourseById($courseId);

		$courseName = $course->course_name;
		$courseType = $course->course_type;

		switch($courseType){
			case "academic_program":
					
				$masterDegree = new MasterDegree();
				$foundMasterDegree = $masterDegree->getMasterDegreeByCourseId($courseId);
				
				$doctorate = new Doctorate();
				$foundDoctorate = $doctorate->getRegisteredDoctorateForCourse($courseId);
				
				break;

			case "professional_program":
				
				$masterDegree = new MasterDegree();
				$foundMasterDegree = $masterDegree->getMasterDegreeByCourseId($courseId);

				$foundDoctorate = FALSE;
				break;

			default:
				$foundMasterDegree = FALSE;
				$foundDoctorate = FALSE;
				break;
		}

		$courseData = array(
			'courseId' => $courseId,
			'courseName' => $courseName,
			'courseType' => $courseType,
			'masterDegree'=> $foundMasterDegree,
			'doctorate' => $foundDoctorate
		);

		loadTemplateSafelyByPermission("cursos",'course/enroll_student.php', $courseData);
	}

	public function enrollStudent(){
		
		$this->load->model('course_model');

		define('ACADEMIC_PROGRAM', 'academic_program');
		define('PROFESSIONAL_PROGRAM', 'professional_program');
		define('GRADUATION', 'graduation');
		define('EAD', 'ead');
		define('MASTER_DEGREE', 'master_degree');
		define('DOCTORATE', 'doctorate');

		$courseId = $this->input->post('courseId');
		$courseType = $this->input->post('courseType');
		$userToEnroll = $this->input->post('user_to_enroll');

		switch($courseType){

			case ACADEMIC_PROGRAM:
			case PROFESSIONAL_PROGRAM:
					
				$academicProgramType = $this->input->post('program_dropdown');

				switch($academicProgramType){
					case MASTER_DEGREE:
						$masterDegree = new MasterDegree();
						$foundMasterDegree = $masterDegree->getMasterDegreeByCourseId($courseId);
						$masterDegreeId = $foundMasterDegree['id_master_degree'];

						// $enrollment = array(
						// 	'id_course' => $courseId,
						// 	'id_user' => $userToEnroll,
						// 	'id_master_degree' => $masterDegreeId,
						// 	'enroll_date' => SYSDATE()
						// );

						$enrollment = "INSERT INTO course_student (id_course, id_user, id_master_degree, enroll_date) VALUES ({$courseId}, {$userToEnroll}, {$masterDegreeId}, NOW())";

						break;
					
					case DOCTORATE:
						$doctorate = new Doctorate();
						$foundDoctorate = $doctorate->getRegisteredDoctorateForCourse($courseId);

						$doctorateId = $foundDoctorate['id_doctorate'];

						// $enrollment = array(
						// 	'id_course' => $courseId,
						// 	'id_user' => $userToEnroll,
						// 	'id_doctorate' => $doctorateId,
						// 	'enroll_date' => SYSDATE()
						// );

						$enrollment = "INSERT INTO course_student (id_course, id_user, id_doctorate, enroll_date) VALUES ({$courseId}, {$userToEnroll}, {$doctorateId}, NOW())";

						break;
					
					default:
						break;
				}

				break;

			case GRADUATION:
			case EAD:
				// $enrollment = array(
				// 	'id_course' => $courseId,
				// 	'id_user' => $userToEnroll,
				// 	'enroll_date' => SYSDATE()
				// );
				
				$enrollment = "INSERT INTO course_student (id_course, id_user, enroll_date) VALUES ({$courseId}, {$userToEnroll}, NOW())";

				break;

			default:
				break;
		}

		$this->course_model->enrollStudentIntoCourse($enrollment);
		$this->addStudentGroupToNewStudent($userToEnroll);

		$this->session->set_flashdata("success", "Aluno matriculado com sucesso");
		redirect("secretary_home");
	}

	private function addStudentGroupToNewStudent($userId){
		
		$group = new Module();

		$studentGroup = "estudante";
		$group->addGroupToUser($studentGroup, $userId);
		
		$guestGroup = "convidado";
		$group->deleteGroupOfUser($guestGroup, $userId);
	}

	public function checkChoosenCourseType(){

		define('POST_GRADUATION', 'post_graduation');
		define('GRADUATION', 'graduation');
		define('DISTANCE_EDUCATION', 'ead');

		$choosenCourseType = $this->input->post('courseType');

		switch($choosenCourseType){
			case POST_GRADUATION:
				// Function located on the helper 'forms' - Loaded by autoload
				postGraduationTypesSelect();
				break;
			case GRADUATION:
				// Code to the graduation specificities
				break;
			case DISTANCE_EDUCATION:
				// Code to the EAD specificities 
				break;
			default:
				// Function located on the helper 'forms' - Loaded by autoload
				emptyDiv();
		}
	}

	public function checkChoosenPostGraduationType(){

		// Option values for the post graduation type <select> - Look this select id on 'forms' helper
		define('ACADEMIC_PROGRAM', 'academic_program');
		define('PROFESSIONAL_PROGRAM', 'professional_program');

		$choosenPostGraduationType = $this->input->post('postGradType');

		switch($choosenPostGraduationType){
			case ACADEMIC_PROGRAM:
				// Function located on the helper 'forms' - Loaded by autoload
				academicProgramForm();
				break;
			case PROFESSIONAL_PROGRAM:
				// Function located on the helper 'forms' - Loaded by autoload
				professionalProgramForm();
				break;
			default:
				// Function located on the helper 'forms' - Loaded by autoload
				emptyDiv();
		}
	}

	public function displayMasterDegreeUpdateForm(){

		define('ACADEMIC_PROGRAM', 'academic_program');

		$choosenProgram = $this->input->post('program');

		switch($choosenProgram){
			// This form is only aplicable to the academic program
			case ACADEMIC_PROGRAM:
				masterDegreeProgramForm();
				break;
			default:
				emptyDiv();
				break;
		}
	}

	public function displayRegisteredDoctorate(){
		
		define('ACADEMIC_PROGRAM', 'academic_program');
		
		$choosenProgram = $this->input->post('program');
		$course = $this->input->post('course');

		switch($choosenProgram){
			// This form is only aplicable to the academic program
			case ACADEMIC_PROGRAM:
				$this->getRegisteredDoctorateForThisCourse($course);
				break;
			default:
				emptyDiv();
				break;
		}
	}

	private function getRegisteredDoctorateForThisCourse($courseId){
		$doctorate = new Doctorate();
		$registeredDoctorate = $doctorate->getRegisteredDoctorateForCourse($courseId);
		$haveMasterDegree = $doctorate->checkIfHaveMasterDegree($courseId);
		$haveDoctorate = $doctorate->checkIfHaveDoctorate($courseId);

		displayRegisteredDoctorateData($courseId, $haveMasterDegree, $haveDoctorate, $registeredDoctorate);
	}

	// Used for the update course page
	public function checkChoosenProgram(){

		// Option values for the post graduation type <select> - Look this select id on 'forms' helper
		define('ACADEMIC_PROGRAM', 'academic_program');
		define('PROFESSIONAL_PROGRAM', 'professional_program');

		$choosenProgram = $this->input->post('program');
		$course = $this->input->post('course');

		switch($choosenProgram){
			case ACADEMIC_PROGRAM:
				// Function located on the helper 'forms' - Loaded by autoload
				$this->displayRegisteredMasterDegree($course);

				break;
			case PROFESSIONAL_PROGRAM:
				// Function located on the helper 'forms' - Loaded by autoload
				professionalProgramForm();
				break;
			default:
				// Function located on the helper 'forms' - Loaded by autoload
				emptyDiv();
				break;
		}
	}

	private function displayRegisteredMasterDegree($courseId){
		
		$courseCommonAttributes = $this->getCourseCommonAttributes($courseId);

		$registeredMasterDegree = $this->getRegisteredMasterDegreeForThisCourse($courseId);

		$commonAttributesIsOk = $courseCommonAttributes != FALSE;
		$specificsAttributesIsOk = $registeredMasterDegree != FALSE;
		$attributesIsOk = $commonAttributesIsOk && $specificsAttributesIsOk;		

		if($attributesIsOk){
			$masterDegreeData = array_merge($courseCommonAttributes, $registeredMasterDegree);
		}else{
			$masterDegreeData = FALSE;
		}

		displayMasterDegreeData($masterDegreeData);	
	}

	private function getRegisteredMasterDegreeForThisCourse($courseId){
		$masterDegree = new MasterDegree();
		$foundMasterDegree = $masterDegree->getMasterDegreeByCourseId($courseId);

		return $foundMasterDegree;
	}

	private function getCourseCommonAttributes($courseId){
		$this->load->model('course_model');
		$commonAttributes = $this->course_model->getCommonAttributesForThisCourse($courseId);

		return $commonAttributes;
	}

	public function checkChoosenAcademicProgram(){
		
		define('MASTER_DEGREE', 'master_degree');
		define('DOCTORATE', 'doctorate');

		$choosenAcademicProgram = $this->input->post('academicProgram');

		switch($choosenAcademicProgram){
			case MASTER_DEGREE:
				// Function located on the helper 'forms' - Loaded by autoload
				masterDegreeProgramForm();
				break;
			case DOCTORATE:
				// Function located on the helper 'forms' - Loaded by autoload
				doctorateProgramForm();
				break;
			default:
				// Function located on the helper 'forms' - Loaded by autoload
				emptyDiv();
				break;
		}
	}

	public function formToCreateDoctorateCourse($courseId){
		
		$course = array('course_id' => $courseId);

		loadTemplateSafelyByPermission("cursos",'course/register_doctorate_course', $course);
	}

	public function formToUpdateDoctorateCourse($courseId){

		$course = array('course_id' => $courseId);

		loadTemplateSafelyByPermission("cursos",'course/update_doctorate_course', $course);	
	}

	public function registerDoctorateCourse(){
		
		$doctorateDataIsOk = $this->validatesNewDoctorateData();

		$programId = $this->input->post('course_id');

		if($doctorateDataIsOk){

			$doctorateName = $this->input->post('doctorate_course_name');
			$doctorateDuration = $this->input->post('course_duration');
			$doctorateTotalCredits = $this->input->post('course_total_credits');
			$doctorateHours= $this->input->post('course_hours');
			$doctorateClass= $this->input->post('course_class');
			$doctorateDescription = $this->input->post('course_description');

			$doctorateToRegister = array(
				'doctorate_name' => $doctorateName,
				'duration' => $doctorateDuration,
				'total_credits' => $doctorateTotalCredits,
				'workload' =>$doctorateHours,
				'start_class' => $doctorateClass,
				'description' => $doctorateDescription
			);

			$doctorate = new Doctorate();
			$doctorate->saveDoctorate($programId, $doctorateToRegister);

			$insertStatus = "success";
			$insertMessage = "Doutorado cadastrado com sucesso.";
			
			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('/course/index');

		}else{
			$insertStatus = "danger";
			$insertMessage = "Dados na forma incorreta.";
			
			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('registerDoctorateCourse/'.$courseId);
		}
		
	}

	public function updateDoctorateCourse(){

		// $doctorateDataIsOk = $this->validatesNewDoctorateData();
		$doctorateDataIsOk = TRUE;

		$programId = $this->input->post('course_id');

		if($doctorateDataIsOk){

			$doctorateName = $this->input->post('doctorate_course_name');
			$doctorateDuration = $this->input->post('course_duration');
			$doctorateTotalCredits = $this->input->post('course_total_credits');
			$doctorateHours= $this->input->post('course_hours');
			$doctorateClass= $this->input->post('course_class');
			$doctorateDescription = $this->input->post('course_description');

			$doctorateToUpdate = array(
				'doctorate_name' => $doctorateName,
				'duration' => $doctorateDuration,
				'total_credits' => $doctorateTotalCredits,
				'workload' =>$doctorateHours,
				'start_class' => $doctorateClass,
				'description' => $doctorateDescription
			);

			try{

				$doctorate = new Doctorate();
				$doctorate->updateDoctorate($programId ,$doctorateToUpdate);

				$updateStatus = "success";
				$updateMessage =  "Doutorado alterado com sucesso!";
			}catch(DoctorateException $caughtException){
				$updateStatus = "danger";
				$updateMessage =  $caughtException->getMessage();
			}

		}else{
			$updateStatus = "danger";
			$updateMessage =  "Dados na forma incorreta.";
		}
		
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect('/course/index');
	}

	public function removeDoctorateCourse($courseId){
		$doctorate = new Doctorate();
		$doctorate->deleteDoctorate($courseId);

		$this->session->set_flashdata('success', 'Doutorado apagado com sucesso!');
		redirect('/course/index');
	}

	/**
	 * Validates the data submitted on the register doctorate form
	 */
	private function validatesNewDoctorateData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("doctorate_course_name", "Doctorate Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("course_duration", "Course duration", "required");
		$this->form_validation->set_rules("course_total_credits", "Course total credits", "required");
		$this->form_validation->set_rules("course_hours", "Course hours", "required");
		$this->form_validation->set_rules("course_class", "Course class", "required");
		$this->form_validation->set_rules("course_description", "Course description", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}

	public function formToRegisterNewCourse(){
		$this->load->helper('url');
		$site_url = site_url();
		$data = array(
			'url' => $site_url
		);

		loadTemplateSafelyByPermission("cursos",'course/register_course', $data);
	}
	
	/**
	 * Function to load the page of a course that will be updated
	 * @param int $id
	 */
	public function formToEditCourse($id){
		$this->load->helper('url');
		$site_url = site_url();
		
		$this->load->model('course_model');
		$course_searched = $this->course_model->getCourseById($id);
		$data = array(
			'course' => $course_searched,
			'url' => $site_url
		);

		loadTemplateSafelyByPermission("cursos",'course/update_course', $data);

	}
	
	/**
	 * Register a new course
	 */
	public function newCourse(){

		$courseDataIsOk = $this->validatesNewCourseData();

		if($courseDataIsOk){

			define("GRADUATION", "graduation");
			define("EAD", "ead");
			define("POST_GRADUATION", "post_graduation");

			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');

			$secretaryType = $this->input->post('secretary_type');
			$userSecretary = $this->input->post('user_secretary');
			
			// Secretary to be saved on database. Array with column names and its values
			$secretaryToRegister = array(
				'id_user'   => $userSecretary,
				'id_group' => $secretaryType
			);


			switch ($courseType){
				case GRADUATION:
					$courseToRegister = array(
						'course_name' => $courseName,
						'course_type' => $courseType
					);

					$graduation = new Graduation();
					$insertionWasMade = $graduation->saveGraduationCourse($courseToRegister,$secretaryToRegister);
					
					break;

				case POST_GRADUATION:

					$post_graduation_type = $this->input->post('post_graduation_type');
					$program_name = $this->input->post('program_name');
					$post_graduation_duration = $this->input->post('course_duration');
					$post_graduation_total_credits = $this->input->post('course_total_credits');
					$post_graduation_hours = $this->input->post('course_hours');
					$post_graduation_class = $this->input->post('course_class');
					$post_graduation_description = $this->input->post('course_description');

					$commonAttr = array(
						'course_name' => $program_name,
						'course_type' => $post_graduation_type
					);

					$courseToRegister = array(
						'master_degree_name' => $courseName,
						'duration' => $post_graduation_duration,
						'total_credits' => $post_graduation_total_credits,
						'workload' =>$post_graduation_hours,
						'start_class' => $post_graduation_class,
						'description' => $post_graduation_description
					);

					$post_graduation = new PostGraduation();
					$insertionWasMade = $post_graduation->savePostGraduationCourse($post_graduation_type, $commonAttr, $courseToRegister, $secretaryToRegister);

					break;

				case EAD:
					$courseToRegister = array(
						'course_name' => $courseName,
						'course_type' => $courseType
					);
					
					$ead = new Ead();
					$insertionWasMade = $ead->saveEadCourse($courseToRegister, $secretaryToRegister);	
					
					break;

				default:
					
					break;
			}

			if($insertionWasMade){
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

		redirect('/course/index');
	}

	/**
	 * Validates the data submitted on the new course form
	 */
	private function validatesNewCourseData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("courseName", "Course Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("courseType", "Course Type", "required");
		// $this->form_validation->set_rules("course_duration", "Course duration", "required");
		// $this->form_validation->set_rules("course_total_credits", "Course total credits", "required");
		// $this->form_validation->set_rules("course_hours", "Course hours", "required");
		// $this->form_validation->set_rules("course_class", "Course class", "required");
		// $this->form_validation->set_rules("course_description", "Course description", "required");
		$this->form_validation->set_rules("secretary_type", "Secretary Type", "required");
		$this->form_validation->set_rules("user_secretary", "User Secretary", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}

	/**
	 * Function to update a registered course data
	 */
	public function updateCourse(){

		// $courseDataIsOk = $this->validatesUpdateCourseData();
		$courseDataIsOk = TRUE;
		
		if($courseDataIsOk){

			define("GRADUATION", "graduation");
			define("EAD", "ead");
			define("POST_GRADUATION", "post_graduation");

			$courseName = $this->input->post('courseName');
			$courseType = $this->input->post('courseType');
			$original_course_type = $this->input->post('original_course_type');
			
			// Due to the bifurcation of the post-graduation into academic and professional programs
			if($courseType == POST_GRADUATION){
				$courseType = $this->input->post('post_graduation_type');
			}

			$idCourse = $this->input->post('id_course');
			$secretaryType = $this->input->post('secretary_type');
			$userSecretary = $this->input->post('user_secretary');

			// Secretary to be saved on database. Array with column names and its values
			$secretaryToUpdate = array(
				'id_course' => $idCourse,
				'id_user'   => $userSecretary,
				'id_group' => $secretaryType
			);
	
			if($courseType == $original_course_type){
				
				if($courseType == "academic_program" || $courseType == "professional_program" ){
					$courseType = POST_GRADUATION;
				}

				// Switch to normal flow of updating a course data without changing course type
				switch($courseType){
					case GRADUATION:
							
						$courseToUpdate = array(
							'course_name' => $courseName,
							'course_type' => $courseType
						);
							
						try{
								
							$graduation = new Graduation();
							$insertionWasMade = $graduation->updateGraduationCourse($idCourse, $courseToUpdate, $secretaryToUpdate, $original_course_type);
							$updateStatus = "success";
							$updateMessage = "Curso \"{$courseName}\" alterado com sucesso";
				
						}catch(CourseNameException $caughtException){
							$updateStatus = "danger";
							$updateMessage = $caughtException->getMessage();
						}
				
						break;
				
					case POST_GRADUATION:
							
						$post_graduation_type = $this->input->post('post_graduation_type');
						
						$master_degree_name = $this->input->post('master_degree_name_update');
						$post_graduation_duration = $this->input->post('course_duration');
						$post_graduation_total_credits = $this->input->post('course_total_credits');
						$post_graduation_hours= $this->input->post('course_hours');
						$post_graduation_class= $this->input->post('course_class');
						$post_graduation_description = $this->input->post('course_description');
						
						$commonAttributes = array(
							'course_name' => $courseName,
							'course_type' => $post_graduation_type
						);
				
						$courseToUpdate = array(
							'master_degree_name' => $master_degree_name,
							'duration' => $post_graduation_duration,
							'total_credits' => $post_graduation_total_credits,
							'workload' =>$post_graduation_hours,
							'start_class' => $post_graduation_class,
							'description' => $post_graduation_description
						);
				
						try{
				
							$post_graduation = new PostGraduation();
							$post_graduation->updatePostGraduationCourse(
								$idCourse, $post_graduation_type, $commonAttributes,
								$courseToUpdate, $secretaryToUpdate
							);
							$updateStatus = "success";
							$updateMessage = "Curso \"{$courseName}\" alterado com sucesso";
						}catch(CourseNameException $caughtException){
							$updateStatus = "danger";
							$updateMessage = $caughtException->getMessage();
						}catch(CourseException $caughtException){
							// Do treatment here
						}
				
						break;
				
					case EAD:
							
						$courseToUpdate = array(
							'course_name' => $courseName,
							'course_type' => $courseType
						);
				
						try{
								
							$ead = new Ead();
							$insertionWasMade = $ead->updateEadCourse($idCourse, $courseToUpdate,$secretaryToUpdate);
							$updateStatus = "success";
							$updateMessage = "Curso \"{$courseName}\" alterado com sucesso";
								
						}catch(CourseNameException $caughtException){
							$updateStatus = "danger";
							$updateMessage = $caughtException->getMessage();
						}
							
						break;
				
					default:
							
						break;
				}
			}else{

				if($courseType == "academic_program" || $courseType == "professional_program" ){
					$courseType = POST_GRADUATION;
				}

				$this->cleanUpOldCourseData($idCourse, $original_course_type);
				
				// Switch to alternative flow of updating a course data. In this way course type has changed
				switch($courseType){
					case GRADUATION:
							
						$courseToUpdate = array(
							'course_name' => $courseName,
							'course_type' => $courseType
						);

						$this->updateCourseToOtherCourseType($idCourse,$secretaryToUpdate,$courseType,$courseToUpdate);
						
						break;
				
					case POST_GRADUATION:
							
						$post_graduation_type = $this->input->post('post_graduation_type');
						
						$master_degree_name = $this->input->post('master_degree_name_update');
						$post_graduation_duration = $this->input->post('course_duration');
						$post_graduation_total_credits = $this->input->post('course_total_credits');
						$post_graduation_hours= $this->input->post('course_hours');
						$post_graduation_class= $this->input->post('course_class');
						$post_graduation_description = $this->input->post('course_description');
						
						$commonAttributes = array(
								'course_name' => $courseName,
								'course_type' => $post_graduation_type
						);
				
						$courseToUpdate = array(
								'master_degree_name' => $master_degree_name,
								'duration' => $post_graduation_duration,
								'total_credits' => $post_graduation_total_credits,
								'workload' =>$post_graduation_hours,
								'start_class' => $post_graduation_class,
								'description' => $post_graduation_description
						);

						$this->updateCourseToOtherCourseType($idCourse,$secretaryToUpdate,$courseType,$courseToUpdate,$commonAttributes,$post_graduation_type);
						break;
				
					case EAD:
							
						$courseToUpdate = array(
						'course_name' => $courseName,
						'course_type' => $courseType
						);
						

						$this->updateCourseToOtherCourseType($idCourse,$secretaryToUpdate,$courseType,$courseToUpdate);
						break;
				
					default:
							
						break;
				}
				
			}
					
		
		}else{
			$updateStatus = "danger";
			$updateMessage = "Dados na forma incorreta.";
		}
		
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect('/course/index');
	}
	
	private function cleanUpOldCourseData($idCourse, $oldCourseType){

		// define("GRADUATION", "graduation");
		// define("EAD", "ead");
		define("ACADEMIC_PROGRAM", "academic_program");
		define("PROFESSIONAL_PROGRAM", "professional_program");

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

			case ACADEMIC_PROGRAM:
			case PROFESSIONAL_PROGRAM:
				
				$this->cleanCourseDependencies($idCourse);
				
				$post_graduation = new PostGraduation();
				$post_graduation->cleanPostGraduationData($idCourse, $oldCourseType);
	
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
	private function updateCourseToOtherCourseType($id_course, $secretaryToRegister, $courseType, $courseToUpdate, $commonAttributes=NULL, $post_graduation_type=NULL){
		
		switch ($courseType){
			case GRADUATION: 
				try{

					$graduation = new Graduation();
					$insertionWasMade = $graduation->saveGraduationCourse($courseToUpdate,$secretaryToRegister);
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
					$insertionWasMade = $ead->saveEadCourse($courseToUpdate, $secretaryToRegister);
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
					$insertionWasMade = $post_graduation->savePostGraduationCourse($post_graduation_type, $commonAttributes, $courseToUpdate, $secretaryToRegister);
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
		$this->form_validation->set_rules("secretary_type", "Secretary Type", "required");
		$this->form_validation->set_rules("user_secretary", "User Secretary", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}

	/**
	 * Function to delete a registered course
	 */
	public function deleteCourse(){
		$course_id = $this->input->post('id_course');
		$courseWasDeleted = $this->deleteCourseFromDb($course_id);

		if($courseWasDeleted){
			$deleteStatus = "success";
			$deleteMessage = "Curso excluído com sucesso.";
		}else{
			$deleteStatus = "danger";
			$deleteMessage = "Não foi possível excluir este curso.";
		}

		$this->session->set_flashdata($deleteStatus, $deleteMessage);

		redirect('/course/index');
	}
	
	public function getCourseSecrecretary($id_course){
		
		$this->load->model('course_model');
		$secretary = $this->course_model->getSecretaryByCourseId($id_course);
		
		return $secretary;
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
