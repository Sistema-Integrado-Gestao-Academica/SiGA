<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Capesavaliation_model extends CI_Model {

	public function getAvaliationAtualizations(){
		$where = array('visualized'=>0);
		
		$notVisualized = $this->db->get_where('capes_avaliation',$where)->result_array();
		
		return $notVisualized;
	}
	
	public function changeToVisualized($avaliationId){
		
		define("VISUALIZED", 1);
		$update = array('visualized'=>VISUALIZED);
		
		$this->db->where('id_avaliation',$avaliationId);
		$updated = $this->db->update('capes_avaliation', $update);
		
		return $updated;
	}
	
}