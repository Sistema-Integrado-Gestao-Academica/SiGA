<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."/constants/GroupConstants.php");
require_once("usuario.php");
class Staff extends CI_Controller {

	public function staffsLoadPage() {
		session();
		$this->load->model('staffs_model');
		$staffs = $this->staffs_model->getAllStaffs();
		$users = $this->getGuestUsers();
		$data = array('staffs' => $staffs, 'users'=>$users);
		$this->load->template('staffs/new_staff', $data);
	}

	public function formulario_altera($id) {
		session();
		$this->load->model('staffs_model');
		$staff = array('id' => $id);
		$staff = $this->staffs_model->busca('id', $staff);
		$data = array('funcionario' => $staff);
		$this->load->template('funcionarios/funcionario_altera', $data);
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

	public function altera() {
		session();
		$id = $this->input->post("funcionario_id");
		$nome = $this->input->post("nome");
		$this->load->model("staffs_model");

		if ($this->staffs_model->altera($id, $nome)) {			
			$this->session->set_flashdata("success", "Funcionário alterado para \"$nome\".");
			redirect("staffs");
		} else {
			$this->session->set_flashdata("danger", "Este funcionário não pôde ser alterado.");
			redirect("funcionarios/{$id}");
		}
	}

	public function remove() {
		session();
		$id = $this->input->post("funcionario_id");
		$this->load->model("staffs_model");
		$staff = array("id" => $id);
		$staff = $this->staffs_model->busca("id", $staff);

		if ($this->staffs_model->remove($id)) {
			$this->session->set_flashdata("success", "Funcionário \"{$staff['nome']}\" foi removido");
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

		foreach ($guests as $key => $guest){
			$guestUser[$guest['id']] = $guest['name'];
		}

		return $guestUser;

	}

}

/* End of file funcionario.php */
/* Location: ./application/controllers/funcionario.php */ 