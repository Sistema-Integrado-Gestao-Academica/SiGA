<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/security/session/SessionManager.php");
require_once(APPPATH."/constants/PermissionConstants.php");

class Setor extends CI_Controller {

	public function formulario() {
		$this->load->model("setores_model");
		$setores = $this->setores_model->buscaTodos();
		$dados = array("setores" => $setores);

		loadTemplateSafelyByPermission(PermissionConstants::SECTOR_PERMISSION, "setores/formulario", $dados);
	}

	public function formulario_altera($id) {
		$this->load->model("setores_model");
		$setor = array('id' => $id);
		$setor = $this->setores_model->busca('id', $setor);
		$dados = array('setor' => $setor);

		loadTemplateSafelyByPermission(PermissionConstants::SECTOR_PERMISSION, "setores/formulario_altera", $dados);
	}

	public function novo() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome do setor", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();

		if ($sucesso) {
			$nome = $this->input->post("nome");
			$setor = array('nome' => $nome);

			$this->load->model("setores_model");
			$setorExiste = $this->setores_model->busca("nome", $setor);

			$session = SessionManager::getInstance();
			if ($setorExiste) {
				$session->showFlashMessage("danger", "Este setor já está cadastrado");
			} 
			else if ($this->setores_model->salva($setor)) {
				$session->showFlashMessage("success", "Setor \"$nome\" salvo com sucesso");
			}
		}

		redirect("setores");
	}

	public function altera() {
		$id = $this->input->post("setor_id");
		$nome = $this->input->post("nome");
		$this->load->model("setores_model");

		$session = SessionManager::getInstance();
		if ($this->setores_model->altera($id, $nome)) {			
			$session->showFlashMessage("success", "Setor alterado para \"$nome\".");
			redirect("setores");
		} else {
			$session->showFlashMessage("danger", "Este setor não pôde ser alterado.");
			redirect("setores/{$id}");
		}
	}

	public function remove() {
		$id = $this->input->post("setor_id");
		$this->load->model("setores_model");
		$setor = array("id" => $id);
		$setor = $this->setores_model->busca("id", $setor);

		if ($this->setores_model->remove($id)) {
			$session = SessionManager::getInstance();
			$session->showFlashMessage("success", "Setor \"{$setor['nome']}\" foi removido");
		}

		redirect("setores");
	}
}
