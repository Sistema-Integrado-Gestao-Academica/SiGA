<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SelectionProcessSubscription extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->model(
            'program/selectiveprocessconfig_model',
            'process_config_model'
        );
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );
    }

    public function newSubscription($processId, $data){
        $user = getSession()->getUserData();

        $this->db->trans_start();
        $candidateId = $this->saveUserSubscription($processId, $user, $data);
        $this->db->trans_complete();

        var_dump('candidateId', $candidateId);

        return $this->db->trans_status();
    }

    private function saveUserSubscription($processId, $user, $data){
        $data = $this->prepareArrayToSave($data);
        $data['id_user'] = $user->getId();
        $data['birth_date'] = convertDateToDB($data['birth_date']);
        return $this->process_subscription_model->save($processId, $data);
    }

    private function prepareArrayToSave($data){
        $prepData = [];
        foreach ($data as $key => $value) {
            $newKey = str_replace('candidate_', '', $key);
            $prepData[$newKey] = $value;
        }
        return $prepData;
    }
}
