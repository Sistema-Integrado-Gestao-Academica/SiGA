<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CapesAvaliation_model extends CI_Model {

	public function getAvaliationAtualizations(){
		$where = array('visualized'=>0);
		
		$notVisualized = $this->db->get_where('capes_avaliation',$where)->result_array();
		
		return $notVisualized;
	}
	
}
?>