<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/SelectionProcessException.php');
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");

class SelectionProcessPhaseChange extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->model(
            'program/selectiveProcess_model',
            'process_model'
        );
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );
        $this->load->module("notification/notification");
    }

    /**
     * @throws SelectionProcessException when invalid status is present on process
     */
    public function changeProcessPhase($processId, $newStatus){
        $process = $this->process_model->getById($processId);

        if($process === FALSE){
            throw new SelectionProcessException('Invalid process.');
        }

        $currentStatus = $process->getStatus();
        switch($currentStatus){
            case SelectionProcessConstants::DISCLOSED:
                // It is going to subscription phase
                assert(
                    $newStatus == SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS,
                    'After process is disclosed, the next phase should be subscription.'
                );
                $this->db->trans_start();
                $this->changeToStatus(
                    $process,
                    SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS
                );
                $this->notifySubscriptionsAreOpen($process);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS:
                // It is going to homologation phase
                assert(
                    $newStatus == SelectionProcessConstants::IN_HOMOLOGATION_PHASE,
                    'After subscriptions, the next phase should be homologation.'
                );
                $this->db->trans_start();
                $this->changeToHomologationPhase($process);
                $this->changeToStatus(
                    $process,
                    SelectionProcessConstants::IN_HOMOLOGATION_PHASE
                );
                $this->notifyInHomologationPhase($process);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_HOMOLOGATION_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $this->changeToStatus($process, $newStatus);
                $this->notifyHomologationIsOver($process, $newStatus);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_PRE_PROJECT_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $this->changeToStatus($process, $newStatus);
                $this->notifyPreProjectIsOver($process, $newStatus);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_WRITTEN_TEST_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $this->changeToStatus($process, $newStatus);
                $this->notifyWrittenTestIsOver($process, $newStatus);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_ORAL_TEST_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $this->changeToStatus($process, $newStatus);
                $this->notifyOralTestIsOver($process, $newStatus);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            default:
                throw new SelectionProcessException('Invalid status for process.');
                break;
        }
    }

    private function notifySubscriptionsAreOpen($process){
        $this->load->model("auth/usuarios_model", "user_model");
        $guests = $this->user_model->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID);
        $guests = !empty($guests) ? $guests : [];

        foreach ($guests as $user) {
            $params = [
                'subject' => "Inscrições abertas do processo {$process->getName()}"
            ];

            $message = function ($params) use ($process){
                return "Informamos que as inscrições para o processo <b>{$process->getName()}</b> estão abertas.";
            };

            $this->notification->notifyUser($user, $params, $message, $sender=FALSE, $onlyBar=FALSE);
        }
    }

    private function notifyInHomologationPhase($process){

    }

    private function changeToStatus($process, $newStatus){
        $this->process_model->changeProcessStatus(
            $process->getId(),
            $newStatus
        );
    }

    private function notifyHomologationIsOver($process, $newStatus){
        $this->notifyHomologatedSubscriptions($process, $newStatus);
        $this->notifyRejectedSubscriptions($process, $newStatus);
        $this->notifyNotFinalizedSubscriptions($process, $newStatus);
    }

    private function notifyHomologatedSubscriptions($process, $newStatus){
        $homologated = $this
            ->process_subscription_model
            ->getProcessHomologatedSubscriptions($process->getId());

        $barMessage = function ($params) use ($process){
            return "Sua inscrição no processo <b>{$process->getName()}</b> foi homologada com sucesso!";
        };

        $emailMessage = function ($subscription) use ($process){
            $message = "Olá, {$subscription['full_name']}!<br><br>";
            $message .= "Informamos que sua inscrição no processo <b>{$process->getName()}</b> foi <b><font color='green'>homologada</font></b> na fase de homologação.<br>";
            return $message;
        };

        $messages = [
            'bar' => $barMessage,
            'email' => $emailMessage
        ];

        $this->notifyHomologationResults($homologated, $process, $newStatus, $messages);
    }

    private function notifyRejectedSubscriptions($process, $newStatus){
        $rejected = $this
            ->process_subscription_model
            ->getProcessRejectedSubscriptions($process->getId());

        $barMessage = function ($params) use ($process){
            return "Sua inscrição no processo <b>{$process->getName()}</b> foi rejeitada na fase de homologação.";
        };

        $emailMessage = function ($subscription) use ($process){
            $message = "Olá, {$subscription['full_name']}!<br><br>";
            $message .= "Informamos que sua inscrição no processo <b>{$process->getName()}</b> foi <b><font color='red'>rejeitada</font></b> na fase de homologação.<br>";
            return $message;
        };

        $messages = [
            'bar' => $barMessage,
            'email' => $emailMessage
        ];

        $this->notifyHomologationResults($rejected, $process, $newStatus, $messages);
    }

    private function notifyNotFinalizedSubscriptions($process, $newStatus){
        $notFinalized = $this
            ->process_subscription_model
            ->getProcessNotFinalizedSubscriptions($process->getId());

        $barMessage = function ($params) use ($process){
            return "O processo <b>{$process->getName()}</b> passou da fase de inscrição e homologação e você não concluiu sua inscrição.";
        };

        $emailMessage = function ($subscription) use ($process){
            $message = "Olá, {$subscription['full_name']}!<br><br>";
            $message .= "Informamos que o processo <b>{$process->getName()}</b> passou da fase de inscrição e homologação e você não concluiu sua inscrição, portanto ela foi rejeitada automaticamente.";
            return $message;
        };

        $messages = [
            'bar' => $barMessage,
            'email' => $emailMessage
        ];

        $this->notifyHomologationResults($notFinalized, $process, $newStatus, $messages);
    }

    private function notifyHomologationResults($subscriptions, $process, $newStatus, array $messages){

        if(!empty($subscriptions)){
            foreach ($subscriptions as $subscription) {
                $user = [
                    'id' => $subscription['id_user'],
                    'name' => $subscription['full_name'],
                    'email' => $subscription['email']
                ];

                $params = [
                    'subject' => "Resultado da Fase de Homologação do processo {$process->getName()}"
                ];

                $this->notification->notifyUser($user, $params, $messages['bar'], $sender=FALSE, $onlyBar=FALSE, $messages['email']($subscription));
            }
        }
    }


    private function notifyPreProjectIsOver($process, $newStatus){

    }

    private function notifyWrittenTestIsOver($process, $newStatus){

    }

    private function notifyOralTestIsOver($process, $newStatus){

    }

    private function checkNextPhase($process, $newStatus){

        assert(
            $process->getStatus() != $newStatus,
            "Trying to change the phase to the current phase of a process."
        );

        // It should be going to next phase in process order
        $phasesOrder = $process->getSettings()->getPhasesOrder();

        // If the next phase is to finalize the process, this checks does not apply
        if($newStatus !== SelectionProcessConstants::FINISHED){
            // The new phase should be in phases order array
            $phaseName = str_replace('_phase', '', $newStatus);
            assert(
                in_array($phaseName, $phasesOrder),
                "The new phase '{$phaseName}' should be in phases order array"
            );

            $currentPhaseName = str_replace('_phase', '', $process->getStatus());

            // $newStatus should be after the current phase in phases order
            $currentPhaseIndex = array_search($currentPhaseName, $phasesOrder);
            // If index wasn't found is because is the homologation phase (the first one)
            $currentPhaseIndex = $currentPhaseIndex === FALSE ? -1 : $currentPhaseIndex;
            assert(
                $phaseName == $phasesOrder[intval($currentPhaseIndex) + 1],
                "The next phase should be after the homologation in phases order array."
            );
        }
    }
}
