<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Staff extends MX_Controller {

	public function staffsLoadPage() {
		
		$staffs = $this->getRegisteredStaffs();
		$guests = $this->getGuestUsers();
		$data = array('staffs' => $staffs, 'guestUsers'=>$guests);
		loadTemplateSafelyByGroup(GroupConstants::ADMIN_GROUP,'program/staffs/new_staff', $data);
	}

	public function editStaff($id) {
		
		$this->load->model('program/staffs_model');
		$staff = $this->staffs_model->getStaffById($id);

		$this->load->model('auth/usuarios_model');
		$user = $this->usuarios_model->getUserById($staff['id_user']); 
		
		$data = array(
			'staff' => $staff,
			'user' => $user
		);
		loadTemplateSafelyByGroup(GroupConstants::ADMIN_GROUP , 'program/staffs/edit_staff', $data);
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

			$this->load->model('program/staffs_model');
			$staffExiste = $this->staffs_model->getStaffById($idStaff);

			$session = getSession();
			if ($staffExiste) {
				$session->showFlashMessage('danger', 'Este funcionário já está cadastrado');
			} else if ($this->staffs_model->saveNewStaff($saveData)) {
				$session->showFlashMessage('success', "Funcionário salvo com sucesso");
			}
		}

		redirect("staffs");
	}

	public function updateStaff() {

		$success = $this->validatesStaffData();
		$idStaff = $this->input->post('staff_id');
		if ($success) {

			$pis = $this->input->post('pis');
			$idUser = $this->input->post('user_id');
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

			$this->load->model("program/staffs_model");
			$updated = $this->staffs_model->updateStaffData($updateData, $where);

			if ($updated) {
				$status = "success";
				$message = "Funcionário alterado.";
			} 
			else {
				$status = "danger";
				$message = "Este funcionário não pôde ser alterado.";
			}

			$session = getSession();
			$session->showFlashMessage($status, $message);
			redirect("staffs");
		}
		else{
			$this->editStaff($idStaff);
		}
	}

	public function remove() {
		getSession();
		$staff_id = $this->input->post("staff_id");
		$user_id = $this->input->post("id_user");
		$this->load->model("program/staffs_model");
		$staff = array("id_staff" => $staff_id, "id_user" => $user_id);

		if ($this->staffs_model->remove($staff)) {
			$status = "success";
			$message = "Funcionário foi removido";
		}else{
			$status = "danger";
			$message = "Funcionário não foi removido. Tente novamente";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("staffs");
	}

	private function validatesStaffData(){

		$this->load->library('form_validation');
		$this->form_validation->set_rules('pis', 'PIS/INSS', 'required');
		$this->form_validation->set_rules('address', 'Endereço', 'required|trim');
		$this->form_validation->set_rules('telephone', 'Telefone', 'required');
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		return $success;
	}

	private function getGuestUsers(){

		$this->load->model('auth/usuarios_model');
		$guests = $this->usuarios_model->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID);

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

		$this->load->model('program/staffs_model');
		$stagedStaffs = $this->staffs_model->getAllStaffs();

		$this->load->model('auth/usuarios_model');

		$staffsReturn = array();

		if ($stagedStaffs) {
			foreach ($stagedStaffs as $key => $staff) {
				$staffsReturn[$staff['id_staff']] = $this->usuarios_model->getUserById($staff['id_user']);
			}
		}

		return $staffsReturn;
	}

}