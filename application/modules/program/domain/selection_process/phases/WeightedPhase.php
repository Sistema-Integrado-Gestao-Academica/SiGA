<?php

require_once "ProcessPhase.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

abstract class WeightedPhase extends ProcessPhase{
	
    const MIN_WEIGHT = 1;
    const MAX_WEIGHT = 5;

    const MIN_GRADE = 0;
    const MAX_GRADE = 100;

    const INVALID_WEIGHT = "O peso da fase está fora do intervalo permitido.";
    const INVALID_GRADE = "A nota obtida na fase está fora do intervalo permitido.";
    const GRADE_REQUIRED = "A nota de corte da fase eliminatória é obrigatória.";
    
	protected $weight;
	protected $grade;
    protected $knockoutPhase;

	public function __construct($phaseName, $weight, $grade = FALSE, $id = FALSE, $startDate = FALSE, $endDate = FALSE, $knockoutPhase = FALSE){

        parent::__construct($phaseName, $id, $startDate, $endDate);
        $this->setWeight($weight);
        $this->setKnockoutPhase($knockoutPhase);
        $this->setGrade($grade);
	}

    protected function setPhaseName($phaseName){

        if(is_string($phaseName)){

            switch($phaseName){
                case SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE:
                case SelectionProcessConstants::WRITTEN_TEST_PHASE:
                case SelectionProcessConstants::ORAL_TEST_PHASE:
                    $this->phaseName = $phaseName;
                    break;

                default:
                    throw new SelectionProcessException(self::INVALID_PHASE_NAME);
                    
            }
        }else{
            throw new SelectionProcessException(self::INVALID_PHASE_NAME);
        }
    }

    private function setWeight($weight){

        if(($weight >= self::MIN_WEIGHT) && ($weight <= self::MAX_WEIGHT)){
            $this->weight = $weight;
        }else{
            throw new SelectionProcessException(self::INVALID_WEIGHT);
        }
    }

    public function setGrade($grade){

        if($this->isKnockoutPhase()){
            if($grade !== FALSE){

                if($grade != ''){
                    if($grade >= self::MIN_GRADE && $grade <= self::MAX_GRADE){
                        
                        $this->grade = $grade;
                    }
                    else{
                        throw new SelectionProcessException(self::INVALID_GRADE);
                    }
                }
                else{
                    throw new SelectionProcessException(self::GRADE_REQUIRED);
                }
            }
            else{
                $this->grade = $grade;
            }
        }
        else{
            $this->grade = NULL;
        }
	}

    public function setKnockoutPhase($knockoutPhase){
        $this->knockoutPhase = $knockoutPhase;
    }

	public function getWeight(){
		return $this->weight;
	}

	public function getGrade(){
		return $this->grade;
	}

    public function isKnockoutPhase(){
        return $this->knockoutPhase;
    }
}