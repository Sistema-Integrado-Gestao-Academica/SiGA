<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syllabus extends CI_Controller {

	public function getCourseSyllabus($courseId){
		
		$this->load->model('syllabus_model');
		
		$courseSyllabus = $this->syllabus_model->getCourseSyllabus($courseId);

		return $courseSyllabus;
	}

	public function newSyllabus($courseId){

		$this->load->model('syllabus_model');

		$wasSaved = $this->syllabus_model->newSyllabus($courseId);

		if($wasSaved){
			$status = "success";
			$message = "Currículo criado com sucesso. Adicione disciplinas em EDITAR.";
		}else{
			$status = "danger";
			$message = "Não foi possível criar o currículo para o curso informado. Tente novamente.";
		}
		
		$this->session->set_flashdata($status, $message);	
		redirect('usuario/secretary_courseSyllabus');
	}

}
