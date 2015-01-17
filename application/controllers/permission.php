<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends CI_Controller {

	public function getAllPermissionsRoutes(){
		$this->load->model('permission_model');
		$permissionsRoutes = $this->permission_model->getAllPermissionsRoutes();

		return $permissionsRoutes;
	}

}
