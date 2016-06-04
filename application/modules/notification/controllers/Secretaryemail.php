<?php

require_once MODULESPATH."notification/domain/emails/SecretaryEmailNotification.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."auth/constants/GroupConstants.php";

class SecretaryEmail extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("auth/usuarios_model");
		$this->load->model("secretary/documentrequest_model");
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

		$this->load->model("program/course_model");
		$courses = $this->course_model->getCoursesOfSecretary($userId);

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
		
		$guests = $this->usuarios_model->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID);
		
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