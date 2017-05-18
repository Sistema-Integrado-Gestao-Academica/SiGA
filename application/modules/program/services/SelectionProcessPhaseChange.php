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
        $this->load->model(
            'program/selectiveProcessEvaluation_model',
            'process_evaluation_model'
        );
        $this->load->module("notification/notification");
    }

    /**
     * @throws SelectionProcessException when invalid status is present on process
     */
    public function changeProcessPhase($processId, $newStatus){
        $process = $this->process_model->getById($processId);

        if($process === FALSE){
            throw new SelectionProcessException('O processo informado é inválido.');
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

                // Notifying all guests takes too long, disasbling for now
                //
                // $this->notifySubscriptionsAreOpen($process);

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
                $this->changeToStatus(
                    $process,
                    SelectionProcessConstants::IN_HOMOLOGATION_PHASE
                );
                $this->notifyInHomologationPhase($process);
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_HOMOLOGATION_PHASE:
                $this->checkIfCanFinishHomologation($process);
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $self = $this;
                $this->checkAppealPeriodAndChangeStatus($process, $newStatus,
                    function() use($self, $process, $newStatus){
                        $self->notifyHomologationIsOver($process, $newStatus);
                    }
                );
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_PRE_PROJECT_PHASE:
                $this->checkIfCanFinishPhase(
                    $process,
                    SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID
                );
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $self = $this;
                $this->checkAppealPeriodAndChangeStatus($process, $newStatus,
                    function() use($self, $process, $newStatus){
                        $self->notifyPreProjectIsOver($process, $newStatus);
                    }
                );
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_WRITTEN_TEST_PHASE:
                $this->checkIfCanFinishPhase(
                    $process,
                    SelectionProcessConstants::WRITTEN_TEST_PHASE_ID
                );
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $self = $this;
                $this->checkAppealPeriodAndChangeStatus($process, $newStatus,
                    function() use($self, $process, $newStatus){
                        $self->notifyWrittenTestIsOver($process, $newStatus);
                    }
                );
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            case SelectionProcessConstants::IN_ORAL_TEST_PHASE:
                $this->checkIfCanFinishPhase(
                    $process,
                    SelectionProcessConstants::ORAL_TEST_PHASE_ID
                );
                $this->checkNextPhase($process, $newStatus);
                $this->db->trans_start();
                $self = $this;
                $this->checkAppealPeriodAndChangeStatus($process, $newStatus,
                    function() use($self, $process, $newStatus){
                        $self->notifyOralTestIsOver($process, $newStatus);
                    }
                );
                $this->db->trans_complete();
                return $this->db->trans_status();
                break;

            default:
                throw new SelectionProcessException('Status do processo inválido.');
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

    private function checkAppealPeriodAndChangeStatus($process, $newStatus, callable $onChange){
        if(!$process->inAppealPeriod()){
            $this->process_model->setProcessAppealPeriod($process->getId(), TRUE);
            $onChange();
        }else{
            $this->changeToStatus($process, $newStatus);
            $this->process_model->setProcessAppealPeriod($process->getId(), FALSE);
        }
    }

    private function checkIfCanFinishHomologation($process){
        $notHomologated = $this
            ->process_subscription_model
            ->getProcessFinalizedSubscriptions($process->getId());

        if($notHomologated !== FALSE){
            throw new SelectionProcessException("Ainda há inscrições deste processo para serem homologadas ou rejeitadas. Finalize a homologação para seguir para a próxima fase.");
        }
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

    private function checkIfCanFinishPhase($process, $processPhase){
        $processPhaseId = $this->process_evaluation_model->getPhaseProcessIdByPhaseId(
            $process->getId(),
            $processPhase
        )->id;

        $pendingEvaluations = $this
            ->process_evaluation_model
            ->getTeachersNullEvaluationsOfPhase($process->getId(), $processPhaseId);

        if($pendingEvaluations !== FALSE){

            $message = "Não é possível avançar de fase, pois os seguintes professores ainda possuem avaliações pendentes:<br><br>";

            foreach ($pendingEvaluations as $evaluation) {
                $message .= "Professor(a) <b>{$evaluation['teacher_name']}</b> falta avaliar o(a) candidato(a) <b>{$evaluation['candidate_id']}</b>.";
                $message .= "<br>";
            }

            throw new SelectionProcessException($message);
        }
    }

    private function notifyPreProjectIsOver($process, $newStatus){
        $processPhaseId = $this->process_evaluation_model->getPhaseProcessIdByPhaseId(
            $process->getId(),
            SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID
        )->id;

        $candidates = $this
            ->process_evaluation_model
            ->getProcessCandidatesOfPhase($process->getId(), $processPhaseId);

        $barMessage = function($params) use ($process){
            return $params['approved']
                ? "Parabéns, você foi <b>aprovado</b> na fase de Avaliação de Pré-projeto do processo <b>{$process->getName()}</b>."
                : "Infelizmente você foi <b>reprovado</b> na fase de Avaliação de Pré-projeto do processo <b>{$process->getName()}</b>.";
        };

        $emailMessage = function($candidate, $approved) use ($process){
            $msg = "Olá, {$candidate['full_name']}!<br><br>";
            $msg .= $approved
                ? "Informamos que você foi <b><font color='green'>aprovado</font></b> na fase de Avaliação de Pré-projeto do processo <b>{$process->getName()}</b>."
                : "Informamos, infelizmente, que você foi <b><font color='red'>reprovado</font></b> na fase de Avaliação de Pré-projeto do processo <b>{$process->getName()}</b>.";
            return $msg;
        };

        $this->notifyOtherPhasesResults(
            'Avaliação de Pré-projeto',
            $candidates,
            $process,
            $barMessage,
            $emailMessage
        );
    }

    private function notifyWrittenTestIsOver($process, $newStatus){
        $processPhaseId = $this->process_evaluation_model->getPhaseProcessIdByPhaseId(
            $process->getId(),
            SelectionProcessConstants::WRITTEN_TEST_PHASE_ID
        )->id;

        $candidates = $this
            ->process_evaluation_model
            ->getProcessCandidatesOfPhase($process->getId(), $processPhaseId);

        $barMessage = function($params) use ($process){
            return $params['approved']
                ? "Parabéns, você foi <b>aprovado</b> na fase de Prova Escrita do processo <b>{$process->getName()}</b>."
                : "Infelizmente você foi <b>reprovado</b> na fase de Prova Escrita do processo <b>{$process->getName()}</b>.";
        };

        $emailMessage = function($candidate, $approved) use ($process){
            $msg = "Olá, {$candidate['full_name']}!<br><br>";
            $msg .= $approved
                ? "Informamos que você foi <b><font color='green'>aprovado</font></b> na fase de Prova Escrita do processo <b>{$process->getName()}</b>."
                : "Informamos, infelizmente, que você foi <b><font color='red'>reprovado</font></b> na fase de Prova Escrita do processo <b>{$process->getName()}</b>.";
            return $msg;
        };

        $this->notifyOtherPhasesResults(
            'Prova Escrita',
            $candidates,
            $process,
            $barMessage,
            $emailMessage
        );
    }

    private function notifyOralTestIsOver($process, $newStatus){
        $processPhaseId = $this->process_evaluation_model->getPhaseProcessIdByPhaseId(
            $process->getId(),
            SelectionProcessConstants::ORAL_TEST_PHASE_ID
        )->id;

        $candidates = $this
            ->process_evaluation_model
            ->getProcessCandidatesOfPhase($process->getId(), $processPhaseId);

        $barMessage = function($params) use ($process){
            return $params['approved']
                ? "Parabéns, você foi <b>aprovado</b> na fase de Prova Oral do processo <b>{$process->getName()}</b>."
                : "Infelizmente você foi <b>reprovado</b> na fase de Prova Oral do processo <b>{$process->getName()}</b>.";
        };

        $emailMessage = function($candidate, $approved) use ($process){
            $msg = "Olá, {$candidate['full_name']}!<br><br>";
            $msg .= $approved
                ? "Informamos que você foi <b><font color='green'>aprovado</font></b> na fase de Prova Oral do processo <b>{$process->getName()}</b>."
                : "Informamos, infelizmente, que você foi <b><font color='red'>reprovado</font></b> na fase de Prova Oral do processo <b>{$process->getName()}</b>.";
            return $msg;
        };

        $this->notifyOtherPhasesResults(
            'Prova Oral',
            $candidates,
            $process,
            $barMessage,
            $emailMessage
        );
    }

    private function notifyOtherPhasesResults($phaseName, $candidates, $process, $barMessage, $emailMessage=FALSE){
        if(!empty($candidates)){
            foreach ($candidates as $candidate) {
                $user = [
                    'id' => $candidate['id_user'],
                    'name' => $candidate['full_name'],
                    'email' => $candidate['email']
                ];

                $approved = $this->candidateIsApprovedInPhase($candidate);

                $params = [
                    'subject' => "Resultado da Fase de {$phaseName} do processo {$process->getName()}",
                    'approved' => $approved
                ];

                $this->notification->notifyUser($user, $params, $barMessage, $sender=FALSE, $onlyBar=FALSE, $emailMessage($candidate, $approved));
            }
        }
    }

    private function candidateIsApprovedInPhase($candidateData){
        $candidateAverageGrade = $candidateData['average_grade'];
        $phasePassingScore = $candidateData['grade'];
        $notKnockoutPhase = !$candidateData['knockout_phase'];

        return ($candidateAverageGrade >= $phasePassingScore) || $notKnockoutPhase;
    }

    private function checkNextPhase($process, $newStatus){

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
