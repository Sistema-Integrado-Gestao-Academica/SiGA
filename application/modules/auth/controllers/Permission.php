<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("SessionManager.php");

class PermissionOld extends MX_Controller {

	public function checkUserPermission($requiredPermission){

		$permissionExists = $this->checkIfPermissionRouteExists($requiredPermission);

		if($permissionExists){

			$session = SessionManager::getInstance();
			$userPermissions = $session->getUserPermissions();

			$havePermission = FALSE;
			foreach($userPermissions as $group => $permissions){

				foreach($permissions as $permission){

					if($permission->getFunctionality() === $requiredPermission){
						$havePermission = TRUE;
						break;
					}
				}

				if($havePermission){
					break;
				}
			}

		}else{
			$havePermission = FALSE;
		}

		return $havePermission;
	}

	private function checkIfPermissionRouteExists($permission){
	
		$this->load->model('auth/permissions_model');
		$allPermissions = $this->permissions_model->getAllPermissionsRoutes();

		$permissionExists = FALSE;
		foreach($allPermissions as $idPermission => $permissionRoute){
			if($permissionRoute === $permission){
				$permissionExists = TRUE;
				break;
			}
		}

		return $permissionExists;
	}

}
