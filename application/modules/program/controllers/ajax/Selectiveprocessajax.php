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
        $this->load->model("selectiveprocess_model", "process_model");
        
        $selectiveprocess = $this->process_model->getById($selectiveprocessId);
        if(is_object($selectiveprocess)){

            $phasesOrder = $selectiveprocess->getSettings()->getPhasesOrder();                
            $phasesToSort = array();
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

            $this->sortPhases($phasesToSort);
        }

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
        
        $process = $this->getDataToSave();

        // Finally saves the selection process
        $this->load->model("selectiveprocess_model", "process_model");
        
        $processId = $this->process_model->save($process);
        $courseId = $this->input->post("course");
        
        if($process !== FALSE){
            $noticeName = $process->getName();
            callout("info", "O processo seletivo ".$noticeName." foi salvo com sucesso!", "Para finalizar o processo, faça o upload do edital em PDF logo abaixo.");
        }

        $hidden = array(
            'selection_process_id' => base64_encode($processId),
            'course' => $courseId
        );

        echo form_open_multipart("program/selectiveprocess/saveNoticeFile");

            echo form_hidden($hidden);

            $noticeFile = array(
                "name" => "notice_file",
                "id" => "notice_file",
                "type" => "file"
            );
            
            $submitFileBtn = array(
                "id" => "open_selective_process_btn",
                "class" => "btn btn-success btn-flat",
                "content" => "Salvar arquivo",
                "type" => "submit",
                "style" => "margin-top: 5%;"
            );

            include(MODULESPATH."/program/views/selection_process/_upload_notice_file.php");

        echo form_close();
        echo "<br>";

        
    }

    public function updateSelectionProcess(){
        
        $process = $this->getDataToSave();
        $hasSettings = $process->getSettings();
        $processId = $this->input->post("processId");

        $this->load->model("selectiveprocess_model", "process_model");
        $processId = $this->process_model->update($process, $processId);

        $noticeName = $process->getName();
        if($processId){
            callout("info", "O processo seletivo ".$noticeName." foi editado com sucesso!");
        }
    }

    public function getDataToSave(){

        echo "<h4><i class='fa fa-tag'></i> Status</h4>";

        $courseId = $this->input->post("course");
        $studentType = $this->input->post("student_type");
        $noticeName = $this->input->post("selective_process_name");
        $startDate = $this->input->post("selective_process_start_date");
        $endDate = $this->input->post("selective_process_end_date");
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

                $writtenTest = $this->input->post("phase_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);
                

                $writtenTestWeight = $this->input->post("phase_weight_".SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);

                $oralTest = $this->input->post("phase_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);
                $oralTestWeight = $this->input->post("phase_weight_".SelectionProcessConstants::ORAL_TEST_PHASE_ID);

                $phases = array();
                
                $notSelected = "0";

                if($preProject !== $notSelected){
                    $preProject = new PreProjectEvaluation($preProjectWeight, FALSE, SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE_ID);
                    $phases[] = $preProject;
                }

                if($writtenTest !== $notSelected){
                    $writtenTest = new WrittenTest($writtenTestWeight, FALSE, SelectionProcessConstants::WRITTEN_TEST_PHASE_ID);
                    $phases[] = $writtenTest;
                }

                if($oralTest !== $notSelected){
                    $oralTest = new OralTest($oralTestWeight, FALSE, SelectionProcessConstants::ORAL_TEST_PHASE_ID);
                    $phases[] = $oralTest;
                }

                if(!empty($phases)){
                    
                    // All processes have homologation
                    $phases[] = new Homologation(SelectionProcessConstants::HOMOLOGATION_PHASE_ID);

                    $phasesOrder = $this->input->post("phases_order");
                    $processSettings = new ProcessSettings($startDate, $endDate, $phases, $phasesOrder);
                    $process->addSettings($processSettings);


                }
                else{
                    // The process must have at least one phase
                    callout("danger", "Deve haver pelo menos uma fase além da homologação no processo seletivo.");
                }

            }else{
                // Invalid Student Type
                // Cannot happen
                callout("danger", "Tipo de estudante para o processo seletivo inválido.");
            }
        }catch(SelectionProcessException $e){
            callout("warning", $e->getMessage());
        }

        return $process;
    }


    public function editNoticeFile(){

        $this->load->module('program/selectiveprocess');
        $processId = $this->input->post("processId");
        $courseId = $this->input->post("course");
        $message = $this->selectiveprocess->uploadNoticeFile($courseId, $processId);
        switch ($message) {
            case selectiveprocess::NOTICE_FILE_SUCCESS:
                $status = "success";
                $pathToRedirect = "program/selectiveprocess/courseSelectiveProcesses/{$courseId}";
                break;

            case selectiveprocess::NOTICE_FILE_ERROR_ON_UPDATE:
                $status = "danger";
                $pathToRedirect = "program/selectiveprocess/tryUploadNoticeFile/{$processId}";
                break;
            
            default:
                $status = "danger";
                $pathToRedirect = "program/selectiveprocess/tryUploadNoticeFile/{$processId}";
                break;
        }

        callout($status, $message);
    }

    public function uploadNoticeFile($courseId, $processId){

        $this->load->library('upload');
        $process = $this->process_model->getById($processId);

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);

        $config = $this->setUploadOptions($process->getName(), $course["id_program"], $course["id_course"], $processId);

        $this->upload->initialize($config);
        $status = "";
        if($this->upload->do_upload("notice_file")){

            $noticeFile = $this->upload->data();
            $noticePath = $noticeFile['full_path'];

            $wasUpdated = $this->updateNoticeFile($processId, $noticePath);

            if($wasUpdated){
                $status = self::NOTICE_FILE_SUCCESS;
            }
            else{
                $status = self::NOTICE_FILE_ERROR_ON_UPDATE;
            }
        }
        else{
            // Errors on file upload
            $errors = $this->upload->display_errors();
            $status = $errors."<br>".self::NOTICE_FILE_ERROR_ON_UPLOAD.".";
        }

        return $status;
    }

    public function defineDivulgationDate(){
        $processId = $this->input->post("process_id");
        $courseId = $this->input->post("course_id");
        $date = $this->input->post("divulgation_start_date");
        $divulgationDescription = $this->input->post("divulgation_description");
        $this->load->model("selectiveprocess_model", "process_model");
        $process = $this->process_model->getById($processId);
        $settings = $process->getSettings();
        $processName = $process->getName();
        $error = "";
        if(is_null($date) || empty($date) || is_null($divulgationDescription) || empty($divulgationDescription)){
            $error .= "<br>Preencha a data e a descrição da divulgação.";
        }
        else{
            $saved = $this->process_model->saveNoticeDivulgation($processId, $date, $divulgationDescription);
            $processDivulgation = $this->process_model->getNoticeDivulgation($processId);
            if(!$saved){
                $error .= "<br>Não foi possível salvar a data de divulgação. Tente novamente.";
            }
        }

        if($error){
            $text = "Data não definida";
            $bodyText = function() use ($error){ 
                echo "<div class='alert alert-danger alert-dismissible' role='alert'>";
                echo $error;
                echo "</div>";
                echo "Você pode definir uma data ou divulgar o processo seletivo agora.";
                
            };
            $processDivulgation = FALSE;
            $footer = function() use ($processId, $courseId, $processName){
                echo anchor("#", "Divulgar agora", "class='btn btn-success'");
                echo "&nbsp";
                echo "<button data-toggle='collapse' data-target=#define_date_form class='btn btn-primary'>Definir data</button>";
                echo "<br>";
                echo "<br>";
                echo "<div id='define_date_form' class='collapse'>";
                echo "<div class='alert alert-info'> Definindo uma data de divulgação do edital você também deve definir uma descrição para a divulgação.</div>";
                echo "<br>";
                formOfDateDivulgation($processId, $processName, $courseId);
            };
            $link = "#";
            $date = FALSE;
        }
        else{
            $text = $divulgationDescription;
            $link = site_url('download_notice/'.$processId.'/'.$courseId);
            $bodyText = function(){
                echo "Clique para baixar.";
            };
            $footer = "";
            $date = convertDateTimeToDateBR($processDivulgation['date']);
            $today = new Datetime();
            $today = $today->format("d/m/Y");
            if($date > $today){
                $footer = function() use ($processId, $courseId, $processName, $date, $text){
                    echo "<button data-toggle='collapse' data-target=#define_date_form class='btn btn-primary'>Editar data</button>";
                    echo "<br>";
                    echo "<br>";
                    echo "<div id='define_date_form' class='collapse'>";
                    echo "<div class='alert alert-info'> Definindo uma data de divulgação do edital você também deve definir uma descrição para a divulgação.</div>";
                    echo "<br>";
                    formOfDateDivulgation($processId, $processName, $courseId, $date, $text);
                };
            }
        }

        writeTimelineItem($text, $date, $link, $bodyText, $footer);
        echo "</ul>";
    }

    function definePhaseDate($phaseId){
        $processId = $this->input->post("process_id");
        $startDate = $this->input->post("startDate");
        $endDate = $this->input->post("endDate");


        $error = "";
        if(is_null($startDate) || empty($startDate) || is_null($endDate) || empty($endDate)){
            $error .= "<br>Você deve escolher a data de início e de fim.";
        }
        else{
            $startDate = validateDate($startDate);
            $startDate = formatDateToDateTime($startDate);

            $endDate = validateDate($endDate);
            $endDate = formatDateToDateTime($endDate);
            
            $dataToSave = array(
                'start_date' => $startDate,
                'end_date' => $endDate
            );

            $this->load->model("selectiveprocess_model", "process_model");
            $saved = $this->process_model->savePhaseDate($processId, $phaseId, $dataToSave);
            if(!$saved){
                $error .= "<br>Não foi possível definir a data";
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
            };
        }

        writeTimelineItem($text, FALSE, "#", $bodyText);
        echo "</ul>";
    }
}
