<?php

require_once ('usuario.php');
require_once ('course.php');
require_once APPPATH."/data_types/notification/emails/SecretaryEmailNotification.php";
require_once APPPATH."/exception/EmailNotificationException.php";
require_once APPPATH."/constants/EmailConstants.php";
require_once APPPATH."/constants/GroupConstants.php";

class SecretaryEmail extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("usuarios_model");
		$this->load->model("documentrequest_model");
		$this->input->is_cli_request();
	}

	public function sendEmail(){

		$secretaries = $this->usuarios_model->getAllSecretaries();
		if($secretaries != FALSE){
			foreach ($secretaries as $id => $secretarie) {				
				$user = $this->usuarios_model->getUserById($id);
				$user = $this->usuarios_model->getUserDataForEmail($user);

				$quantityOfGuestUsers = $this->getQuantityOfGuestUsers();
				$quantityOfDocumentsRequest = $this->getQuantityOfDocumentsRequest($id);
				
				$notifySecretary = new SecretaryEmailNotification($user, $quantityOfGuestUsers, $quantityOfDocumentsRequest);
				$notifySecretary->notify();
			}
		}
	}

	private function getQuantityOfDocumentsRequest($userId){

		$course = new Course();
		$courses = $course->getCoursesOfSecretary($userId);

		$quantityOfDocumentsRequest = 0;
		if($courses !== FALSE){
			foreach ($courses as $course) {
				$courseId = $course['id_course'];
				$documents = $this->documentrequest_model->getCourseRequests($courseId);
				if($documents !== FALSE){
					$quantityOfDocumentsRequest += count($documents);
				}
				else{
					$quantityOfDocumentsRequest += 0;
				}
			}
		}


		return $quantityOfDocumentsRequest;
	}

	private function getQuantityOfGuestUsers(){
		$users = new Usuario();
		$guests = $users->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID);
		
		if($guests !== FALSE){
			$quantityOfGuestUsers = count($guests);
		}
		else{
			$quantityOfGuestUsers = 0;
		}

		return $quantityOfGuestUsers;
	}

	
}
?>