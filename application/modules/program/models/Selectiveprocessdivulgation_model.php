
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."program/exception/SelectionProcessException.php");

class SelectiveProcessDivulgation_model extends CI_Model {

	public $TABLE = "selection_process_divulgation";
	public $PROCESS_TABLE = "selection_process";

	public function updateNoticeFile($processId, $noticePath){

		$this->db->where("id_process", $processId);
		$updated = $this->db->update($this->PROCESS_TABLE, array(
			"notice_path" => $noticePath
		));

		return $updated;
	}

	
    public function saveProcessDivulgation($data){
    	
		$processId = $data['id_process'];
		$this->db->where("id_process", $processId);
		$this->db->where('initial_divulgation', True);

		$saved = $this->db->insert("selection_process_divulgation", $data);

    	return $saved;
    }

    public function getProcessDivulgations($processId, $noticeDivulgation = FALSE){

    	$this->db->select("*");
		$this->db->from('selection_process_divulgation');
		$this->db->where("id_process", $processId);
    	if($noticeDivulgation){
			$this->db->where('initial_divulgation', True);
    	}
    	else{
			$this->db->where('date <= CURDATE()', NULL, FALSE);
    		$this->db->order_by('date', 'DESC');
    	}
		$noticeDivulgations = $this->db->get()->result_array();

		$noticeDivulgations = checkArray($noticeDivulgations);

		if($noticeDivulgation){
			$noticeDivulgations = $noticeDivulgations[0];
		}

		return $noticeDivulgations;
    }

    public function getProcessDivulgationById($divulgationId){

    	$this->db->select("*");
    	$searchResult = $this->db->get_where('selection_process_divulgation', array('id' => $divulgationId));
  		$divulgation = $searchResult->row_array();
		$divulgation = checkArray($divulgation);

		return $divulgation;
    }

}