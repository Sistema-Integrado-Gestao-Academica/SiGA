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

	/**
	 * Loads a service.
	 *
	 * Services must be placed in a folder called 'services' under
	 * the application folder or in a module folder.
	 *
	 * @param $service - The service class to load
	 * @param $name - An alias name to access the service
	 * @return void
	 */
	public function service($service, $name='') {
		$lastSlash = strrpos($service, '/');

		// Is in Modules folder
		if($lastSlash){
			$path = substr($service, 0, ++$lastSlash);
			$serviceName = substr($service, $lastSlash);
			$path = MODULESPATH.$path;
		}
		// Is in Application root folder
		else{
			$path = APPPATH;
			$serviceName = $service;
		}

		if(empty($name)){
			$name = $serviceName;
		}

		$fileName = $path.'services/'.$serviceName.'.php';
		if(file_exists($fileName)){
			require_once($fileName);
			$CI =& get_instance();
			$CI->$name = new $serviceName();
		}else{
			throw new RunTimeException("Unable to locate the service you requested: ".$serviceName);
		}
	}

}
