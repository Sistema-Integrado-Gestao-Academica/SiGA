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

    public function getSubscriptionDocs($subscriptionId){
        return $this->get(
            'id_subscription',
            $subscriptionId,
            $unique=FALSE,
            $like=FALSE,
            self::SUBSCRIPTION_DOCS_TABLE
        );
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