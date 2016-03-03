<?php

require_once(APPPATH."/exception/security/GroupException.php");

class Group{

    const INVALID_ID = "O ID do grupo informado é inválido. O ID deve ser maior que zero.";
    const INVALID_NAME = "O nome do grupo deve ser uma String.";
    const INVALID_PROFILE_ROUTE = "O rota do perfil do grupo deve ser uma String.";
    const INVALID_PERMISSIONS = "Um grupo deve conter um array de objetos da classe Permission.";

    private $id;
    private $name;
    private $profileRoute;
    private $permissions;

    public function __construct($id = FALSE, $name = FALSE, $profileRoute = FALSE, $permissions = FALSE){

        $this->setId($id);
        $this->setName($name);
        $this->setProfileRoute($profileRoute);
        $this->setPermissions($permissions);
    }

    /**
     * Adds a permission to the group
     * @param $permission - The permission object to be added
     * @throws GroupException if the given permission is not a Permission object
     */
    public function addPermission($permission){
        if(get_class($permission) == Permission::class){
            $this->permissions[] = $permission;
        }else{
            throw new GroupException(self::INVALID_PERMISSIONS);
        }
    }

    private function setId($id){

        // Id must be a number or a string number
        if(is_numeric($id)){
            // Id must be greater than zero
            if($id > 0){
                $this->id = $id;
            }else{
                throw new GroupException(self::INVALID_ID);
            }
        }else{
            throw new GroupException(self::INVALID_ID);
        }
    }

    private function setName($name){

        if(is_string($name)){
            $this->name = $name;
        }else{
            throw new GroupException(self::INVALID_NAME);
        }
    }

    private function setProfileRoute($profileRoute){

        if(is_string($profileRoute)){
            $this->profileRoute = $profileRoute;
        }else{
            throw new GroupException(self::INVALID_PROFILE_ROUTE);
        }
    }

    private function setPermissions($permissions){

        if($permissions !== FALSE){

            if(is_array($permissions)){

                $isAPermission = TRUE;
                foreach ($permissions as $permission){
                    if(!(get_class($permission) == Permission::class) ){
                        $isAPermission = FALSE;
                        break;
                    }
                }

                if($isAPermission){
                    $this->permissions = $permissions;
                }else{
                    throw new GroupException(self::INVALID_PERMISSIONS);
                }
            }else{
                throw new GroupException(self::INVALID_PERMISSIONS);
            }
        }else{
            $this->permissions = array();
        }
    }
}