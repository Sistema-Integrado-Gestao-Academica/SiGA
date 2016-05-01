<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/security/session/SessionManager.php");
require_once(APPPATH."/constants/PermissionConstants.php");

class Departamento extends CI_Controller {

	public function formulario() {
		$this->load->model("departamentos_model");
		$departamentos = $this->departamentos_model->buscaTodos();
		$dados = array("departamentos" => $departamentos);
		
		loadTemplateSafelyByPermission(PermissionConstants::DEPARTMENTS_PERMISSION , "departamentos/formulario", $dados);
	}

	public function formulario_altera($id) {
		$this->load->model("departamentos_model");
		$departamento = array('id' => $id);
		$departamento = $this->departamentos_model->busca('id', $departamento);
		$dados = array('departamento' => $departamento);
		
		loadTemplateSafelyByPermission(PermissionConstants::DEPARTMENTS_PERMISSION , "departamentos/formulario_altera", $dados);
	}

	public function novo() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome do departamento", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();


		if ($sucesso) {
			$nome = $this->input->post("nome");
			$departamento = array('nome' => $nome);

			$this->load->model("departamentos_model");
			$departamentoExiste = $this->departamentos_model->busca("nome", $departamento);

			$session = SessionManager::getInstance();
			if ($departamentoExiste) {
				$session->showFlashMessage("danger", "Este departamento já está cadastrado");
			} else if ($this->departamentos_model->salva($departamento)) {
				$session->showFlashMessage("success", "Departamento \"$nome\" salvo com sucesso");
			}
		}

		redirect("departamentos");
	}

	public function altera() {
		$id = $this->input->post("departamento_id");
		$nome = $this->input->post("nome");
		$this->load->model("departamentos_model");

		$session = SessionManager::getInstance();
		if ($this->departamentos_model->altera($id, $nome)) {			
			$session->showFlashMessage("success", "Departamento alterado para \"$nome\".");
			redirect("departamentos");
		} else {
			$session->showFlashMessage("danger", "Este departamento não pôde ser alterado.");
			redirect("departamentos/{$id}");
		}
	}

	public function remove() {
		$id = $this->input->post("departamento_id");
		$this->load->model("departamentos_model");
		$departamento = array("id" => $id);
		$departamento = $this->departamentos_model->busca("id", $departamento);

		if ($this->departamentos_model->remove($id)) {
			$session = SessionManager::getInstance();
			$session->showFlashMessage("success", "Departamento \"{$departamento['nome']}\" foi removido");
		}

		redirect("departamentos");
	}
}
