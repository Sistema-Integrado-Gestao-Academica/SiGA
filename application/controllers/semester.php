<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Semester extends CI_Controller {

	public function getCurrentSemester(){

		$this->load->model('semester_model');

		$currentSemester = $this->semester_model->getCurrentSemester();

		return $currentSemester;
	}

	public function saveSemester() {
		
		$id_user = session()['user']['id'];
		$current_user = $this->db->get_where('users', array('id'=>$id_user))->row_array();

		$password = md5($this->input->post('password'));
		if ($password != $current_user['password']) {
			$this->session->set_flashdata("danger", "Senha incorreta");
			redirect('/cursos/');
		}

		$semester_id = $this->input->post('current_semester_id') + 1;
		$object = array('id_semester' => $semester_id);
		if ($this->db->update('current_semester', $object)) {
			$this->session->set_flashdata("success", "Semestre corrente alterado");
		}
		redirect('/usuario/secretary_offerList');
	}

}
