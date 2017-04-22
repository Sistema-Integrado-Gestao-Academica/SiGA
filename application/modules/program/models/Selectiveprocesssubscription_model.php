<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SelectiveProcessSubscription_model extends CI_Model {

    const CANDIDATE_ID_LENGTH = 7;

    protected $TABLE = 'selection_process_user_subscription';

    public function save($processId, $subscription){
        $foundSubscription = $this->get([
            'id_process' => $processId,
            'id_user' => $subscription['id_user']
        ]);

        $userNotSubscribedInProcess = $foundSubscription === FALSE;
        if($userNotSubscribedInProcess){
            $candidateId = $this->generateCandidateId();
            $subscription['candidate_id'] = $candidateId;
            $this->persist($subscription);
        }else{
            $candidateId = $foundSubscription['candidate_id'];
        }

        return $candidateId;
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
}