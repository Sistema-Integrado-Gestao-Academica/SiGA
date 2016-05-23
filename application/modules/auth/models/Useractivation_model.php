<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserActivation_model extends CI_Model {

	const ACTIVATION_TABLE = "user_activation";

	const USER_COLUMN = "id_user";
	const ACTIVATION_COLUMN = "activation";

	public function saveActivation($user, $activation){

		$userId = $user['id'];

		$newActivation = array(
			self::USER_COLUMN => $userId,
			self::ACTIVATION_COLUMN => $activation
		);

		$saved = $this->db->insert(self::ACTIVATION_TABLE, $newActivation);

		return $saved;
	}

	public function activationExists($activation){

		$foundActivation = $this->get(self::ACTIVATION_COLUMN, $activation);

		if($foundActivation !== FALSE){
			$exists = TRUE;
		}else{
			$exists = FALSE;
		}

		return $exists;
	}

	public function confirmRegister($userId, $activationKey){

		$foundActivation = $this->get(array(
			self::USER_COLUMN => $userId,
			self::ACTIVATION_COLUMN => $activationKey
		));

		if($foundActivation !== FALSE){
			
			$confirmed = $this->activateUser($userId);

			$this->activation_model->cleanUsedActivation($activationKey);
		}else{
			$confirmed = FALSE;
		}

		return $confirmed;
	}

	public function activateUser($userId){

		$this->load->model("usuarios_model");

		$this->db->where(Usuarios_model::USER_ID_COLUMN, $userId);
		
		$activated = $this->db->update(Usuarios_model::USER_TABLE, array(
			Usuarios_model::ACTIVE_COLUMN => TRUE
		));

		return $activated;
	}

	public function cleanUsedActivation($activationKey){

		$this->db->where(self::ACTIVATION_COLUMN, $activationKey);
		$this->db->delete(self::ACTIVATION_TABLE);
	}

	private function get($attr, $value = FALSE, $unique = TRUE){

		if(is_array($attr)){
			$foundActivation = $this->db->get_where(self::ACTIVATION_TABLE, $attr);
		}else{
			$foundActivation = $this->db->get_where(self::ACTIVATION_TABLE, array($attr => $value));
		}

		if($unique){
			$foundActivation = $foundActivation->row_array();
		}else{
			$foundActivation = $foundActivation->result_array();
		}

		$foundActivation = checkArray($foundActivation);

		return $foundActivation;
	}

	public function deleteUserActivation($userId){

		$this->db->where(self::USER_COLUMN, $userId);
		$deleted = $this->db->delete(self::ACTIVATION_TABLE);

		return $deleted;
	}
}