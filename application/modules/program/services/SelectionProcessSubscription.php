<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/UploadException.php');

class SelectionProcessSubscription extends CI_Model {

    const SUBSCRIPTIONS_FOLDER_NAME = 'selection_process_subscriptions';

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

        $saved = $this->db->trans_status();
        $status = $saved ? 'success' : 'danger';
        $msg = $saved
            ? 'Seus dados básicos foram salvos com sucesso!'
            : 'Não foi possível salvar os seus dados básicos, cheque os dados informados.';
        getSession()->showFlashMessage($status, $msg);

        $this->getSubmittedDocs($processId, $user, $candidateId);
    }

    private function saveUserSubscription($processId, $user, $data){
        $data = $this->prepareArrayToSave($data);
        $data['id_user'] = $user->getId();
        $data['birth_date'] = convertDateToDB($data['birth_date']);
        return $this->process_subscription_model->saveOrUpdate($processId, $data);
    }

    private function getSubmittedDocs($processId, $user, $candidateId){

        $subscription = $this->process_subscription_model->getByCandidateId($candidateId);
        $requiredDocs = $this->process_config_model->getProcessDocs($processId);

        $subfolders = [
            "s" => $processId,
            "u" => $user->getId()
        ];

        $errors = [];
        foreach($requiredDocs as $requiredDoc){
            $filename = 'P'.$processId.'-'.$candidateId.'-'.$requiredDoc['id'];
            $docFieldId = "doc_".$requiredDoc['id'];

            try{
                $docPath = uploadFile(
                    $filename,
                    $subfolders,
                    $docFieldId,
                    self::SUBSCRIPTIONS_FOLDER_NAME,
                    'pdf',
                    ['overwrite' => true]
                );
                $this->process_subscription_model->saveOrUpdateSubscriptionDoc([
                    'id_subscription' => $subscription['id'],
                    'id_doc' => $requiredDoc['id'],
                    'doc_path' => $docPath
                ]);
            }catch(UploadException $e){
                if(!!$requiredDoc['totally_required']
                    || $e->getErrorData() != "Nenhum arquivo foi selecionado."){

                    $errors[$requiredDoc['id']] = $e->getErrorData();
                }
            }
        }

        if(!empty($errors)){
            throw new UploadException('Invalid files submitted.', $errors);
        }
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
