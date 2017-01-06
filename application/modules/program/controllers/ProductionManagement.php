<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

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

        $group = GroupConstants::COORDINATOR_GROUP;
        $this->loadProductionsReportPage($programs, $user, $group);
    }

    public function loadProductionsReportPage($programs, $user, $group){
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

        loadTemplateSafelyByGroup($group, "program/intellectual_production/management/production_report", $data);
    }

    // Receive ajax request
    public function changeReportYear(){
        var_dump("aqui"); exit();
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

    public function printFillReport(){
        $data = [
            'users' => json_decode($this->input->post('users')),
            'filled' => $this->input->post('filled'),
            'year' => $this->input->post('year')
        ];

        loadTemplateSafelyByPermission(PermissionConstants::PRODUCTION_FILL_REPORT_PERMISSION, "program/intellectual_production/management/production_fill_report_print", $data);
    }

    public function productionFillReport(){

        $courses = $this->getUserCoursesForProductions();

        $this->loadProductionFillReportPage($courses);
    }

    public function loadProductionFillReportPage($courses){

        $year = $this->input->get('report_year');
        $filled = $this->input->get('only_registered_productions');
        $filled = is_null($filled) ? FALSE : TRUE;

        $currentYear = getCurrentYear();

        $year = empty($year) ? $currentYear : $year;

        $referenceYear = is_null($year) ? $currentYear : $year;

        $productionsAuthors = $this->getUsersWhoFilledProductions($referenceYear, $courses,  $filled);

        $data = array(
            'currentYear' => $currentYear,
            'referenceYear' => $referenceYear,
            'students' => $productionsAuthors[0],
            'teachers' => $productionsAuthors[1],
            'filled' => $filled,
            'searchYear' => $year
        );

        loadTemplateSafelyByPermission(PermissionConstants::PRODUCTION_FILL_REPORT_PERMISSION, "program/intellectual_production/management/production_fill_report", $data);

    }

    private function getUsersWhoFilledProductions($year, $courses, $filled=TRUE){

        $students = [];
        $teachers = [];
        if(!empty($courses)){
            $students = $this->production_model
                ->getProductionsAuthorByCourse($courses, $year, TRUE, FALSE, $filled);
            $teachers = $this->production_model
                ->getProductionsAuthorByCourse($courses, $year, FALSE, TRUE, $filled);
        }

        return [$students, $teachers];
    }

    private function getUserCoursesForProductions(){
        $loggedUser = getSession()->getUserData();
        $userId = $loggedUser->getId();

        // Check if the logged user is a coordinator
        $this->load->module("auth/module");
        $isCoordinator = $this->module->checkUserGroup(GroupConstants::COORDINATOR_GROUP);

        if($isCoordinator){
            $this->load->model('program/program_model');
            $programs = $this->program_model->getCoordinatorPrograms($userId);
            $courses = [];
            if(!empty($programs)){
                foreach ($programs as $program) {
                    $programCourses = $this->program_model->getProgramCourses($program['id_program']);
                    foreach ($programCourses as $course) {
                        $courses[] = $course;
                    }
                }
            }
        }else{
            // Otherwise the logged user is a secretary
            $this->load->model('program/course_model');
            $courses = $this->course_model->getCoursesOfSecretary($userId);
        }

        return $courses;
    }
}