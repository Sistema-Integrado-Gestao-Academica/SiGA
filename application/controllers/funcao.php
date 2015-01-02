<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funcao extends CI_Controller {

	public function formulario() {
		$this->load->model("funcoes_model");
		$funcoes = $this->funcoes_model->buscaTodos();
		$dados = array("funcoes" => $funcoes);
		$this->load->template("funcoes/formulario", $dados);
	}

	public function formulario_altera($id) {
		session();
		$this->load->model("funcoes_model");
		$funcao = array('id' => $id);
		$funcao = $this->funcoes_model->busca('id', $funcao);
		$dados = array('funcao' => $funcao);
		$this->load->template("funcoes/formulario_altera", $dados);
	}

	public function novo() {
		$usuarioLogado = session();
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome da função", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();


		if ($sucesso) {
			$usuarioLogado = session();
			$nome = $this->input->post("nome");
			$funcao = array('nome' => $nome);

			$this->load->model("funcoes_model");
			$funcaoExiste = $this->funcoes_model->busca("nome", $funcao);

			if ($funcaoExiste) {
				$this->session->set_flashdata("danger", "Esta função já está cadastrada");
			} else if ($this->funcoes_model->salva($funcao)) {
				$this->session->set_flashdata("success", "Função \"$nome\" salvo com sucesso");
			}
		}

		redirect("funcoes");
	}

	public function altera() {
		session();
		$id = $this->input->post("funcao_id");
		$nome = $this->input->post("nome");
		$this->load->model("funcoes_model");

		if ($this->funcoes_model->altera($id, $nome)) {			
			$this->session->set_flashdata("success", "Função alterada para \"$nome\".");
			redirect("funcoes");
		} else {
			$this->session->set_flashdata("danger", "Esta função não pôde ser alterada.");
			redirect("funcoes/{$id}");
		}
	}

	public function remove() {
		session();
		$id = $this->input->post("funcao_id");
		$this->load->model("funcoes_model");
		$funcao = array("id" => $id);
		$funcao = $this->funcoes_model->busca("id", $funcao);

		if ($this->funcoes_model->remove($id)) {
			$this->session->set_flashdata("success", "Função \"{$funcao['nome']}\" foi removida");
		}

		redirect("funcoes");
	}
}
