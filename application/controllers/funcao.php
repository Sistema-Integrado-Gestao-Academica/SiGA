<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/security/session/SessionManager.php");
require_once(APPPATH."/constants/PermissionConstants.php");

class Funcao extends CI_Controller {

	public function formulario() {
		$this->load->model("funcoes_model");
		$funcoes = $this->funcoes_model->buscaTodos();
		$dados = array("funcoes" => $funcoes);
		
		loadTemplateSafelyByPermission(PermissionConstants::FUNCTIONS_PERMISSION, "funcoes/formulario", $dados);
	}

	public function formulario_altera($id) {
		$this->load->model("funcoes_model");
		$funcao = array('id' => $id);
		$funcao = $this->funcoes_model->busca('id', $funcao);
		$dados = array('funcao' => $funcao);
		
		loadTemplateSafelyByPermission(PermissionConstants::FUNCTIONS_PERMISSION, "funcoes/formulario_altera", $dados);
	}

	public function novo() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome da função", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();


		if ($sucesso) {
			$nome = $this->input->post("nome");
			$funcao = array('nome' => $nome);

			$this->load->model("funcoes_model");
			$funcaoExiste = $this->funcoes_model->busca("nome", $funcao);

			$session = SessionManager::getInstance();
			if ($funcaoExiste) {
				$session->showFlashMessage("danger", "Esta função já está cadastrada");
			} else if ($this->funcoes_model->salva($funcao)) {
				$session->showFlashMessage("success", "Função \"$nome\" salvo com sucesso");
			}
		}

		redirect("funcoes");
	}

	public function altera() {
		$id = $this->input->post("funcao_id");
		$nome = $this->input->post("nome");
		$this->load->model("funcoes_model");

		$session = SessionManager::getInstance();
		if ($this->funcoes_model->altera($id, $nome)) {			
			$session->showFlashMessage("success", "Função alterada para \"$nome\".");
			redirect("funcoes");
		} else {
			$session->showFlashMessage("danger", "Esta função não pôde ser alterada.");
			redirect("funcoes/{$id}");
		}
	}

	public function remove() {
		$id = $this->input->post("funcao_id");
		$this->load->model("funcoes_model");
		$funcao = array("id" => $id);
		$funcao = $this->funcoes_model->busca("id", $funcao);

		$session = SessionManager::getInstance();
		if ($this->funcoes_model->remove($id)) {
			$session->showFlashMessage("success", "Função \"{$funcao['nome']}\" foi removida");
		}

		redirect("funcoes");
	}
}
