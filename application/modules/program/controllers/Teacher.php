<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."program/domain/portal/TeacherInfo.php");

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

		if($teachers !== FALSE || !empty($teachers)){
			
			foreach ($teachers as $id => $teacher) {
				$teacherInfo = $this->convertTeacherInObjects($teacher);
				$teachers[$id] = $teacherInfo;   	
			}
		}

		return $teachers;
	}

	public function convertTeacherInObjects($teacher){
		
		$teacherId = $teacher['id'];
		$name = $teacher['name'];
		$email = $teacher['email'];
	
		$teacherInfo = $this->teacher_model->getInfoTeacherForHomepage($teacherId);
		$summary = $teacherInfo[0]['summary'];
		$latteslink = $teacherInfo[0]['lattes_link'];
		$researchLine = $teacherInfo[0]['research_line'];

		$teacherInfo = new TeacherInfo($teacherId, $name, $email, $summary, $latteslink, $researchLine);

		return $teacherInfo;

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

		if($courses !== FALSE){
			
			foreach ($courses as $course) {
				$id = $course['id_course'];
				$researchLinesCourse = $this->course_model->getCourseResearchLines($id);	 
				if ($researchLinesCourse !== FALSE){
					$researchLines[$id] = $researchLinesCourse;
				}
			}
		}

		return $researchLines;
	}
}