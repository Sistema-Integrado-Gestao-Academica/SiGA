<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Settings extends MX_Controller {

	public function index() {
		$this->load->model("program/semester_model");
		$current_semester = $this->semester_model->getCurrentSemester();

		$this->load->module("auth/module");
		$edit = $this->module->checkUserGroup(GroupConstants::ADMIN_GROUP);
		
		$data = array('current_semester' => $current_semester, 'edit' => $edit);
		loadTemplateSafelyByGroup(GroupConstants::ADMIN_GROUP, 'program/settings/index', $data);
	}

	public function saveSemester() {

		$session = getSession();
		
		$user = $session->getUserData();
		$id_user = $user->getId();
		$current_user = $this->db->get_where('users', array('id'=>$id_user))->row_array();

		$password = md5($this->input->post('password'));
		if ($password != $current_user['password']) {
			$session->showFlashMessage("danger", "Senha incorreta");
			redirect('configuracoes');
		}

		$semester_id = $this->input->post('current_semester_id') + 1;
		$object = array('id_semester' => $semester_id);
		if ($this->db->update('current_semester', $object)) {
			$this->load->model("secretary/offer_model");
			$this->offer_model->updatePlannedOffers($semester_id);
			$session->showFlashMessage("success", "Semestre corrente alterado");
		}
		redirect('configuracoes');
	}

}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */