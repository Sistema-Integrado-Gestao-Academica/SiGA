<?php
class Discipline_model extends CI_Model {

	public function listAllDisciplines(){
		$this->db->select('*');
		$this->db->from('discipline');
		$this->db->order_by("discipline_name", "asc");
		$registeredDisciplines = $this->db->get()->result_array();
		
		return $registeredDisciplines;
	}
	
	public function saveNewDiscipline($disciplineToRegister){
		$insertNew = $this->db->insert("discipline", $disciplineToRegister);
		if($insertNew){
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}
		return $insertionStatus;
	}
	
	public function disciplineExists($disciplineCode, $disciplineName){
		$this->db->where('discipline_code',$disciplineCode);
		$disciplineCodeExists = $this->db->get('discipline')->row_array();
		
		$this->db->where('discipline_name',$disciplineName);
		$disciplineNameExists = $this->db->get('discipline')->row_array();
		
		if($disciplineCodeExists && $disciplineNameExists){
			$existsCode = TRUE;
			$existsName = TRUE;
		}else if ($disciplineNameExists){
			$existsName = TRUE;
			$existsCode = FALSE;
		}else if ($disciplineCodeExists){
			$existsCode= TRUE;
			$existsName = FALSE;
		}else{
			$existsCode = FALSE;
			$existsName = FALSE;
		}
		$exists = array('code'=>$existsCode,'name'=>$existsName);
		return $exists;
	}
}