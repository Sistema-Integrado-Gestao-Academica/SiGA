<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Director extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('administrative/director_model');
	}

	public function index(){

		loadTemplateSafelyByGroup(GroupConstants::DIRECTOR_GROUP, 'administrative/director/index');
	}

	public function defineDirector(){
		$this->load->model('program/teacher_model');
		$teachers = $this->teacher_model->getAllTeachers();
		$teachers = makeDropdownArray($teachers, 'id', 'name');

		$currentDirector = $this->director_model->getCurrentDirector();

		$data = array(
			'teachers' => $teachers,
			'currentDirector' => $currentDirector
		);

		$permittedGroups = array(GroupConstants::DIRECTOR_GROUP, GroupConstants::ADMIN_GROUP);
		loadTemplateSafelyByGroup($permittedGroups,'administrative/director/define', $data);
	}

	public function saveDirector(){

		$director = $this->input->post("new_director");
		$currentDirector = $this->input->post("current_director");
		$saved = $this->director_model->insertUserOnDirectorGroup($director, $currentDirector);

		if($saved){
			$status = 'success';
			$message = 'Diretor definido com sucesso.';
		}
		else{
			$status = 'danger';
			$message = 'Não foi possível definir o diretor. Tente novamente.';
		}
		
		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect('define_director');
	}

	private function getDirectorPrograms(){
		$this->load->model("program/program_model");
		$programs = $this->program_model->getAllPrograms();

		return $programs;
	}

	public function productionReports(){

		$session = getSession();
		$user = $session->getUserData();

		$programs = $this->getDirectorPrograms();
		$this->load->module("program/productionManagement");
        $this->productionmanagement->loadProductionsReportPage($programs, $user, GroupConstants::DIRECTOR_GROUP);
	}

	public function evaluationsReports(){

		$programs = $this->getDirectorPrograms();

		$this->load->module("program/coordinator");
		$this->coordinator->loadEvaluationReportsPage($programs, GroupConstants::DIRECTOR_GROUP);
	}

	public function productionFillReport(){
		$this->load->module("program/productionManagement");
		
        $courses = [];
		$programs = $this->getDirectorPrograms();
        foreach ($programs as $program) {
            $programCourses = $this->program_model->getProgramCourses($program['id_program']);
            foreach ($programCourses as $course) {
                $courses[] = $course;
            }
        }

		$this->productionmanagement->loadProductionFillReportPage($courses);

	}


}
