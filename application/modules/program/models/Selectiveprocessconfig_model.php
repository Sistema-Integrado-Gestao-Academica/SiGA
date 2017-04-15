<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Selectiveprocessconfig_model extends CI_Model {

    const AVAILABLE_DOCS_TABLE = "selection_process_available_docs";
    const NEEDED_DOCS_TABLE = "selection_process_needed_docs";

    public function addAllDocumentsToProcess($processId){
        $allDocs = $this->getAvailableDocs();

        foreach($allDocs as $doc){
            $this->db->insert(self::NEEDED_DOCS_TABLE, [
                'id_process' => $processId,
                'id_doc' => $doc['id']
            ]);
        }
    }

    public function getAvailableDocs(){
        return $this->get(FALSE, FALSE, FALSE, FALSE, self::AVAILABLE_DOCS_TABLE);
    }
}
