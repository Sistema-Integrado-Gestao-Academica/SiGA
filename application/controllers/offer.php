<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');
require_once('course.php');
require_once('discipline.php');
require_once('module.php');
require_once('usuario.php');
require_once('schedule.php');
require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/constants/EnrollmentConstants.php");

class Offer extends CI_Controller {

	/**
	 * Used to solve problems of instance of objects in other files
	 */
	public function loadModel(){

		$this->load->model('offer_model');
	}

	public function newOffer($courseId){

		$needsMastermindApproval = $this->input->post("needs_mastermind_approval_ckbox");

		if($needsMastermindApproval === FALSE){
			$needsMastermindApproval = EnrollmentConstants::DONT_NEED_MASTERMIND_APPROVAL;
		}

		$this->load->model('offer_model');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$offer = array(
			'semester' => $currentSemester['id_semester'],
			'course' => $courseId,
			'offer_status' => "proposed",
			'needs_mastermind_approval' => $needsMastermindApproval
		);

		$wasSaved = $this->offer_model->newOffer($offer);

		if($wasSaved){
			$status = "success";
			$message = "Lista de oferta criada com sucesso. Adicione disciplinas em EDITAR.";
		}else{
			$status = "danger";
			$message = "Não foi possível criar a lista de oferta. Tente novamente.";
		}

		$this->session->set_flashdata($status, $message);
		redirect('usuario/secretary_offerList');
	}

	public function approveOfferList($idOffer){

		$this->load->model('offer_model');
		$wasApproved = $this->offer_model->approveOfferList($idOffer);

		if($wasApproved){
			$status = "success";
			$message = "Lista de Oferta aprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível aprovar essa lista de ofertas. Verifique se há discisplinas adicionadas a essa lista, não é possível aprovar uma lista sem disciplinas.";
		}

		$this->session->set_flashdata($status, $message);
		redirect('usuario/secretary_offerList');
	}

	public function deleteDisciplineClass($idOffer, $idDiscipline, $class, $idCourse){
		$this->load->model('offer_model');
		$deletedDisciplineOffer = $this->offer_model->deleteDisciplineClassOffer($idOffer, $idDiscipline,$class);

		if($deletedDisciplineOffer){
			$status = "success";
			$message = "Turma apagada da oferta.";
		}else{
			$status = "danger";
			$message = "Não foi possível apagar essa turma da lista de ofertas.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("offer/displayDisciplineClasses/{$idDiscipline}/{$idOffer}/{$idCourse}");
	}

	public function formToUpdateDisciplineClass($idOffer, $idDiscipline, $class, $idCourse){

		// Get the classe of a discipline in an offer
		$offerDisciplineClass = $this->getCourseOfferDisciplineByClass($idDiscipline, $idOffer, $class);

		// Get discipline data
		$discipline = new Discipline();
		$disciplineData = $discipline->getDisciplineByCode($idDiscipline);

		// Get all teachers
		define("TEACHER_GROUP", "docente");

		$group = new Module();
		$foundGroup = $group->getGroupByName(TEACHER_GROUP);

		if($foundGroup !== FALSE){
			$user = new Usuario();
			$teachers = $user->getUsersOfGroup($foundGroup['id_group']);

			if($teachers !== FALSE){

				$allTeachers = array();

				foreach($teachers as $teacher){
					$allTeachers[$teacher['id']] = $teacher['name'];
				}
			}else{
				$allTeachers = FALSE;
			}

		}else{
			$allTeachers = FALSE;
		}

		$data = array(
				'disciplineData'      => $disciplineData,
				'offerDisciplineData' => $offerDisciplineClass,
				'idOffer'             => $idOffer,
				'teachers'            => $allTeachers,
				'class'               => $class,
				'idCourse'			  => $idCourse
		);

		loadTemplateSafelyByGroup('secretario', 'offer/offer_update_discipline_classes', $data);

	}

	public function displayOfferedDisciplines($courseId){

		$this->load->model('offer_model');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$offer = $this->offer_model->getOfferByCourseId($courseId, $currentSemester['id_semester']);

		if($offer !== FALSE){
			$disciplines = $this->getOfferDisciplines($offer['id_offer']);
		}else{
			$disciplines = FALSE;
		}

		return $disciplines;
	}

	public function displayDisciplines($idOffer, $courseId){

		$disciplines = $this->getOfferDisciplines($idOffer);

		$course = new Course();
		$offerCourse = $course->getCourseById($courseId);

		$offerData = array(
			'idOffer' => $idOffer,
			'course' => $offerCourse,
			'disciplines' => $disciplines
		);

		loadTemplateSafelyByGroup('secretario', 'offer/new_offer', $offerData);
	}

	public function displayDisciplineClasses($idDiscipline, $idOffer, $idCourse){

		// Get the classes of a discipline in an offer
		$this->load->model('offer_model');
		$offerDisciplineClasses = $this->offer_model->getOfferDisciplineClasses($idDiscipline, $idOffer);

		// Get discipline data
		$discipline = new Discipline();
		$disciplineData = $discipline->getDisciplineByCode($idDiscipline);

		// Get all teachers
		$group = new Module();
		$foundGroup = $group->getGroupByName(GroupConstants::TEACHER_GROUP);

		if($foundGroup !== FALSE){
			$user = new Usuario();
			$teachers = $user->getUsersOfGroup($foundGroup['id_group']);

			if($teachers !== FALSE){

				$allTeachers = array();

				foreach($teachers as $teacher){
					$allTeachers[$teacher['id']] = $teacher['name'];
				}
			}else{
				$allTeachers = FALSE;
			}

		}else{
			$allTeachers = FALSE;
		}

		$data = array(
			'disciplineData' => $disciplineData,
			'offerDisciplineData' => $offerDisciplineClasses,
			'idOffer' => $idOffer,
			'teachers' => $allTeachers,
			'idCourse' => $idCourse
		);

		loadTemplateSafelyByGroup('secretario', 'offer/offer_discipline_classes', $data);
	}

	public function newOfferDisciplineClass($idDiscipline, $idOffer, $idCourse){

		$dataIsOk = $this->validateDisciplineClassData();

		if($dataIsOk){

			$disciplineClass = $this->input->post('disciplineClass');
			$totalVacancies = $this->input->post('totalVacancies');
			$mainTeacher = $this->input->post('mainTeacher');
			$secondaryTeacher = $this->input->post('secondaryTeacher');

			// As is a new class, the current vacancy is equal to the total
			$currentVacancies = $totalVacancies;

			$classData = array(
				'id_offer' => $idOffer,
				'id_discipline' => $idDiscipline,
				'class' => $disciplineClass,
				'total_vacancies' => $totalVacancies,
				'current_vacancies' => $currentVacancies,
				'main_teacher' => $mainTeacher
			);

			define("NONE_TEACHER_OPTION", 0);

			if($secondaryTeacher != NONE_TEACHER_OPTION){
				if($mainTeacher !== $secondaryTeacher){
					$classData['secondary_teacher'] = $secondaryTeacher;
				}else{
					// Nothing to do because the main and secondary teachers might not be equal
				}
			}else{
				// Nothing to do because was not chosen a secondary teacher
			}

			$this->load->model('offer_model');
			$wasSaved = $this->offer_model->addOfferDisciplineClass($classData);

			if($wasSaved){
				$status = "success";
				$message = "Turma cadastrada com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível cadastrar essa turma. Cheque os dados informados, não é possível cadastrar um turma que já existe.";
			}

		}else{
			$status = "danger";
			$message = "Dados na forma incorreta.";
		}

		$this->session->set_flashdata($status, $message);

		redirect("offer/displayDisciplineClasses/{$idDiscipline}/{$idOffer}/{$idCourse}");
	}

	public function updateOfferDisciplineClass($idDiscipline, $idOffer, $oldClass){

		$idCourse = $this->input->post('course');
		$dataIsOk = $this->validateDisciplineClassData();

		if($dataIsOk){
			$disciplineClass = $this->input->post('disciplineClass');
			$totalVacancies = $this->input->post('totalVacancies');
			$mainTeacher = $this->input->post('mainTeacher');
			$secondaryTeacher = $this->input->post('secondaryTeacher');

			// As this code is only reached when the offer list is proposed, the current vacancies does not change
			$currentVacancies = $totalVacancies;

			$classData = array(
				'id_offer' => $idOffer,
				'id_discipline' => $idDiscipline,
				'class' => $disciplineClass,
				'total_vacancies' => $totalVacancies,
				'current_vacancies' => $currentVacancies,
				'main_teacher' => $mainTeacher
			);

			define("NONE_TEACHER_OPTION", 0);

			if($secondaryTeacher != NONE_TEACHER_OPTION){
				if($mainTeacher !== $secondaryTeacher){
					$classData['secondary_teacher'] = $secondaryTeacher;
				}else{
					// Nothing to do because the main and secondary teachers might not be equal
				}
			}else{
				$classData['secondary_teacher'] = NULL;
			}

			$this->load->model('offer_model');
			$wasUpdated = $this->offer_model->updateOfferDisciplineClass($classData, $oldClass);

		if($wasUpdated){
				$status = "success";
				$message = "Turma alterada com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível alterar essa turma. Cheque os dados informados, não é possível cadastrar uma turma que já existe.";
			}

		}else{
			$status = "danger";
			$message = "Dados na forma incorreta. Cheque os dados informados.<br> Informe apenas letras para a turma.";
		}

		$this->session->set_flashdata($status, $message);

		redirect("offer/displayDisciplineClasses/{$idDiscipline}/{$idOffer}/{$idCourse}");
	}

	private function validateDisciplineClassData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("disciplineClass", "Turma", "required|alpha");
		$this->form_validation->set_rules("totalVacancies", "Vagas totais", "required");
		$this->form_validation->set_rules("mainTeacher", "Professor principal", "required");
		$this->form_validation->set_rules("secondaryTeacher", "Professor secundário", "");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$status = $this->form_validation->run();

		return $status;
	}

	public function removeDisciplineFromOffer($idDiscipline, $idOffer, $idCourse){

		$this->load->model('offer_model');

		$wasRemoved = $this->offer_model->removeDisciplineFromOffer($idDiscipline, $idOffer);

		if($wasRemoved){
			$status = "success";
			$message = "Disciplina retirada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível retirar essa disciplina. Cheque os códigos informados.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("offer/addDisciplines/{$idOffer}/{$idCourse}");
	}

	public function addDisciplines($idOffer, $courseId){

		$discipline = new Discipline();
		$allDisciplines = $discipline->getCourseSyllabusDisciplines($courseId);

		$course = new Course();
		$offerCourse = $course->getCourseById($courseId);

		$data = array(
			'allDisciplines' => $allDisciplines,
			'course' => $offerCourse,
			'idOffer' => $idOffer
		);

		loadTemplateSafelyByGroup('secretario', 'offer/offer_disciplines', $data);
	}

	public function checkAvailableVacancies($idOfferDiscipline){

		$this->load->model('offer_model');

		$wasSubtracted = $this->offer_model->checkAvailableVacancies($idOfferDiscipline);

		return $wasSubtracted;
	}

	public function subtractOneVacancy($idOfferDiscipline){

		$this->load->model('offer_model');

		$wasSubtracted = $this->offer_model->subtractOneVacancy($idOfferDiscipline);

		return $wasSubtracted;
	}

	public function getOffer($idOffer){

		$this->load->model('offer_model');

		$foundOffer = $this->offer_model->getOffer($idOffer);

		return $foundOffer;
	}

	public function getOfferDisciplineById($idOfferDiscipline){

		$this->load->model('offer_model');

		$foundOfferDiscipline = $this->offer_model->getOfferDisciplineById($idOfferDiscipline);

		return $foundOfferDiscipline;
	}

	public function disciplineExistsInOffer($disciplineId, $offerId){

		$this->load->model('offer_model');

		$disciplineExists = $this->offer_model->disciplineExistsInOffer($disciplineId, $offerId);

		return $disciplineExists;
	}

	public function checkIfClassExistsInDiscipline($offerId, $disciplineId, $classToCheck){

		$this->load->model('offer_model');

		$classExists = $this->offer_model->checkIfClassExistsInDiscipline($offerId, $disciplineId, $classToCheck);

		return $classExists;
	}

	public function getCourseOfferDisciplineByClass($disciplineId, $offerId, $disciplineClass){

		$this->load->model('offer_model');

		$offerDiscipline = $this->offer_model->getCourseOfferDisciplineByClass($disciplineId, $offerId, $disciplineClass);

		return $offerDiscipline;
	}

	private function getOfferDisciplines($idOffer){

		$this->load->model('offer_model');

		$disciplines = $this->offer_model->getOfferDisciplines($idOffer);

		return $disciplines;
	}

	public function getCourseOfferList($courseId, $semester){

		$this->load->model('offer_model');

		$offerLists = $this->offer_model->getCourseOfferList($courseId, $semester);

		return $offerLists;
	}

	public function getOfferBySemesterAndCourse($semesterId, $courseId){

		$this->load->model('offer_model');

		$offer = $this->offer_model->getOfferBySemesterAndCourse($semesterId, $courseId);

		return $offer;
	}

	public function getCourseApprovedOfferListDisciplines($courseId, $semester){

		define("APPROVED_STATUS", "approved");

		$offer = $this->getCourseOfferList($courseId, $semester);

		if($offer !== FALSE){

			$offerListIsApproved = $offer['offer_status'] === APPROVED_STATUS;

			if($offerListIsApproved){
				$disciplines = $this->getOfferDisciplines($offer['id_offer']);
			}else{
				$disciplines = FALSE;
			}
		}else{

			$disciplines = FALSE;
		}

		return $disciplines;
	}

	/**
	 * Get the disciplines classes of an offer list of a specific course in a specific semester
	 * @param $courseId - Id of the course to search for offer lists
	 * @param $semester - Id of the semester to search for
	 * @param $disciplineId - Id of the discipline to search for classes
	 * @return if there is approved offer lists, an Array with the disciplines of the offer list, if does not, return FALSE
	 */
	public function getApprovedOfferListDisciplineClasses($courseId, $semester, $disciplineId){

		$this->load->model('offer_model');

		$offerLists = $this->offer_model->getApprovedOfferListDisciplineClasses($courseId, $semester, $disciplineId);

		return $offerLists;
	}

	public function getProposedOfferLists(){
		$this->load->model('offer_model');

		$offerLists = $this->offer_model->getProposedOfferLists();

		return $offerLists;
	}

	public function getOfferSemester($offerId){
		$this->load->model('offer_model');

		$offerSemester = $this->offer_model->getOfferSemester($offerId);

		return $offerSemester;
	}

	private function createNewOffer(){
		$this->load->model('offer_model');

	}
}
