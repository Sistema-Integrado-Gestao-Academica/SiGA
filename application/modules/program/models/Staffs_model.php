<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Staffs_model extends CI_Model {

	public function getStaffById($id) {
		$res = $this->db->get_where("staffs", array('id_staff' => $id))->row_array();
		$res = checkArray($res);
		return $res;
	}

	public function getAllStaffs() {
		$allStaffs = $this->db->get("staffs")->result_array();
		$allStaffs = checkArray($allStaffs);
		return $allStaffs;
	}

	public function getEmployeeByPartialName($employeeName){

		$this->db->select("users.id, users.name, users.cpf, users.email, staffs.*");
		$this->db->from("users");
		$this->db->join("staffs", "staffs.id_user = users.id");
		$this->db->like("users.name", $employeeName);
		$foundEmployees = $this->db->get()->result_array();

		$foundEmployees = checkArray($foundEmployees);

		return $foundEmployees;
	}

	public function saveNewStaff($saveData) {
		$saved = $this->db->insert("staffs", $saveData);
		if($saved){
			$this->db->where('id_user', $saveData['id_user']);
			$this->db->update('user_group', array('id_group' => GroupConstants::STAFF_GROUP_ID));
		}
		return $saved;
	}

	public function updateStaffData($staff,$where) {

		$res = $this->db->update("staffs", $staff, $where);

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
