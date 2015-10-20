<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."/constants/GroupConstants.php");

class staffs_model extends CI_Model {

	public function getStaff($atributo, $setor) {
		$res = $this->db->get_where("staffs", array($atributo => $setor[$atributo]))->row_array();
		$res = checkArray($res);
		return $res;
	}

	public function getAllStaffs() {
		$allStaffs = $this->db->get("staffs")->result_array();
		$allStaffs = checkArray($allStaffs);
		return $allStaffs;
	}

	public function saveNewStaff($saveData) {
		$saved = $this->db->insert("staffs", $saveData);
		if($saved){
			$this->db->where('id_user', $saveData['id_user']);
			$this->db->update('user_group', array('id_group' => GroupConstants::STAFF_GROUP_ID));
		}
		return $saved;
	}

	public function altera($id, $nome) {
		$this->db->where('id', $id);
		$res = $this->db->update("staffs", array('nome' => $nome));
		return $res;
	}

	public function remove($staff) {
		$res = $this->db->delete("staffs", $staff);
		if($res){
			$this->db->where('id_user', $staff['id_user']);
			$this->db->update('user_group', array('id_group' => GroupConstants::GUEST_USER_GROUP_ID));
		}
		return $res;
	}
}

/* End of file staffs_model.php */
/* Location: ./application/models/staffs_model.php */ ?>