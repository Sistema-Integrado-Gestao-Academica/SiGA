<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class ProductionManagement extends MX_Controller {

    const UNQUALIFIED = "Não qualificado";

    public function __construct(){
        $this->load->model("program/production_model");
    }

    public function index(){

        $user = getSession()->getUserData();
        $coordinatorId = $user->getId();

        $this->load->model("program/program_model");
        $programs = $this->program_model->getCoordinatorPrograms($coordinatorId);

        $currentYear = getCurrentYear();

        $programsChartData = array();
        if(!empty($programs)){
            // Create the chart data to all coordinator programs
            foreach ($programs as $program) {
                $productions = $this->production_model->getProgramsProduction($program, $currentYear);
                $chartData = $this->assembleChartData($productions, $currentYear, $json=FALSE);
                $programsChartData[$program['id_program']] = $chartData;
            }
        }

        $data = array(
            'programs' => $programs,
            'user' => $user,
            'chartData' => json_encode($programsChartData), // Send it to the view as json
            'currentYear' => $currentYear
        );

        loadTemplateSafelyByGroup(GroupConstants::COORDINATOR_GROUP, "program/intellectual_production/management/production_report", $data);
    }

    // Receive ajax request
    public function changeReportYear(){
        $year = $this->input->post("year");
        $programId = $this->input->post("program");

        $this->load->model("program/program_model");
        $program = $this->program_model->getProgramById($programId);

        $productions = $this->production_model->getProgramsProduction($program, $year);

        $chartData = $this->assembleChartData($productions, $year);

        echo $chartData;
    }

    private function assembleChartData($productions, $year, $json=TRUE){

        $productions = $this->filterProductionsByQualis($productions);

        $columns = array(
            // Put the year on the X axis
            array('x1', $year)
        );

        $xs = array();
        $types = array();
        foreach ($productions as $qualis => $qualisProductions) {
            $columns[] = array($qualis, count($qualisProductions)); // Put the quantity of productions as Y data
            $xs[$qualis] = 'x1';  // Associate all the Y data to the axis X
            $types[$qualis] = 'bar'; // All as bar chart
        }

        $chartData = array(
            'xs' => $xs,
            'columns' => $columns,
            'types' => $types
        );

        $chartData = $json ? json_encode($chartData) : $chartData;

        return $chartData;
    }

    private function filterProductionsByQualis($productions){
        // Separate the productions by possible qualis: A1; A2; B1; B2; B3; B4; B5; C
        $filteredProductions = array(
            'A1' => array(),
            'A2' => array(),
            'B1' => array(),
            'B2' => array(),
            'B3' => array(),
            'B4' => array(),
            'B5' => array(),
            'C' => array(),
            self::UNQUALIFIED => array(),
        );

        if(!empty($productions)){
            foreach ($productions as $production) {

                $productionQualis = $production['qualis'];

                if($productionQualis !== NULL){
                    $qualis = strtoupper($productionQualis);
                    $filteredProductions[$qualis][] = $production;
                }else{
                    $filteredProductions[self::UNQUALIFIED][] = $production;
                }
            }
        }

        return $filteredProductions;
    }

}