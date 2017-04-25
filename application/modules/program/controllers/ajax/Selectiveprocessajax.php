<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");

require_once(MODULESPATH."/program/domain/selection_process/SelectionProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/RegularStudentProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/SpecialStudentProcess.php");
require_once(MODULESPATH."/program/domain/selection_process/ProcessSettings.php");


require_once(MODULESPATH."/program/domain/selection_process/phases/ProcessPhase.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/Homologation.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/WeightedPhase.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/PreProjectEvaluation.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/WrittenTest.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/OralTest.php");

class SelectiveProcessAjax extends MX_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->helper('selectionprocess');
        $this->load->model("selectiveprocess_model", "process_model");        
    }
    
    public function getPhasesToSort(){

        $preProject = $this->input->post("preProject");
        $writtenTest = $this->input->post("writtenTest");
        $oralTest = $this->input->post("oralTest");
        $phasesToSort = array();

        $notPresent = "0";
        if($preProject !== $notPresent){
            $phasesToSort["pre_project"] = SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE;
        }
        if($writtenTest !== $notPresent){
            $phasesToSort["written_test"] = SelectionProcessConstants::WRITTEN_TEST_PHASE;
        }
        if($oralTest !== $notPresent){
            $phasesToSort["oral_test"] = SelectionProcessConstants::ORAL_TEST_PHASE;
        }


        $this->sortPhases($phasesToSort);
    }

    public function showPhasesInOrder(){

        $selectiveprocessId = $this->input->post("processId");
        $selectiveprocess = $this->process_model->getById($selectiveprocessId);
        $phasesToSort = $this->getPhasesInSavedOrder($selectiveprocess);

        $this->sortPhases($phasesToSort);
    }

    private function getPhasesInSavedOrder($selectiveprocess){        
        
        $phasesToSort = array();
        if(is_object($selectiveprocess)){

            $phasesOrder = $selectiveprocess->getSettings()->getPhasesOrder();
            if($phasesOrder){

                foreach ($phasesOrder as $phaseOrder) {
                    $phasesToSort[$phaseOrder] = lang($phaseOrder);
                }

            }
            $preProject = $this->input->post("preProject");
            $writtenTest = $this->input->post("writtenTest");
            $oralTest = $this->input->post("oralTest");

            $notPresent = "0";

            if($preProject == $notPresent){
                unset($phasesToSort["pre_project"]);
            }
            else if($preProject != $notPresent){
                $isInArray = array_key_exists('pre_project', $phasesToSort);
                if(!$isInArray){
                    $phasesToSort['pre_project'] = lang('pre_project');
                }
            }

            if($writtenTest == $notPresent){
                unset($phasesToSort["written_test"]);
            }
            else if($writtenTest != $notPresent){
                $isInArray = array_key_exists('written_test', $phasesToSort);
                if(!$isInArray){
                    $phasesToSort['written_test'] = lang('written_test');
                }
            }

            if($oralTest == $notPresent){
                unset($phasesToSort["oral_test"]);
            }
            else if($oralTest != $notPresent){
                $isInArray = array_key_exists('oral_test', $phasesToSort);
                if(!$isInArray){
                    $phasesToSort['oral_test'] = lang('oral_test');
                }
            }
        }

        return $phasesToSort;
    }

    private function sortPhases($phasesToSort){
        if(!empty($phasesToSort)){

            echo "<div id='phases_order_list'>";
                echo "<ol id = 'sortable' style='cursor: move;'>";
            foreach ($phasesToSort as $key => $phase){

                echo "<li id={$key}>";
                echo "<h4><span class='label label-primary'>".$phase."</span></h4>";
                echo "</li>";
            }
            echo "</ol>";
            echo "</div>";
        }else{
            callout("danger", "Deve haver pelo menos uma fase além da ".SelectionProcessConstants::HOMOLOGATION_PHASE." no processo seletivo.");
        }
    }

    public function newSelectionProcess(){

        $data = $this->getDataToSave();
        $process = $data['process'];
        if($process !== FALSE){
            try{
                // Try to save the selection process
                $processId = $this->process_model->save($process);

                $noticeName = $process->getName();

                $response = array(
                    'status' => TRUE,
                    'processId' => $processId    
                );

            }
            catch(SelectionProcessException $e){
                $message = $e->getMessage();
                $response = array(
                    'message' => $message,
                    'status' => FALSE
                );
            }

        }
        else{
            $message = $data['message'];

            $response = array(
                'message' => $message,
                'status' => FALSE
            );
        }
        
        $json = json_encode($response);
        echo $json;
    }

    public function updateSelectionProcess(){

        $data = $this->getDataToSave();

        $process = $data['process'];
        if($process !== FALSE){
            $processId = $this->input->post("processId");

            try{
                $phasesChanged = $this->checkIfPhasesHasChanged($processId, $process);
                $processId = $this->process_model->update($process, $processId);
                
                $phases = NULL;
                if($phasesChanged){
                    $phases = json_encode($this->getPhasesArray($processId));
                }

                if($processId){
                    $response = array(
                        'status' => TRUE,
                        'phasesChanged' => $phasesChanged,
                        'phases' => $phases
                    );
                }
            }
            catch(SelectionProcessException $e){
                $message = $e->getMessage();
                $response = array(
                    'message' => $message,
                    'status' => FALSE
                );
            }
        }
        else{
            $message = $data['message'];

            $response = array(
                'message' => $message,
                'status' => FALSE
            );
        }

        $json = json_encode($response);
        echo $json;
    }

    public function getDataToSave(){

        $courseId = $this->input->post("course");
        $studentType = $this->input->post("student_type");
        $noticeName = $this->input->post("selective_process_name");

        $process = FALSE;
        $message = "";
        try{

            switch($studentType){
                case SelectionProcessConstants::REGULAR_STUDENT:
                    $process = new RegularStudentProcess($courseId, $noticeName);
                    break;

                case SelectionProcessConstants::SPECIAL_STUDENT:
                    $process = new SpecialStudentProcess($courseId, $noticeName);
                    break;

                default:
                    $process = FALSE;
                    break;
            }

            if($process !== FALSE){
                $preProject = $this->input->post("phase_".SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);
                $preProjectWeight = $this->input->post("phase_weight_".SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);
                $preProjectGrade = $this->input->post("phase_grade_".SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);

                $writtenTest = $this->input->post("phase_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);
                $writtenTestWeight = $this->input->post("phase_weight_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);
                $writtenTestGrade = $this->input->post("phase_grade_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);

                $oralTest = $this->input->post("phase_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);
                $oralTestWeight = $this->input->post("phase_weight_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);
                $oralTestGrade = $this->input->post("phase_grade_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);

                $phases = array();

                $notSelected = "0";


                if($preProject !== $notSelected){
                    $preProject = new PreProjectEvaluation($preProjectWeight, $preProjectGrade, SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);
                    $phases[] = $preProject;
                }

                if($writtenTest !== $notSelected){
                    $writtenTest = new WrittenTest($writtenTestWeight, $writtenTestGrade, SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);
                    $phases[] = $writtenTest;
                }

                if($oralTest !== $notSelected){
                    $oralTest = new OralTest($oralTestWeight, $oralTestGrade, SelectionProcessConstants::ORAL_TEST_PHASE_ID);
                    $phases[] = $oralTest;
                }

                if(!empty($phases)){

                    // All processes have homologation
                    $phases[] = new Homologation(SelectionProcessConstants::HOMOLOGATION_PHASE_ID);

                    $phasesOrder = $this->input->post("phases_order");
                    $processSettings = new ProcessSettings(NULL, NULL, $phases, $phasesOrder, FALSE, TRUE);

                    $process->addSettings($processSettings);
                }
                else{
                    // The process must have at least one phase
                    $message = "Deve haver pelo menos uma fase além da homologação no processo seletivo.";
                }
            }else{
                // Invalid Student Type, cannot happen
                $message = "Tipo de estudante para o processo seletivo inválido.";
            }
        }
        catch(SelectionProcessException $e){
            $process = FALSE;
            $message = $e->getMessage();
        }


        $data = array('process' => $process, 'message' => $message);

        return $data;
    }

    public function defineSubscriptionDate($processId){

        $courseId = $this->input->post("course_id");
        $startDate = $this->input->post("start_date");
        $endDate = $this->input->post("end_date");
        $error = "";
        if(is_null($startDate) || empty($startDate) || is_null($endDate) || empty($endDate)){
            $error .= "<br>Você deve escolher a data de início e de fim.";
        }
        else{
            $startDate = convertDateToDateTime($startDate);
            $endDate = convertDateToDateTime($endDate);
            $startDateToValidation = new Datetime($startDate);
            $endDateToValidation = new Datetime($endDate);

            $validDates = validateDatesDiff($startDateToValidation, $endDateToValidation);

            if($validDates){
                $validDateBasedOnPhases = $this->validateSubscriptionDate($processId, $endDateToValidation);
                
                if($validDateBasedOnPhases){
                    $saved = $this->process_model->saveSubscriptionDate($processId, $startDate, $endDate);
                    if(!$saved){
                        $error .= "<br>Não foi possível definir a data";
                    }
                }
                else{
                    $error .= "<br>Período inválido.<br> Verifique se o período é anterior ao período da fase seguinte.";
                }
               
            }
            else{
                $error .= "<br>A data final deve ser maior que a data inicial";
            }

        }

        if($error){
            $text = "Período de inscrição não definido";
            $bodyText = function() use ($processId, $error){
                echo "<div class='alert alert-danger alert-dismissible' role='alert'>";
                echo $error;
                echo "</div>";
                defineDateForm($processId, 'define_subscription_date', "start_date", "end_date");
            };
        }
        else{
            $text = "Período definido";
            $formattedStartDate = convertDateTimeToDateBR($startDate);
            $formattedEndDate = convertDateTimeToDateBR($endDate);
            $bodyText = function() use ($formattedStartDate, $formattedEndDate, $processId){
                echo "<b>Data de início:</b><br>";
                echo $formattedStartDate;
                echo "<b><br>Data de fim:</b><br>";
                echo $formattedEndDate;
                echo "<br><br>";
                echo "<b>Editar data definida</b>";
                defineDateForm($processId, 'define_subscription_date', "start_date", "end_date", $formattedStartDate, $formattedEndDate);
            };
        }

        writeTimelineItem($text, FALSE, "#", $bodyText);
        echo "</ul>";
    }

    public function definePhaseDate($phaseId){
        $processId = $this->input->post("process_id");
        $startDate = $this->input->post("startDate");
        $endDate = $this->input->post("endDate");

        $error = "";
        if(is_null($startDate) || empty($startDate) || is_null($endDate) || empty($endDate)){
            $error .= "<br>Você deve escolher a data de início e de fim.";
        }
        else{
            $startDate = convertDateToDateTime($startDate);
            $endDate = convertDateToDateTime($endDate);
            $startDateToValidation = new Datetime($startDate);
            $endDateToValidation = new Datetime($endDate);

            $validDates = validateDatesDiff($startDateToValidation, $endDateToValidation);
            
            if($validDates){

                $validDateBasedOnPhases = $this->validateDateBasedOnPhases($processId, $phaseId, $startDateToValidation, $endDateToValidation);
                
                if($validDateBasedOnPhases){

                    $dataToSave = array(
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    );

                    $saved = $this->process_model->savePhaseDate($processId, $phaseId, $dataToSave);
                    if(!$saved){
                        $error .= "<br>Não foi possível definir a data";
                    }
                }
                else{
                    $error .= "<br>Período inválido.<br> Verifique se o período é anterior ao período da fase seguinte e posterior ao período da fase anterior";
                }
            }
            else{
                $error .= "<br>A data final deve ser maior que a data inicial";
            }

        }

        if($error){
            $text = "Período para a fase não definido";
            $bodyText = function() use ($processId, $phaseId, $error){
                echo "<div class='alert alert-danger alert-dismissible' role='alert'>";
                echo $error;
                echo "</div>";
                defineDateForm($processId, 'define_date_phase_'.$phaseId, "phase_{$phaseId}_start_date", "phase_{$phaseId}_end_date");
            };
        }
        else{
            $text = "Período definido";
            $bodyText = function() use ($processId, $phaseId){
                echo "<b>Data de início:</b><br>";
                $phase = $this->process_model->getPhaseById($processId, $phaseId);
                $startDate = convertDateTimeToDateBR($phase[0]['start_date']);
                echo $startDate;
                $endDate = convertDateTimeToDateBR($phase[0]['end_date']);
                echo "<b><br>Data de fim:</b><br>";
                echo $endDate;
                echo "<hr>";
                echo "<b>Editar data definida</b>";
                defineDateForm($processId, 'define_date_phase_'.$phaseId, "phase_{$phaseId}_start_date", "phase_{$phaseId}_end_date", $startDate, $endDate);
            };
        }

        writeTimelineItem($text, FALSE, "#", $bodyText);
        echo "</ul>";
    }

    private function validateDateBasedOnPhases($processId, $phaseId, $phaseStartDate, $phaseEndDate){
        
        $process = $this->process_model->getById($processId);       

        $settings = $process->getSettings();
        $phases = $settings->getPhases();
        
        $relatedPhases = $this->getPreviousAndNextPhase($phaseId, $phases);
        $previousPhase = $relatedPhases['previous'];


        if(is_null($previousPhase)){
            $previousPhaseEndDate = $settings->getEndDate();
            if($previousPhaseEndDate != NULL){
                $previousPhaseEndDate = $settings->getYMDEndDate();
            }
        }
        else{
            $previousPhaseEndDate = $previousPhase->getYMDEndDate();
        }

        if($previousPhaseEndDate !== NULL){          
            // The current phase start date must be later than the end date of previous phase
            $previousPhaseEndDate = new Datetime($previousPhaseEndDate);
            $validDatePreviousPhase = validateDatesDiff($previousPhaseEndDate, $phaseStartDate);

            $nextPhase = $relatedPhases['next'];
            if(is_null($nextPhase)){
                $validDateNextPhase = TRUE;
            }
            else{
                $nextPhaseStartDate = $nextPhase->getYMDStartDate();
                $nextPhaseStartDate = new Datetime($nextPhaseStartDate);
                $validDateNextPhase = validateDatesDiff($phaseEndDate, $nextPhaseStartDate);
            }

            $validDates = $validDatePreviousPhase && $validDateNextPhase;
        }
        else{
            $validDates = TRUE;
        }


        return $validDates;
    }

    private function getPreviousAndNextPhase($phaseId, $phases){

        $phasesPerId = array();
        if($phases !== FALSE){
            foreach ($phases as $phase) {
                $id = $phase->getPhaseId();
                $phasesPerId[] = $id;
            }
        }

        $nextPhase = null;
        $previousPhase = null;
        if(!empty($phasesPerId)){
            $numberOfPhases = count($phases);
            $phase = array_search($phaseId, $phasesPerId);
            $firstPhasePreviousIndex = $phase - 1;
            $firstPhaseNextIndex = $phase + 1;
            if($phase > 0 && $phase < ($numberOfPhases - 1)){
                $previousPhase = $this->getRelatedPhasesRecursively($firstPhasePreviousIndex, $phases, TRUE);
                $nextPhase = $this->getRelatedPhasesRecursively($firstPhaseNextIndex, $phases);
            }
            elseif ($phase == ($numberOfPhases - 1)) {
                $previousPhase = $this->getRelatedPhasesRecursively($firstPhasePreviousIndex, $phases, TRUE);
            }
            else{
                $nextPhase = $this->getRelatedPhasesRecursively($firstPhaseNextIndex, $phases);
            }
        }

        $relatedPhases = array(
            'previous' => $previousPhase,
            'next' => $nextPhase
        );


        return $relatedPhases;
    }

    // Phase index is the key of phases array
    private function getRelatedPhasesRecursively($phaseIndex, $phases, $previousPhase = FALSE){

        $result = null;
        $phaseIndexExists = array_key_exists($phaseIndex, $phases);
        if($phaseIndexExists){
            $phase = $phases[$phaseIndex];
            $startDate = $phase->getStartDate();

            if(!is_null($startDate)){
                $result = $phase;
            }
            else{
                if($previousPhase){
                    $result = $this->getRelatedPhasesRecursively(($phaseIndex - 1), $phases, TRUE);
                }
                else{
                    $result = $this->getRelatedPhasesRecursively(($phaseIndex + 1), $phases);
                }
            }
        }


        return $result;
    }

    private function validateSubscriptionDate($processId, $endDate){

        $process = $this->process_model->getById($processId);       
        $settings = $process->getSettings();
        $phases = $settings->getPhases();

        $dateToCompare = NULL;
        if($phases){
            foreach ($phases as $phase) {
                $startDate = $phase->getStartDate();
                if($startDate != NULL){
                    $dateToCompare = $phase->getYMDStartDate();
                    $dateToCompare = new Datetime($dateToCompare);
                    break;
                }                        

            }
        }

        if($dateToCompare != NULL){
            $validDates = validateDatesDiff($endDate, $dateToCompare);
        }
        else{
            $validDates = TRUE;
        }

        return $validDates;
    }

    public function setDatesDefined($processId){

        $this->process_model->updateProcessFlags($processId, array('dates_defined' => TRUE));
    }

    public function setTeachersSelected($processId){

        $this->process_model->updateProcessFlags($processId, array('teachers_selected' => TRUE));

    }

    public function addDefineDatesTimeline($processId){
        $process = $this->process_model->getById($processId);

        $settings = $process->getSettings();
        $startDate = $settings->getStartDate();
        $endDate = $settings->getEndDate();
        $formattedStartDate = is_null($startDate) ? NULL: $settings->getFormattedStartDate();
        $formattedEndDate = is_null($endDate) ? NULL: $settings->getFormattedEndDate();

        $phases = $this->input->post('phases');
        $processPhases = array();
        if($phases){
            foreach ($phases as $phase) {
                $processPhaseId = $phase['id_phase'];
                $startDate = $phase['start_date'] === '' ? NULL : $phase['start_date'];
                $endDate = $phase['end_date'] === '' ? NULL : $phase['end_date'];
                switch ($processPhaseId) {
                    case SelectionProcessConstants::HOMOLOGATION_PHASE_ID:
                        $phase = new Homologation($processPhaseId, $startDate, $endDate);
                        break;
                    case SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID:
                        $phase = new PreProjectEvaluation(1, FALSE, $processPhaseId, $startDate, $endDate);
                        break;
                    case SelectionProcessConstants::WRITTEN_TEST_PHASE_ID:
                        $phase = new WrittenTest(1, FALSE, $processPhaseId, $startDate, $endDate);
                        break;
                    case SelectionProcessConstants::ORAL_TEST_PHASE_ID:
                        $phase = new OralTest(1, FALSE, $processPhaseId, $startDate, $endDate);
                        break;
                    default:
                        $phase = NULL;
                        break;
                }
                $processPhases[] = $phase;
            }
        }

        defineDateTimeline($processId, $formattedStartDate, $formattedEndDate, $processPhases);
    }

    private function checkIfPhasesHasChanged($processId, $newProcess){
        
        $process = $this->process_model->getById($processId);

        $oldPhases = $process->getSettings()->getPhases();
        $newPhases = $newProcess->getSettings()->getPhases();
        $phasesNotChanged = count($oldPhases) === count($newPhases);

        if($phasesNotChanged){
            $oldPhasesInOrder = $this->getPhasesInSavedOrder($process);
            $newPhasesInOrder = $this->getPhasesInSavedOrder($newProcess);

            $phasesNotChanged = $oldPhasesInOrder === $newPhasesInOrder;
        }


        return !$phasesNotChanged;
    }

    private function getPhasesArray($processId){
        
        $process = $this->process_model->getById($processId);
        $phases = $process->getSettings()->getPhases();
        $phasesArray = array();
        
        if($phases){
            foreach ($phases as $phase) {
                $startDate = $phase->getStartDate() == NULL ? NULL : $phase->getStartDate()->format("d/m/Y");
                $endDate = $phase->getEndDate() == NULL ? NULL : $phase->getEndDate()->format("d/m/Y");
                $phaseId = $phase->getPhaseId();
                $phasesArray[] = array('id_phase' => $phaseId,
                                    'start_date' => $startDate,
                                    'end_date' => $endDate);   
            }
        }

        return $phasesArray;
    }
}