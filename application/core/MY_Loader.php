<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

	/**
	 * Loads the header + content of page + footer at once to display
	 * @param $pageContent - The page content to load between header and footer
	 * @param $data - Data to sent to the requested view
	 * @return void
	 */
	public function template($pageContent, $data = array()) {
		$this->view("header.php");
		$this->view($pageContent, $data);
		$this->view("footer.php");
	}

}
