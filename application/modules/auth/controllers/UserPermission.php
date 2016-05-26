<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/Permission.php");
require_once(MODULESPATH."auth/exception/PermissionException.php");

class UserPermission extends MX_Controller {

	const MODEL_NAME = "auth/permissions_model";

    public function __construct(){
        parent::__construct();
        $this->load->model(self::MODEL_NAME);
    }

	/**
     * Get the group permissions
     * @param $group - The groupId to get the permissions
     * @return An array of Permission objects or FALSE if none permissions is found
     */
    public function getGroupPermissions($group){

        $foundPermissions = $this->permissions_model->getGroupPermissions($group);

        if($foundPermissions !== FALSE){

            $permissions = array();
            foreach($foundPermissions as $foundPermission){

                try{
                    $permission = new Permission($foundPermission['id_permission'], $foundPermission['permission_name'], $foundPermission['route']);
                    $permissions[] = $permission;
                }catch(PermissionException $e){
                    PermissionException::handle($e);
                }

            }
        }else{
            $permissions = FALSE;
        }

        return $permissions;
    }

	public function checkUserPermission($requiredPermission){

		$permissionExists = $this->checkIfPermissionRouteExists($requiredPermission);

		if($permissionExists){

			$session = getSession();
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
