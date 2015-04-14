<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('discipline.php');

class Syllabus extends CI_Controller {

	public function courseSyllabus($courseId){

		$foundSyllabus = $this->getCourseSyllabus($courseId);

		if($foundSyllabus !== FALSE){
			$syllabusDisciplines = $this->getSyllabusDisciplines($foundSyllabus['id_syllabus']);
		}else{
			$syllabusDisciplines = FALSE;
		}

		$course = new Course();
		$foundCourse = $course->getCourseById($courseId);

		$data = array(
			'syllabusDisciplines' => $syllabusDisciplines,
			'course' => $foundCourse,
		);

		loadTemplateSafelyByGroup("estudante",'syllabus/course_syllabus_disciplines_student', $data);
	}

	public function getCourseSyllabus($courseId){
		
		$this->load->model('syllabus_model');
		
		$courseSyllabus = $this->syllabus_model->getCourseSyllabus($courseId);

		return $courseSyllabus;
	}

	public function getSyllabusCourse($syllabusId){

		$this->load->model('syllabus_model');
		
		$syllabusCourse = $this->syllabus_model->getSyllabusCourse($syllabusId);

		return $syllabusCourse;	
	}

	public function newSyllabus($courseId){

		$this->load->model('syllabus_model');

		$wasSaved = $this->syllabus_model->newSyllabus($courseId);

		if($wasSaved){
			$status = "success";
			$message = "Currículo criado com sucesso. Adicione disciplinas em EDITAR.";
		}else{
			$status = "danger";
			$message = "Não foi possível criar o currículo para o curso informado. Tente novamente.";
		}
		
		$this->session->set_flashdata($status, $message);	
		redirect('usuario/secretary_courseSyllabus');
	}

	public function displayDisciplinesOfSyllabus($syllabusId, $courseId){

		$syllabusDisciplines = $this->getSyllabusDisciplines($syllabusId);

		$course = new Course();
		$foundCourse = $course->getCourseById($courseId);

		$data = array(
			'syllabusDisciplines' => $syllabusDisciplines,
			'syllabusId' => $syllabusId,
			'course' => $foundCourse
		);

		loadTemplateSafelyByGroup("secretario",'syllabus/course_syllabus_disciplines', $data);
	}

	public function addDisciplines($syllabusId, $courseId){

		$discipline = new Discipline();

		$allDisciplines = $discipline->getAllDisciplines();

		$data = array(
			'allDisciplines' => $allDisciplines,
			'syllabusId' => $syllabusId,
			'courseId' => $courseId
		);

		loadTemplateSafelyByGroup("secretario",'syllabus/add_syllabus_disciplines', $data);
	}

	public function addDisciplineToSyllabus($syllabusId, $disciplineId, $courseId){
		
		$this->load->model('syllabus_model');

		$wasSaved = $this->syllabus_model->addDisciplineToSyllabus($syllabusId, $disciplineId);

		if($wasSaved){
			$status = "success";
			$message = "Disciplina adicionada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível adicionar a disciplina ao currículo informado. Tente novamente.";
		}
		
		$this->session->set_flashdata($status, $message);	
		redirect("syllabus/addDisciplines/{$syllabusId}/{$courseId}");
	}

	public function removeDisciplineFromSyllabus($syllabusId, $disciplineId, $courseId){

		$this->load->model('syllabus_model');

		$wasRemoved = $this->syllabus_model->removeDisciplineFromSyllabus($syllabusId, $disciplineId);

		if($wasRemoved){
			$status = "success";
			$message = "Disciplina removida com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover a disciplina do currículo informado. Tente novamente.";
		}
		
		$this->session->set_flashdata($status, $message);	
		redirect("syllabus/addDisciplines/{$syllabusId}/{$courseId}");
	}

	public function disciplineExistsInSyllabus($idDiscipline, $syllabusId){
		
		$this->load->model('syllabus_model');

		$disciplineExists = $this->syllabus_model->disciplineExistsInSyllabus($idDiscipline, $syllabusId);

		return $disciplineExists;
	}

	private function getSyllabusDisciplines($syllabusId){

		$this->load->model("syllabus_model");

		$foundDisciplines = $this->syllabus_model->getSyllabusDisciplines($syllabusId);

		return $foundDisciplines;
	}

}
