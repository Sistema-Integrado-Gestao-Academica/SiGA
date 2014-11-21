<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

	/**
	 * Loads the header + content of page + footer at once to display
	 * @param
	 * @param
	 * @return
	 */
	public function template($nome, $dados = array()) {
		$this->view("header.php");
		$this->view($nome, $dados);
		$this->view("footer.php");
	}
}