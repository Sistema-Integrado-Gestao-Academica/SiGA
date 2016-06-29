<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ImportQualis_model extends CI_Model {

    const PERIODIC_QUALIS_TABLE = "periodic_qualis";
    const ISSN_COLUMN = "issn";
    const PERIODIC_COLUMN = "periodic";
    const QUALIS_COLUMN = "qualis";
    const AREA_COLUMN = "area";

    public function save($periodic){
        $this->db->insert(self::PERIODIC_QUALIS_TABLE, $periodic);
    }

    public function issnExists($issn){

        $periodic = $this->db->get_where(self::PERIODIC_QUALIS_TABLE, array(
            self::ISSN_COLUMN => $issn
        ))->result_array();

        $periodic = checkArray($periodic);

        $exists = $periodic !== FALSE;

        return $exists;
    }
}