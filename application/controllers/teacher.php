<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/GroupConstants.php");

class Teacher extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('teacher_model');
		$this->load->model('course_model');
	}

	public function updateProfile(){
		
		$session = $this->session->userdata("current_user");
		$teacher = $session['user']['id'];
		$infoProfile = $this->getInfoProfile($teacher);
		
		loadTemplateSafelyByGroup(GroupConstants::TEACHER_GROUP, 'teacher/update_profile', $infoProfile);
	}

	public function saveProfile(){
		
		$teacherId = $this->input->post('teacher');

		$summary = $this->input->post('summaryField');
		$lattes = $this->input->post('lattesField');
		
		$teacherData = array(
			'summary' => $summary,
			'lattes_link' => $lattes,
			'id_user' => $teacherId
		);

		$wasUpdated = $this->teacher_model->updateProfile($teacherData);

		if($wasUpdated){
			$insertStatus = "success";
			$insertMessage = "Perfil atualizado com sucesso!";
			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('mastermind_home');
		}
		else{
			$insertStatus = "danger";
			$insertMessage = "Não foi possível atualizar o perfil. Tente novamente.";
			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('update_profile');
		}

	}


	public function getCourseTeachersForHomepage($courseId){

		$teachers = $this->course_model->getCourseTeachers($courseId);
		$teachersInfo = array();

		if($teachers !== FALSE){
			
			foreach ($teachers as $teacher) {
				$teacherId = $teacher['id_user'];
				$teachersInfo[$teacherId] = $this->teacher_model->getInfoTeacherForHomepage($teacherId);
			}
		}

		return $teachersInfo;
	}

	public function getInfoProfile($teacherId){

		$teacher = array('id_user' => $teacherId);
		$teacherProfile = $this->teacher_model->getTeacherProfile($teacher);
		
		if($teacherProfile !== FALSE && !empty($teacherProfile)){
			
			$summary = $teacherProfile['summary'];
			$lattes = $teacherProfile['lattes_link'];
			$researchLine = $teacherProfile['research_line'];
		}	
		else{
			
			$summary = "";	
			$lattes = "";
		}

		$availableResearchLines = $this->getAvailableResearchLines($teacherId);
		$data = array(
			'teacher' => $teacherId,
			'summary' => $summary,
			'lattes' => $lattes,
			'researchLine' => $researchLine, # Research line of the teacher
			'availableResearchLines' => $availableResearchLines # Possible research lines for the teacher
		);
		
		return $data;
	}

	private function getAvailableResearchLines($teacherId){

		$courses = $this->teacher_model->getTeacherCourses($teacherId);

		$researchLines = array();
		$availableResearchLines = array();
	
		foreach ($courses as $course) {
			$id = $course['id_course'];
			$researchLinesCourse = $this->course_model->getCourseResearchLines($id);	 
			if ($researchLinesCourse !== FALSE){
				$researchLines[$id] = $researchLinesCourse;
			}
		}

		$id = 0;
		foreach ($researchLines as $researchLine) {
			$researchLineLength = count($researchLine);

			for ($i = 0; $i < $researchLineLength; $i++){
				$researchLineInfo = $researchLine[$i];
				$availableResearchLines[$id] = $researchLineInfo['description'];			
				$id++;
			}

		}

		return $availableResearchLines;
	}
}