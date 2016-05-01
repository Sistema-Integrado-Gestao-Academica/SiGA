<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/GroupConstants.php");

class Settings extends CI_Controller {

	public function index() {
		$semester = new Semester();
		$current_semester = $semester->getCurrentSemester();

		$group = new Module();
		$edit = $group->checkUserGroup(GroupConstants::ADMIN_GROUP);
		
		$data = array('current_semester' => $current_semester, 'edit' => $edit);
		$this->load->template('settings/index', $data);
	}

	public function saveSemester() {

		$session = SessionManager::getInstance();
		
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
			$session->showFlashMessage("success", "Semestre corrente alterado");
		}
		redirect('configuracoes');
	}

}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */