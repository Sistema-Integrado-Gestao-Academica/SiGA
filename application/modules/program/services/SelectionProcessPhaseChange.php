<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/SelectionProcessException.php');
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
                    'After process is disclosed,, the next phase should be subscription.'
                );
                $this->changeToSusbcriptionPhase($process);
                break;

            case SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS:
                // It is going to homologation phase
                assert(
                    $newStatus == SelectionProcessConstants::IN_HOMOLOGATION_PHASE,
                    'After subscriptions, the next phase should be homologation.'
                );
                $this->changeToHomologationPhase($process);
                break;

            case SelectionProcessConstants::IN_HOMOLOGATION_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->changeToStatus($process, $newStatus);
                $this->notifyHomologationIsOver($process, $newStatus);
                break;

            case SelectionProcessConstants::IN_PRE_PROJECT_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->changeToStatus($process, $newStatus);
                $this->notifyPreProjectIsOver($process, $newStatus);
                break;

            case SelectionProcessConstants::IN_WRITTEN_TEST_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->changeToStatus($process, $newStatus);
                $this->notifyWrittenTestIsOver($process, $newStatus);
                break;

            case SelectionProcessConstants::IN_ORAL_TEST_PHASE:
                $this->checkNextPhase($process, $newStatus);
                $this->changeToStatus($process, $newStatus);
                $this->notifyOralTestIsOver($process, $newStatus);
                break;

            default:
                throw new SelectionProcessException('Invalid status for process.');
                break;
        }
    }

    private function changeToSusbcriptionPhase($process){
        $this->process_model->changeProcessStatus(
            $process->getId(),
            SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS
        );
    }

    private function changeToHomologationPhase($process){
        $this->process_model->changeProcessStatus(
            $process->getId(),
            SelectionProcessConstants::IN_HOMOLOGATION_PHASE
        );
    }

    private function changeToStatus($process, $newStatus){
        $this->process_model->changeProcessStatus(
            $process->getId(),
            $newStatus
        );
    }

    private notifyHomologationIsOver($process, $newStatus){

    }

    private notifyPreProjectIsOver($process, $newStatus){

    }

    private notifyWrittenTestIsOver($process, $newStatus){

    }

    private notifyOralTestIsOver($process, $newStatus){

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

            // $newStatus should be after the homologation phase in phases order
            $homologationIndex = array_search("homologation", $phasesOrder);
            assert($newStatus == $processOrder[intval($homologationIndex) + 1]);
        }
    }
}
