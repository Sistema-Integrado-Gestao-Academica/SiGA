<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Controller {

	// CRUD

	/**
	  * Check the modules registered to an user
	  * @param $user_id - User id to check the modules
	  * @return 
	  */
	public function checkModules($user_id){

		$this->load('module_model');
		$registered_modules = $this->module_model->getUserModulesNames($user_id);

		var_dump($registered_modules);

		return $registered_modules;
	}

}
