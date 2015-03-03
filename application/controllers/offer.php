<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');
require_once('course.php');
require_once('discipline.php');
require_once('module.php');
require_once('usuario.php');

class Offer extends CI_Controller {

	public function newOffer($courseId){

		$this->load->model('offer_model');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$offer = array(
			'semester' => $currentSemester['id_semester'],
			'course' => $courseId,
			'offer_status' => "proposed"
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

	public function displayDisciplineClasses($idDiscipline, $idOffer){

		// Get the classes of a discipline in an offer
		$this->load->model('offer_model');
		$offerDisciplineClasses = $this->offer_model->getOfferDisciplineClasses($idDiscipline, $idOffer);

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
			'disciplineData' => $disciplineData,
			'offerDisciplineData' => $offerDisciplineClasses,
			'idOffer' => $idOffer,
			'teachers' => $allTeachers
		);

		loadTemplateSafelyByGroup('secretario', 'offer/offer_discipline_classes', $data);
	}

	public function newOfferDisciplineClass($idDiscipline, $idOffer){

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

			if($mainTeacher !== $secondaryTeacher){
				$classData['secondary_teacher'] = $secondaryTeacher;
			}

			$this->load->model('offer_model');
			$wasSaved = $this->offer_model->addOfferDisciplineClass($classData);

			if($wasSaved){
				$status = "success";
				$message = "Turma cadastrada com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível cadastrar essa turma. Cheque os códigos informados.";
			}

		}else{
			$status = "danger";
			$message = "Dados na forma incorreta.";
		}
		
		$this->session->set_flashdata($status, $message);

		redirect("offer/displayDisciplineClasses/{$idDiscipline}/{$idOffer}");
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

	// public function addDisciplineToOffer($idDiscipline, $idOffer, $idCourse){
		
	// 	$this->load->model('offer_model');

	// 	$wasSaved = $this->offer_model->addDisciplineToOffer($idDiscipline, $idOffer);

	// 	if($wasSaved){
	// 		$status = "success";
	// 		$message = "Disciplina adicionada com sucesso.";
	// 	}else{
	// 		$status = "danger";
	// 		$message = "Não foi possível adicionar essa disciplina. Cheque os códigos informados.";
	// 	}

	// 	$this->session->set_flashdata($status, $message);	
	// 	redirect("offer/addDisciplines/{$idOffer}/{$idCourse}");
	// }

	public function disciplineExistsInOffer($disciplineId, $offerId){

		$this->load->model('offer_model');

		$disciplineExists = $this->offer_model->disciplineExistsInOffer($disciplineId, $offerId);

		return $disciplineExists;
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
