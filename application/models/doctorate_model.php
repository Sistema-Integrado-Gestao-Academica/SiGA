<?php 

require_once(APPPATH."/exception/DoctorateException.php");

class Doctorate_model extends CI_Model {

	public function saveDoctorate($programId, $doctorateAttributes){

		$doctorateId = $this->saveDoctorateAttributes($doctorateAttributes);

		$this->associateDoctorateToAcademicProgram($doctorateId, $programId);
	}

	/**
	 * Associate a doctorate to an academic program
	 * @param $doctorateId - Doctorate id to associate
	 * @param $programId - The id of the program (course) to associate the doctorate
	 */
	private function associateDoctorateToAcademicProgram($doctorateId, $programId){
		$this->db->where('id_course', $programId);
		$this->db->update('academic_program', array('id_doctorate' => $doctorateId));
	}

	/**
	* Save a doctorate
	* @param $doctorateAttributes - Array with the doctorate attributes to be saved
	* @return the id of the saved doctorate
	*/
	private function saveDoctorateAttributes($doctorateAttributes){
		
		$this->db->insert('doctorate', $doctorateAttributes);

		$doctorateName = $doctorateAttributes['doctorate_name'];
		$doctorate = $this->getDoctorateByName($doctorateName);
		$doctorateId = $doctorate['id_doctorate'];

		return $doctorateId;
	}

	/**
	 * Search for a Doctorate by its name
	 * @param $doctorateName - String with the doctorate name to search for
	 * @return an array with all attributes of the found doctorate course
	 */
	private function getDoctorateByName($doctorateName){
		
		$searchResult = $this->db->get_where('doctorate', array('doctorate_name' => $doctorateName));
		
		$foundDoctorate = $searchResult->row_array();

		return $foundDoctorate;
	}

	public function getRegisteredDoctorateForCourse($courseId){
		$foundDoctorate = $this->getDoctorateForCourse($courseId);

		return $foundDoctorate;
	}

	private function getDoctorateForCourse($courseId){
		$thereIsDoctorate = $this->checkIfExistsDoctorateForThisCourse($courseId);

		if($thereIsDoctorate){
			$this->db->select('doctorate.*');
			$this->db->from('academic_program');
			$this->db->join('doctorate', 'academic_program.id_doctorate = doctorate.id_doctorate');
			$this->db->where('academic_program.id_course', $courseId);
			
			$foundDoctorate = $this->db->get();
			$foundDoctorate = $foundDoctorate->row_array();
		}else{
			$foundDoctorate = FALSE;
		}

		return $foundDoctorate;
	}

	private function checkIfExistsDoctorateForThisCourse($courseId){
		$this->db->select('id_doctorate');
		$searchResult = $this->db->get_where('academic_program', array('id_course' => $courseId));
		$searchResult = $searchResult->row_array();

		if(sizeof($searchResult) > 0){
			$foundDoctorate = $searchResult['id_doctorate'];
			$doctorateExists = $foundDoctorate != NULL;
		}else{
			$doctorateExists = FALSE;
		}

		return $doctorateExists;
	}

	public function deleteDoctorateByCourseId($courseId){
		

		$registeredDoctorate = $this->getDoctorateForCourse($courseId);


		if($registeredDoctorate != FALSE){
			$idDoctorate = $registeredDoctorate['id_doctorate'];
			$this->deleteDoctorate($idDoctorate);
		}else{
			// throw new DoctorateException("Não há doutorados registrados para esse curso.");
		}
	}

	private function deleteDoctorate($idDoctorate){
		$this->db->where('id_doctorate', $idDoctorate);
		$this->db->delete('doctorate');
	}
}