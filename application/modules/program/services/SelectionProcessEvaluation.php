<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/SelectionProcessException.php');
require_once(MODULESPATH."/auth/constants/GroupConstants.php");


class SelectionProcessEvaluation extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );
        $this->load->model(
            'program/selectiveProcess_model',
            'process_model'
        );
        $this->load->model(
            'program/selectiveProcessEvaluation_model',
            'process_evaluation_model'
        );
    }

    public function homologateSubscription($subscription, $process, $subscriptionTeachers){
        $this->validateSubscriptionTeachers($subscriptionTeachers);
        $this->db->trans_start();
        $this->deleteSubscriptionPreviousEvaluations($subscription);
        $this->registerEvaluationTeachers($subscription, $subscriptionTeachers);
        $this->setSubscriptionHomologated($subscription);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function rejectSubscription($subscription){
        $this->db->trans_start();
        $this->process_subscription_model->rejectSubscription($subscription['id']);
        $this->deleteSubscriptionPreviousEvaluations($subscription);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    private function deleteSubscriptionPreviousEvaluations($subscription){
        $this->process_evaluation_model->deleteSubscriptionEvaluations($subscription['id']);
    }

    private function registerEvaluationTeachers($subscription, $subscriptionTeachers){
        $processPhases = $this->process_model->getProcessPhases($subscription['id_process']);
        if(!empty($processPhases)){
            foreach ($subscriptionTeachers as $teacherId) {
                foreach ($processPhases as $phase) {
                    $this->process_evaluation_model->saveOrUpdate(
                        $subscription['id'],
                        $teacherId,
                        $phase['id'],
                        ['grade' => NULL]
                    );
                }
            }
        }
    }

    private function setSubscriptionHomologated($subscription){
        $this->process_subscription_model->homologateSubscription($subscription['id']);
    }

    private function validateSubscriptionTeachers($subscriptionTeachers){
        $validArray = is_array($subscriptionTeachers)
            // Must be a pair of teachers to enroll
            && count($subscriptionTeachers) === 2;
        if($validArray){
            $teacherValid = TRUE;
            foreach ($subscriptionTeachers as $teacherId) {
                if(!userInGroup(GroupConstants::TEACHER_GROUP, $teacherId)){
                    $teacherValid = FALSE;
                    break;
                }
            }
            if(!$teacherValid){
                throw new SelectionProcessException('Este não é um professor válido no sistema.');
            }
        }else{
            throw new SelectionProcessException('Deve haver uma dupla de professores para um candidato.');
        }
    }
}
