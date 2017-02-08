<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller{
	
	public function index(){

		$this->db->select('id, password');
        $users = $this->db->get('users')->result_array();        
 
 		$this->load->module("program/program");
		
		$data = $this->program->getInformationAboutPrograms();
		$isGuest = $this->checkIfUserIsAGuest();
		$data['isGuest'] = $isGuest;
		if($isGuest){
			$this->load->model("program/selectiveprocess_model", "process_model");
			$openSelectiveProcesses = $this->process_model->getOpenSelectiveProcesses();
			$data['openSelectiveProcesses'] = $openSelectiveProcesses;
        	$courses = $this->getCoursesName($openSelectiveProcesses);
			$data['courses'] = $courses;
		}
		$this->load->template('home/home', $data);
	}

	private function checkIfUserIsAGuest(){

		$session = getSession();
		$userData = $session->getUserData();

		$isGuest = FALSE;
		if(!is_null($userData)){

			$groups = $userData->getGroups();
			foreach ($groups as $group) {
				$id = $group->getId();
				if($id == GroupConstants::GUEST_USER_GROUP_ID){
					$isGuest = TRUE;
					break;
				}
			}
		}

		return $isGuest;
	}

	private function getCoursesName($openSelectiveProcesses){
		
		$courses = array();
		if(!empty($openSelectiveProcesses)){
			$this->load->model("program/course_model");
			foreach ($openSelectiveProcesses as $process) {
				$courseId = $process->getCourse();
				$course = $this->course_model->getCourseName($courseId);
				$processId = $process->getId();
				$courses[$processId] = $course; 
			}
		}

		return $courses;
	}
}