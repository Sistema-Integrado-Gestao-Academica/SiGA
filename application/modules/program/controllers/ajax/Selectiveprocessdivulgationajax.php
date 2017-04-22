<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");

class SelectiveProcessDivulgationAjax extends MX_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->helper('selectionprocess');
    }
    
    private function uploadNoticeFileForm($processId, $courseId){
        $hidden = array(
            'selection_process_id' => base64_encode($processId),
            'course' => $courseId
        );

        echo form_open_multipart("program/selectiveprocess/saveNoticeFile");

            echo form_hidden($hidden);

            $noticeFile = array(
                "name" => "notice_file",
                "id" => "notice_file",
                "type" => "file",
                "class" => "filestyle"
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
    
    public function addFormToAddDivulgation($processId){

        $this->load->model("selectiveprocess_model", "process_model");
        $process = $this->process_model->getById($processId);

        $fieldsForm = $this->getFieldsOfDivulgationForm($process);
        
        $text = function () use ($fieldsForm){
            echo form_input($fieldsForm['description']);
        };
        
        $bodyText = function() use ($fieldsForm){
            echo form_input($fieldsForm['processHidden']);
            echo form_input($fieldsForm['initialDivulgationHidden']);
            
                    echo form_label("Mensagem", "message");
                    echo form_textarea($fieldsForm['message']);

            echo "<br>";
            echo "<div class='row'>";
                echo "<div class='col-lg-6'>";
                    echo form_label("Fase relacionada", "phase_label");
                    echo form_dropdown("phase", $fieldsForm['dropdownPhases'], '', "class='form-control'");

                echo "</div>";
                echo "<div class='col-lg-6'>";
                    echo form_label("Arquivo <small><i>(Arquivos aceitos '.jpg, .png e .pdf')</i></small>", "divulgation_file");
                    echo form_input($fieldsForm['divulgationFile']);
                echo "</div>";
            echo "</div>";
        };
        $footer = function(){
            echo "<br>";
            echo form_button(array(
                "class" => "btn bg-olive btn-block",
                "content" => 'Divulgar',
                "type" => "submit"
            ));
        };

        echo form_open_multipart("program/selectiveprocessdivulgation/addDivulgation");
            writeTimelineItemToAddItem($text, $bodyText, $footer);
        echo form_close();
    }

    
    private function getFieldsOfDivulgationForm($process){
        $settings = $process->getSettings();
        $phases = $settings->getPhases();

        $dropdownPhases = array('0' => "Nenhuma");

        if($phases !== FALSE){
            foreach ($phases as $phase) {
                $id = $phase->getPhaseId();
                $name = $phase->getPhaseName();
                $dropdownPhases[$id] = $name;
            }
        }

        $description = array(
            "name" => "description",
            "id" => "description",
            "type" => "text",
            "placeholder" => "Descrição da divulgação",
            "class" => "form-control",
            "oninvalid" => "this.setCustomValidity('A descrição é obrigatória')",
            "oninput" => "setCustomValidity('')",
            "required" => "true"
        );
        $message = array(
            "name" => "message",
            "id" => "message",
            "type" => "text",
            "placeholder" => "Mensagem relacionada",
            "class" => "form-control",
            'rows' => '3'
        );

        $processId = $process->getId();
        $processHidden = array(
            "id" => "process_id",
            "name" => "process_id",
            "type" => "hidden",
            "value" => $processId
        );

        $initialDivulgation = $process->getNoticePath() == "" ? TRUE : FALSE;      

        $initialDivulgationHidden = array(
            "id" => "initial_divulgation",
            "name" => "initial_divulgation",
            "type" => "hidden",
            "value" => $initialDivulgation
        );

        $divulgationFile = array(
            "name" => "divulgation_file",
            "id" => "divulgation_file",
            "type" => "file"
        );

        if($initialDivulgation){
            $divulgationFile["required"] = TRUE;
            $divulgationFile["oninvalid"] = "this.setCustomValidity('Para a primeira divulgação (do edital) o arquivo é obrigatório')";
            $divulgationFile["onchange"] = "setCustomValidity('')";
        }


        $fields = array(
            'description' => $description,
            'dropdownPhases' => $dropdownPhases,
            'message' => $message,
            'processHidden' => $processHidden,
            'initialDivulgationHidden' => $initialDivulgationHidden,
            'divulgationFile' => $divulgationFile
        );

        return $fields;
    }

}