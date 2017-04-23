<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Selectiveprocessconfig_model extends CI_Model {

    const AVAILABLE_DOCS_TABLE = "selection_process_available_docs";
    const NEEDED_DOCS_TABLE = "selection_process_needed_docs";

    public function addAllDocumentsToProcess($processId){
        $allDocs = $this->getAvailableDocs();
        $this->addDocsToProcess($allDocs, $processId);
    }

    public function addSelectedDocsToProcess($docs, $processId){
        $this->db->trans_start();
        $this->deleteProcessDocs($processId);
        $this->addDocsToProcess($docs, $processId);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    private function addDocsToProcess($docs, $processId){
        foreach($docs as $doc){
            $this->db->insert(self::NEEDED_DOCS_TABLE, [
                'id_process' => $processId,
                'id_doc' => $doc['id']
            ]);
        }
    }

    public function getProcessDocs($processId){
        $this->db->select('id, doc_name, doc_desc, totally_required');
        $this->db->from(self::AVAILABLE_DOCS_TABLE);
        $this->db->join(
            self::NEEDED_DOCS_TABLE,
            self::NEEDED_DOCS_TABLE.'.id_doc = '.self::AVAILABLE_DOCS_TABLE.'.id'
        );
        $this->db->where(self::NEEDED_DOCS_TABLE.'.id_process', $processId);
        $docs = $this->db->get()->result_array();
        $docs = checkArray($docs);

        return $docs;
    }

    public function getAvailableDocs(){
        return $this->get(FALSE, FALSE, FALSE, FALSE, self::AVAILABLE_DOCS_TABLE);
    }

    private function deleteProcessDocs($processId){
        $this->db->delete(self::NEEDED_DOCS_TABLE, [
            'id_process' => $processId
        ]);
    }
}
