<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SelectiveProcessSubscription_model extends CI_Model {

    const CANDIDATE_ID_LENGTH = 7;

    protected $TABLE = 'selection_process_user_subscription';
    const SUBSCRIPTION_DOCS_TABLE = 'selection_process_subscription_docs';

    public function saveOrUpdate($processId, $subscription){
        $identifier = [
            'id_process' => $processId,
            'id_user' => $subscription['id_user']
        ];
        $foundSubscription = $this->get($identifier);

        $userNotSubscribedInProcess = $foundSubscription === FALSE;
        if($userNotSubscribedInProcess){
            $candidateId = $this->generateCandidateId();
            $subscription['candidate_id'] = $candidateId;
            $subscription['id_process'] = $processId;
            $this->persist($subscription);
        }else{
            $candidateId = $foundSubscription['candidate_id'];
            $this->db->where($identifier);
            $this->db->update($this->TABLE, $subscription);
        }

        return $candidateId;
    }

    public function saveOrUpdateSubscriptionDoc($subscriptionDoc){
        $identifier = [
            'id_subscription' => $subscriptionDoc['id_subscription'],
            'id_doc' => $subscriptionDoc['id_doc']
        ];
        $foundDocs = $this->get(
            $identifier, FALSE, TRUE, FALSE, self::SUBSCRIPTION_DOCS_TABLE
        );

        if($foundDocs === FALSE){
            $this->persist($subscriptionDoc, self::SUBSCRIPTION_DOCS_TABLE);
        }else{
            $this->db->where($identifier);
            $this->db->update(self::SUBSCRIPTION_DOCS_TABLE, $subscriptionDoc);
        }
    }

    public function finalizeSubscription($subscriptionId){
        if($this->exists('id', $subscriptionId)){
            $this->db->where('id', $subscriptionId);
            $this->db->update($this->TABLE, [
               'finalized' => TRUE
            ]);
        }
    }

    public function homologateSubscription($subscriptionId){
        return $this->setHomologated($subscriptionId, TRUE);
    }

    public function rejectSubscription($subscriptionId){
        return $this->setHomologated($subscriptionId, FALSE);
    }

    private function setHomologated($subscriptionId, $value=NULL){
        if($this->exists('id', $subscriptionId)){
            $this->db->where('id', $subscriptionId);
            return $this->db->update($this->TABLE, [
               'homologated' => $value
            ]);
        }else{
            return FALSE;
        }
    }

    private function generateCandidateId(){
        $this->load->helper('string');
        $candidateId = random_string('numeric', self::CANDIDATE_ID_LENGTH);
        while($this->candidateIdExists($candidateId)){
            $candidateId = random_string('numeric', self::CANDIDATE_ID_LENGTH);
        }
        return $candidateId;
    }

    private function candidateIdExists($candidateId){
        return $this->exists('candidate_id', $candidateId);
    }

    public function getByCandidateId($candidateId){
        return $this->get(
            'candidate_id',
            $candidateId,
            $unique=TRUE,
            $like=FALSE
        );
    }

    public function getByUserAndProcess($processId, $userId){
        return $this->get(
            ['id_process' => $processId, 'id_user' => $userId],
            FALSE,
            $unique=TRUE,
            $like=FALSE
        );
    }

    public function getBySubscriptionId($subscriptionId){
        return $this->get('id', $subscriptionId);
    }

    // Return the finalized but not homologated subscriptions of a process
    public function getProcessFinalizedSubscriptions($processId){
        return $this->getProcessSubscriptions($processId, TRUE, NULL);
    }

    // Return the NOT finalized subscriptions of a process
    public function getProcessNotFinalizedSubscriptions($processId){
        return $this->getProcessSubscriptions($processId, FALSE, NULL);
    }

    // Return the finalized and homologated subscriptions of a process
    public function getProcessHomologatedSubscriptions($processId){
        return $this->getProcessSubscriptions($processId, TRUE, TRUE);
    }

    // Return the finalized and rejected subscriptions of a process
    public function getProcessRejectedSubscriptions($processId){
        return $this->getProcessSubscriptions($processId, TRUE, FALSE);
    }

    public function getProcessAllCandidates($processId){
        return $this->get('id_process', $processId, FALSE); 
    }

    private function getProcessSubscriptions($processId, $finalized, $homologated){
        return $this->get(
            [
                'id_process' => $processId,
                'finalized' => $finalized,
                'homologated' => $homologated
            ],
            FALSE,
            FALSE
        );
    }

    public function getSubscriptionDocs($subscriptionId, $isTeacher = FALSE){
        $this->db->select('selection_process_subscription_docs.id_doc, doc_name, doc_path, id_subscription');
        $this->db->from('selection_process_subscription_docs');
        $this->db->join(
            'selection_process_available_docs',
            'selection_process_subscription_docs.id_doc = selection_process_available_docs.id'
        );
        $this->db->where(
            'selection_process_subscription_docs.id_subscription',
            $subscriptionId
        );

        if($isTeacher){
            $this->db->join(
                'selection_process_needed_docs',
                'selection_process_needed_docs.id_doc = selection_process_subscription_docs.id_doc'
            );
            $this->db->where('selection_process_needed_docs.protected', FALSE);
                
        }
        $docs = checkArray($this->db->get()->result_array());

        return $docs;
    }

    public function getSubscriptionDoc($subscriptionId, $docId){
        return $this->get(
            ['id_subscription' => $subscriptionId, 'id_doc' => $docId],
            FALSE,
            $unique=TRUE,
            $like=FALSE,
            self::SUBSCRIPTION_DOCS_TABLE
        );
    }
}