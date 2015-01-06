<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setor extends CI_Controller {

	public function formulario() {
		session();
		$this->load->model("setores_model");
		$setores = $this->setores_model->buscaTodos();
		$dados = array("setores" => $setores);
		$this->load->template("setores/formulario", $dados);
	}

	public function formulario_altera($id) {
		session();
		$this->load->model("setores_model");
		$setor = array('id' => $id);
		$setor = $this->setores_model->busca('id', $setor);
		$dados = array('setor' => $setor);
		$this->load->template("setores/formulario_altera", $dados);
	}

	public function novo() {
		$usuarioLogado = session();
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome do setor", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();

		if ($sucesso) {
			$usuarioLogado = session();
			$nome = $this->input->post("nome");
			$setor = array('nome' => $nome);

			$this->load->model("setores_model");
			$setorExiste = $this->setores_model->busca("nome", $setor);

			if ($setorExiste) {
				$this->session->set_flashdata("danger", "Este setor já está cadastrado");
			} else if ($this->setores_model->salva($setor)) {
				$this->session->set_flashdata("success", "Setor \"$nome\" salvo com sucesso");
			}
		}

		redirect("setores");
	}

	public function altera() {
		session();
		$id = $this->input->post("setor_id");
		$nome = $this->input->post("nome");
		$this->load->model("setores_model");

		if ($this->setores_model->altera($id, $nome)) {			
			$this->session->set_flashdata("success", "Setor alterado para \"$nome\".");
			redirect("setores");
		} else {
			$this->session->set_flashdata("danger", "Este setor não pôde ser alterado.");
			redirect("setores/{$id}");
		}
	}

	public function remove() {
		session();
		$id = $this->input->post("setor_id");
		$this->load->model("setores_model");
		$setor = array("id" => $id);
		$setor = $this->setores_model->busca("id", $setor);

		if ($this->setores_model->remove($id)) {
			$this->session->set_flashdata("success", "Setor \"{$setor['nome']}\" foi removido");
		}

		redirect("setores");
	}
}
