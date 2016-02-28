<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("usuario.php");
require_once(APPPATH."/constants/GroupConstants.php");

class Staff extends CI_Controller {

	public function staffsLoadPage() {
		session();
		$staffs = $this->getRegisteredStaffs();
		$guests = $this->getGuestUsers();
		$data = array('staffs' => $staffs, 'guestUsers'=>$guests);
		$this->load->template('staffs/new_staff', $data);
	}

	public function editStaff($id) {
		$this->load->model('staffs_model');
		$staff = @$this->staffs_model->getStaff('id_staff', $id);
		$data = array('staff' => $staff);
		$this->load->template('staffs/edit_staff', $data);
	}

	public function getEmployeeByPartialName($employeeName){

		$this->load->model('staffs_model');

		$employeeName = $this->staffs_model->getEmployeeByPartialName($employeeName);

		return $employeeName;
	}

	public function newStaff() {

		$success = $this->validatesStaffData();

		if ($success) {

			$pis = $this->input->post('pis');
			$idStaff = $this->input->post('staff');
			$registration = $this->input->post('registration');
			$landingDate = $this->input->post('landingDate');
			$address = $this->input->post('address');
			$phone = $this->input->post('telephone');
			$bank = $this->input->post('bank');
			$agency = $this->input->post('agency');
			$accountNumber = $this->input->post('accountNumber');

			$saveData = array(
				'id_user' => $idStaff,
				'pisPasep' => $pis,
				'registration' => $registration,
				'brazil_landing' => $landingDate,
				'address' => $address,
				'telephone' => $phone,
				'bank' => $bank,
				'agency' => $agency,
				'account_number' => $accountNumber
			);

			$this->load->model('staffs_model');
			$staffExiste = $this->staffs_model->getStaff('id_user', $idStaff);

			if ($staffExiste) {
				$this->session->set_flashdata('danger', 'Este funcionário já está cadastrado');
			} else if ($this->staffs_model->saveNewStaff($saveData)) {
				$this->session->set_flashdata('success', "Funcionário \"$nome\" salvo com sucesso");
			}
		}

		redirect("staffs");
	}

	public function updateStaff() {

		$success = $this->validatesStaffData();

		if ($success) {

			$pis = $this->input->post('pis');
			$idUser = $this->input->post('user_id');
			$idStaff = $this->input->post('staff_id');
			$registration = $this->input->post('registration');
			$landingDate = $this->input->post('landingDate');
			$address = $this->input->post('address');
			$phone = $this->input->post('telephone');
			$bank = $this->input->post('bank');
			$agency = $this->input->post('agency');
			$accountNumber = $this->input->post('accountNumber');

			$updateData = array(
				'pisPasep' => $pis,
				'registration' => $registration,
				'brazil_landing' => $landingDate,
				'address' => $address,
				'telephone' => $phone,
				'bank' => $bank,
				'agency' => $agency,
				'account_number' => $accountNumber
			);

			$where = array('id_staff' => $idStaff, 'id_user' => $idUser);

			$this->load->model("staffs_model");
			$updated = $this->staffs_model->updateStaffData($updateData, $where);

			if ($updated) {
				$this->session->set_flashdata("success", "Funcionário alterado.");
				redirect("staffs");
			} else {
				$this->session->set_flashdata("danger", "Este funcionário não pôde ser alterado.");
				redirect("staffs");
			}
		}
	}

	public function remove() {
		session();
		$staff_id = $this->input->post("staff_id");
		$user_id = $this->input->post("id_user");
		$this->load->model("staffs_model");
		$staff = array("id_staff" => $staff_id, "id_user" => $user_id);

		if ($this->staffs_model->remove($staff)) {
			$this->session->set_flashdata("success", "Funcionário foi removido");
		}else{
			$this->session->set_flashdata("danger", "Funcionário não foi removido. Tente novamente");
		}

		redirect("staffs");
	}

	private function validatesStaffData(){

		$this->load->library('form_validation');
		$this->form_validation->set_rules('pis', 'PIS/INSS', 'required|xss_clean|alpha_dash');
		$this->form_validation->set_rules('address', 'Endereço', 'required|trim|xss_clean|callback__alpha_dash_space"');
		$this->form_validation->set_rules('telephone', 'Telefone', 'required');
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		return $success;
	}

	private function getGuestUsers(){

		$user = new Usuario();

		$guests = $user->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID);

		if($guests !== FALSE){
			foreach ($guests as $key => $guest){
				$guestUser[$guest['id']] = $guest['name'];
			}
		}else{
			$guestUser = FALSE;
		}

		return $guestUser;

	}

	private function getRegisteredStaffs(){

		$this->load->model('staffs_model');
		$stagedStaffs = $this->staffs_model->getAllStaffs();

		$user = new Usuario();

		$staffsReturn = array();

		if ($stagedStaffs) {
			foreach ($stagedStaffs as $key => $staff) {
				$staffsReturn[$staff['id_staff']] = $user->getUserById($staff['id_user']);
			}
		}

		return $staffsReturn;
	}

}