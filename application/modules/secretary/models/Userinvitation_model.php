<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserInvitation_model extends CI_Model {

	const INVITATION_TABLE = "user_invitation";
	const ID_COLUMN = "id_invitation";
	const INVITED_GROUP_COLUMN = "invited_group";
	const INVITED_EMAIL_COLUMN = "invited_email";
	const SECRETARY_COLUMN = "id_secretary";
	const ACTIVE_COLUMN = "active";

	public function save($invitation){
		$this->db->insert(self::INVITATION_TABLE, $invitation);
	}

	public function disable($invitation){
		$this->db->where(self::ID_COLUMN, $invitation);
		$this->db->update(self::INVITATION_TABLE, array(
			self::ACTIVE_COLUMN => FALSE
		));
	}

	public function invitationExists($invitation){

		$foundInvitation = $this->get(self::ID_COLUMN, $invitation);

		return $foundInvitation !== FALSE;
	}

	public function getInvitation($invitation){

		$foundInvitation = $this->get(self::ID_COLUMN, $invitation);

		return $foundInvitation;
	}
	
	private function get($attr, $value = FALSE, $unique = TRUE){

		if(is_array($attr)){
			$foundInvitation = $this->db->get_where(self::INVITATION_TABLE, $attr);
		}else{
			$foundInvitation = $this->db->get_where(self::INVITATION_TABLE, array($attr => $value));
		}

		if($unique){
			$foundInvitation = $foundInvitation->row_array();
		}else{
			$foundInvitation = $foundInvitation->result_array();
		}

		$foundInvitation = checkArray($foundInvitation);

		return $foundInvitation;
	}	
}