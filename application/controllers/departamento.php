<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departamento extends CI_Controller {

	public function formulario() {
		$this->load->model("departamentos_model");
		$departamentos = $this->departamentos_model->buscaTodos();
		$dados = array("departamentos" => $departamentos);
		$this->load->template("departamentos/formulario", $dados);
	}

	public function formulario_altera($id) {
		session();
		$this->load->model("departamentos_model");
		$departamento = array('id' => $id);
		$departamento = $this->departamentos_model->busca('id', $departamento);
		$dados = array('departamento' => $departamento);
		$this->load->template("departamentos/formulario_altera", $dados);
	}

	public function novo() {
		$usuarioLogado = session();
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome do departamento", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();


		if ($sucesso) {
			$usuarioLogado = session();
			$nome = $this->input->post("nome");
			$departamento = array('nome' => $nome);

			$this->load->model("departamentos_model");
			$departamentoExiste = $this->departamentos_model->busca("nome", $departamento);

			if ($departamentoExiste) {
				$this->session->set_flashdata("danger", "Este departamento já está cadastrado");
			} else if ($this->departamentos_model->salva($departamento)) {
				$this->session->set_flashdata("success", "Departamento \"$nome\" salvo com sucesso");
			}
		}

		redirect("departamentos");
	}

	public function altera() {
		session();
		$id = $this->input->post("departamento_id");
		$nome = $this->input->post("nome");
		$this->load->model("departamentos_model");

		if ($this->departamentos_model->altera($id, $nome)) {			
			$this->session->set_flashdata("success", "Departamento alterado para \"$nome\".");
			redirect("departamentos");
		} else {
			$this->session->set_flashdata("danger", "Este departamento não pôde ser alterado.");
			redirect("departamentos/{$id}");
		}
	}

	public function remove() {
		session();
		$id = $this->input->post("departamento_id");
		$this->load->model("departamentos_model");
		$departamento = array("id" => $id);
		$departamento = $this->departamentos_model->busca("id", $departamento);

		if ($this->departamentos_model->remove($id)) {
			$this->session->set_flashdata("success", "Departamento \"{$departamento['nome']}\" foi removido");
		}

		redirect("departamentos");
	}
}
