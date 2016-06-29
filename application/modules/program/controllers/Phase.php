<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/program/domain/selection_process/phases/ProcessPhase.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/Homologation.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/WeightedPhase.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/PreProjectEvaluation.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/WrittenTest.php");
require_once(MODULESPATH."/program/domain/selection_process/phases/OralTest.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/auth/constants/PermissionConstants.php");

class Phase extends MX_Controller {

    const MODEL_NAME = "program/phase_model";

    public function __construct(){
        parent::__construct();

        $this->load->model(self::MODEL_NAME);
    }

    public function getAllPhases(){

        $arrayPhases = $this->phase_model->getAllPhases();

        if($arrayPhases !== FALSE){

            $phases = array();

            foreach($arrayPhases as $arrayPhase){
                $phaseId = $arrayPhase[Phase_model::ID_ATTR];
                $phaseName = $arrayPhase[Phase_model::NAME_ATTR];
                $phaseWeight = $arrayPhase[Phase_model::WEIGHT_ATTR];

                try{

                    switch($phaseName){
                        case SelectionProcessConstants::HOMOLOGATION_PHASE:
                            $phase = new Homologation($phaseId);
                            break;
                        
                        case SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE:
                            $phase = new PreProjectEvaluation($phaseWeight, FALSE, $phaseId);
                            break;

                        case SelectionProcessConstants::WRITTEN_TEST_PHASE:
                            $phase = new WrittenTest($phaseWeight, FALSE, $phaseId);
                            break;

                        case SelectionProcessConstants::ORAL_TEST_PHASE:
                            $phase = new OralTest($phaseWeight, FALSE, $phaseId);
                            break;

                        default:
                            show_error("Foi retornado uma fase inválida do banco de dados. Contate o administrador.<br>Erro na tabela '<i>".Phase_model::PHASE_TABLE_NAME."</i>' - Deveria retornar uma das fases definidas no arquivo SelectionProcessConstants.", 500, "Algo errado no banco de dados.");
                            break;
                    }
                }catch(SelectionProcessException $e){
                    show_error("Algum dado das fases cadastradas no banco não está no formato correto. Exceção lançada: <i>".$e->getMessage()."</i>", 500, "Algo errado no banco de dados.");                    
                }

                $phases[] = $phase;

            }

        }else{
            show_error("Nenhum dado de fases encontrado na tabela '<i>".Phase_model::PHASE_TABLE_NAME."</i>'. Contate o administrador", 500, "Algo errado no banco de dados.");  
        }

        return $phases;
    }


}
