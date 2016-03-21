<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

class SelectiveProcessAjax extends CI_Controller {

    public function getPhasesToSort(){

        $preProject = $this->input->post("preProject");
        $writtenTest = $this->input->post("writtenTest");
        $oralTest = $this->input->post("oralTest");

        $phasesToSort = array();
        
        $notPresent = "0";

        if($preProject !== $notPresent){
            $phasesToSort[] = SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE;
        }

        if($writtenTest !== $notPresent){
            $phasesToSort[] = SelectionProcessConstants::WRITTEN_TEST_PHASE;
        }

        if($oralTest !== $notPresent){
            $phasesToSort[] = SelectionProcessConstants::ORAL_TEST_PHASE;
        }

        if(!empty($phasesToSort)){

            echo "<div id='phases_order_list'>";
                echo "<ol id = 'sortable' style='cursor: move;'>";
            foreach ($phasesToSort as $phase){
                
                echo "<li id={$phase}>";
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

        
    }

}
