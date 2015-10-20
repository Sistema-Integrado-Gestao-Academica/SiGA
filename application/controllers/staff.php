<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends CI_Controller {

	public function staffsLoadPage() {
		session();
		$this->load->model('staffs_model');
		$staffs = $this->staffs_model->getAllStaffs();
		$data = array('staffs' => $staffs);
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

	public function novo() {
		$usuarioLogado = session();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nome', 'Nome do funcionário', 'required');
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();

		if ($sucesso) {
			$usuarioLogado = session();
			$nome = $this->input->post('nome');
			$staff = array('nome' => $nome);

			$this->load->model('staffs_model');
			$staffExiste = $this->staffs_model->busca('nome', $staff);

			if ($staffExiste) {
				$this->session->set_flashdata('danger', 'Este funcionário já está cadastrado');
			} else if ($this->staffs_model->salva($staff)) {
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

}

/* End of file funcionario.php */
/* Location: ./application/controllers/funcionario.php */ 