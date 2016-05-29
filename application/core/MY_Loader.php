<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
    
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

	/**
	 * Loads the test header + content of page + footer at once to display. Used for tests report
	 * @param $pageContent - The page content to load between header and footer
	 * @param $data - Data to sent to the requested view
	 * @return void
	 */
	public function test_template($pageContent, $data = array()) {
		$this->view("test/test_header.php");
		$this->view($pageContent, $data);
		$this->view("test/test_footer.php");
	}
}
