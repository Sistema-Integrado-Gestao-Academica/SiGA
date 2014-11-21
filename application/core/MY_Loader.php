<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

	public function __construct(){
	        parent::__construct();
	}

	public function template($nome, $dados = array()) {
		$this->view("cabecalho.php");
		$this->view($nome, $dados);
		$this->view("rodape.php");
	}

}
