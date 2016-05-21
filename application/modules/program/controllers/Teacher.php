<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Teacher extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('program/teacher_model');
		$this->load->model('program/course_model');
	}

	public function updateProfile(){
		
		$session = getSession();
		$user = $session->getUserData();
		$teacher = $user->getId();

		$infoProfile = $this->getInfoProfile($teacher);
		
		loadTemplateSafelyByGroup(GroupConstants::TEACHER_GROUP, 'program/teacher/update_profile', $infoProfile);
	}

	public function saveProfile(){
		
		$teacherId = $this->input->post('teacher');

		$summary = $this->input->post('summaryField');
		$lattes = $this->input->post('lattesField');
		$availableResearchLines = $this->input->post('researchLines');
		$researchLineId = $this->input->post('researchLineField');
		$researchLine = $availableResearchLines[$researchLineId];

		$teacherData = array(
			'summary' => $summary,
			'lattes_link' => $lattes,
			'research_line' => $researchLine,
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


	/**
		Get the teacher info for homepage based on program courses
	*/
	public function getCourseTeachersForHomepage($courseId){

		$teachers = $this->course_model->getTeachers($courseId);
		$teachersInfo = array();
		$teachersData = array();

		if($teachers !== FALSE || !empty($teachers)){
			
			foreach ($teachers as $teacher) {
				$teacherId = $teacher['id_user'];
				$teachersInfo[$teacherId]['extra_data'] = $this->teacher_model->getInfoTeacherForHomepage($teacherId);
				$teachersInfo[$teacherId]['basic_data'] = $this->teacher_model->getTeacherData($teacherId);

				$teacherInfo = $teachersInfo[$teacherId];
				$teachersData[$teacherId]['id'] = $teacherInfo['basic_data'][0]['id']; 
				$teachersData[$teacherId]['name'] = $teacherInfo['basic_data'][0]['name']; 
				$teachersData[$teacherId]['email'] = $teacherInfo['basic_data'][0]['email'];
				$teachersData[$teacherId]['summary'] = $teacherInfo['extra_data'][0]['summary'];
				$teachersData[$teacherId]['lattes_link'] = $teacherInfo['extra_data'][0]['lattes_link'];
				$teachersData[$teacherId]['research_line'] = $teacherInfo['extra_data'][0]['research_line'];		
		               	
			}
		}

		return $teachersData;
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
			$researchLine = "";
		}

		$availableResearchLines = $this->getAvailableResearchLines($teacherId, $researchLine);
		$data = array(
			'teacher' => $teacherId,
			'summary' => $summary,
			'lattes' => $lattes,
			'researchLine' => $researchLine, # Research line of the teacher
			'availableResearchLines' => $availableResearchLines # Possible research lines for the teacher
		);
		
		return $data;
	}

	private function getAvailableResearchLines($teacherId, $currentResearchLine){

		$courses = $this->teacher_model->getTeacherCourses($teacherId);

		$researchLines = $this->getTeacherResearchLinesInfo($courses);
		$availableResearchLines = array();
	
		$id = 0;
		// Put the current research line on top of dropdown
		if (!empty($currentResearchLine)){
			$availableResearchLines[$id] = $currentResearchLine;			
			$id++;
		}
		if($researchLines !== FALSE){
			

			foreach ($researchLines as $researchLine) {
				$researchLineLength = count($researchLine);

				for ($i = 0; $i < $researchLineLength; $i++){
					$researchLineInfo = $researchLine[$i];
					$researchLineId = $researchLineInfo['id_research_line'];
					$description = $researchLineInfo['description'];
					if($description != $currentResearchLine){
						$availableResearchLines[$id] = $description;
						$id++;
					}			
				}

			}
		}

		return $availableResearchLines;
	}

	private function getTeacherResearchLinesInfo($courses){
		
		$researchLines = array();

		foreach ($courses as $course) {
			$id = $course['id_course'];
			$researchLinesCourse = $this->course_model->getCourseResearchLines($id);	 
			if ($researchLinesCourse !== FALSE){
				$researchLines[$id] = $researchLinesCourse;
			}
		}

		return $researchLines;
	}
}