<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funcionario extends CI_Controller {

	public function formulario() {
		autoriza();
		$this->load->model('funcionarios_model');
		$funcionarios = $this->funcionarios_model->buscaTodos();
		$dados = array('funcionarios' => $funcionarios);
		$this->load->template('funcionarios/formulario', $dados);
	}

	public function formulario_altera($id) {
		autoriza();
		$this->load->model('funcionarios_model');
		$funcionario = array('id' => $id);
		$funcionario = $this->funcionarios_model->busca('id', $funcionario);
		$dados = array('funcionario' => $funcionario);
		$this->load->template('funcionarios/funcionario_altera', $dados);
	}

	public function novo() {
		$usuarioLogado = autoriza();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nome', 'Nome do funcionário', 'required');
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();

		if ($sucesso) {
			$usuarioLogado = autoriza();
			$nome = $this->input->post('nome');
			$funcionario = array('nome' => $nome);

			$this->load->model('funcionarios_model');
			$funcionarioExiste = $this->funcionarios_model->busca('nome', $funcionario);

			if ($funcionarioExiste) {
				$this->session->set_flashdata('danger', 'Este funcionário já está cadastrado');
			} else if ($this->funcionarios_model->salva($funcionario)) {
				$this->session->set_flashdata('success', "Funcionário \"$nome\" salvo com sucesso");
			}
		}

		redirect("funcionarios");
	}

	public function altera() {
		autoriza();
		$id = $this->input->post("funcionario_id");
		$nome = $this->input->post("nome");
		$this->load->model("funcionarios_model");

		if ($this->funcionarios_model->altera($id, $nome)) {			
			$this->session->set_flashdata("success", "Funcionário alterado para \"$nome\".");
			redirect("funcionarios");
		} else {
			$this->session->set_flashdata("danger", "Este funcionário não pôde ser alterado.");
			redirect("funcionarios/{$id}");
		}
	}

	public function remove() {
		autoriza();
		$id = $this->input->post("funcionario_id");
		$this->load->model("funcionarios_model");
		$funcionario = array("id" => $id);
		$funcionario = $this->funcionarios_model->busca("id", $funcionario);

		if ($this->funcionarios_model->remove($id)) {
			$this->session->set_flashdata("success", "Funcionário \"{$funcionario['nome']}\" foi removido");
		}

		redirect("funcionarios");
	}

}

/* End of file funcionario.php */
/* Location: ./application/controllers/funcionario.php */ 