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

                    // Finally saves the selection process
                    $this->load->model("selectiveprocess_model", "process_model");
                    
                    $processId = $this->process_model->save($process);


                    callout("info", "O processo seletivo ".$noticeName." foi salvo com sucesso!", "Para finalizar o processo, faça o upload do edital em PDF logo abaixo.");

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

                }else{
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

    }

}
