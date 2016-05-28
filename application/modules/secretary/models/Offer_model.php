<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/constants/OfferConstants.php");

class Offer_model extends CI_Model {

	public function newOffer($offer){

		$this->db->insert('offer', $offer);

		$registeredOffer = $this->getOfferBySemesterAndCourse($offer['semester'], $offer['course']);

		if($registeredOffer !== FALSE){
			$wasSaved = TRUE;
		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	public function deleteDisciplineClassOffer($idOffer, $idDiscipline, $class){
		$disciplineOfferToDelete = array('id_offer' => $idOffer, 'id_discipline' => $idDiscipline, 'class' => $class);
		$deletedDisciplineOffer = $this->db->delete('offer_discipline', $disciplineOfferToDelete);
		return $deletedDisciplineOffer;
	}

	public function approveOfferList($idOffer){

		define("APPROVED", "approved");

		$offerExists = $this->checkIfOfferExists($idOffer);

		if($offerExists){

			$offerHaveDisciplines = $this->checkIfOfferHaveDiscipline($idOffer);

			if($offerHaveDisciplines){

				$this->changeOfferStatus($idOffer, APPROVED);

				$registeredStatus = $this->getOfferStatus($idOffer);

				if($registeredStatus === APPROVED){
					$wasApproved = TRUE;
				}else{
					$wasApproved = FALSE;
				}
			}else{
				$wasApproved = FALSE;
			}

		}else{
			$wasApproved = FALSE;
		}

		return $wasApproved;
	}

	private function changeOfferStatus($idOffer, $newStatus){

		$this->db->where('id_offer', $idOffer);
		$this->db->update('offer', array('offer_status' => $newStatus));
	}

	public function updatePlannedOffers($semesterId){

		$semesterOffers = $this->getAllSemesterOffers($semesterId);

		foreach ($semesterOffers as $offer) {
			$status = $offer['offer_status'];
			if($status === OfferConstants::PLANNED_OFFER){

				$this->db->where('id_offer', $offer['id_offer']);
				$this->db->update('offer', array('offer_status' => OfferConstants::PROPOSED_OFFER));
			
			}
		}

	}

	private function getAllSemesterOffers($semesterId){

		$semesterOffers = $this->db->get_where('offer', array('semester' => $semesterId));

		$semesterOffers = $semesterOffers->result_array();

		$semesterOffers = checkArray($semesterOffers);

		return $semesterOffers;
	}

	private function checkIfOfferHaveDiscipline($idOffer){
		$searchResult = $this->db->get_where('offer_discipline', array('id_offer' => $idOffer));

		$foundOfferDisciplines = $searchResult->result_array();

		$haveDisciplines = sizeof($foundOfferDisciplines) > 0;

		return $haveDisciplines;
	}

	private function getOfferStatus($idOffer){

		$this->db->select('offer_status');
		$searchResult = $this->db->get_where('offer', array('id_offer' => $idOffer));

		$foundOffer = $searchResult->row_array();

		if(sizeof($foundOffer) > 0){
			$status = $foundOffer['offer_status'];
		}else{
			$status = FALSE;
		}

		return $status;
	}

	public function getOffer($idOffer){

		$searchResult = $this->db->get_where('offer', array('id_offer' => $idOffer));

		$foundOffer = $searchResult->row_array();

		$foundOffer = checkArray($foundOffer);

		return $foundOffer;
	}

	public function getOfferBySemesterAndCourse($semester, $course){
		$searchResult = $this->db->get_where('offer', array('semester' => $semester, 'course' => $course));
		$foundOffer = $searchResult->row_array();

		$foundOffer = checkArray($foundOffer);

		return $foundOffer;
	}

	public function checkAvailableVacancies($idOfferDiscipline){

		$offerDiscipline = $this->getOfferDisciplineById($idOfferDiscipline);

		if($offerDiscipline !== FALSE){

			define("MIN_VACANCY_QUANTITY_TO_ENROLL", 1);

			$currentVacancies = $offerDiscipline['current_vacancies'];

			if($currentVacancies >= MIN_VACANCY_QUANTITY_TO_ENROLL){
				$thereIsVacancy = TRUE;
			}else{
				$thereIsVacancy = FALSE;
			}

		}else{
			$thereIsVacancy = FALSE;
		}

		return $thereIsVacancy;
	}

	public function subtractOneVacancy($idOfferDiscipline){

		define("NO_VACANCY", 0);

		$offerDiscipline = $this->getOfferDisciplineById($idOfferDiscipline);

		if($offerDiscipline !== FALSE){

			$currentVacancies = $offerDiscipline['current_vacancies'];

			if($currentVacancies != NO_VACANCY){

				$newQuantityOfVacancies = $currentVacancies - 1;

				$this->updateOfferDiscipline($idOfferDiscipline, array('current_vacancies' => $newQuantityOfVacancies));

				// At this point, the offer_discipline exists because was checked on the firs 'if'
				$foundOfferDiscipline = $this->getOfferDisciplineById($idOfferDiscipline);

				if($foundOfferDiscipline['current_vacancies'] == $newQuantityOfVacancies){
					$wasSubtracted = TRUE;
				}else{
					$wasSubtracted = FALSE;
				}
			}else{
				$wasSubtracted = FALSE;
			}

		}else{
			$wasSubtracted = FALSE;
		}

		return $wasSubtracted;
	}

	private function updateOfferDiscipline($idOfferDiscipline, $newData){

		$this->db->where('id_offer_discipline', $idOfferDiscipline);
		$this->db->update('offer_discipline', $newData);
	}

	public function disciplineExistsInOffer($disciplineId, $offerId){

		$foundOfferDisciplines = $this->getOfferDiscipline($disciplineId, $offerId);

		if($foundOfferDisciplines !== FALSE){
			$disciplineExists = TRUE;
		}else{
			$disciplineExists = FALSE;
		}

		return $disciplineExists;
	}

	public function getCourseOfferDisciplineByClass($disciplineId, $offerId, $disciplineClass){

		$offerDiscipline = $this->getClass($disciplineId, $offerId, $disciplineClass);

		return $offerDiscipline;
	}

	public function getOfferDisciplineById($idOfferDiscipline){

		$searchResult = $this->db->get_where('offer_discipline', array('id_offer_discipline' => $idOfferDiscipline));
		$foundOfferDiscipline = $searchResult->row_array();

		$foundOfferDiscipline = checkArray($foundOfferDiscipline);

		return $foundOfferDiscipline;
	}

	public function getOfferDisciplines($idOffer){
		$offerExists = $this->checkIfOfferExists($idOffer);

		if($offerExists){

			$this->db->distinct();
			$this->db->select('discipline.*');
			$this->db->from('discipline');
			$this->db->join('offer_discipline', 'discipline.discipline_code = offer_discipline.id_discipline');
			$this->db->where('offer_discipline.id_offer', $idOffer);
			$disciplines = $this->db->get()->result_array();

			$disciplines = checkArray($disciplines);

		}else{
			$disciplines = FALSE;
		}

		return $disciplines;
	}

	public function getOfferByCourseId($courseId, $semester){

		$this->db->select('id_offer');
		$this->db->from('offer');
		$this->db->where('course', $courseId);
		$this->db->where('semester', $semester);
		$offer = $this->db->get()->row_array();

		$offer = checkArray($offer);

		return $offer;
	}

	public function getOfferDisciplineClasses($idDiscipline, $idOffer){

		$disciplineClasses = $this->getOfferDiscipline($idDiscipline, $idOffer);

		return $disciplineClasses;
	}

	public function addOfferDisciplineClass($classData){

		$offerExists = $this->checkIfOfferExists($classData['id_offer']);

		$this->load->model('program/discipline_model');
		$disciplineExists = $this->discipline_model->checkIfDisciplineExists($classData['id_discipline']);
		$classAlreadyExists = $this->checkIfClassExists($classData['id_offer'], $classData['id_discipline'], $classData['class']);

		$dataIsOk = $offerExists && $disciplineExists && (!$classAlreadyExists);

		if($dataIsOk){

			$this->saveOfferDisciplineClass($classData);

			$registeredClass = $this->getOfferDisciplineClass($classData);

			if($registeredClass !== FALSE){
				$wasSaved = TRUE;
			}else{
				$wasSaved = FALSE;
			}

		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	public function updateOfferDisciplineClass($classData, $oldClass){

		$offerExists = $this->checkIfOfferExists($classData['id_offer']);

		$this->load->model('program/discipline_model');
		$disciplineExists = $this->discipline_model->checkIfDisciplineExists($classData['id_discipline']);

		$classAlreadyExists = $this->checkIfClassExists($classData['id_offer'], $classData['id_discipline'], $classData['class']);
		$classHasChanged = $classData['class'] !== $oldClass;

		// The class need to change and not exists or don't change and exists
		$classIsOk = ($classAlreadyExists && !$classHasChanged) || (!$classAlreadyExists && $classHasChanged);

		$dataIsOk = $offerExists && $disciplineExists && $classIsOk;

		if($dataIsOk){
			$updated = $this->updateOfferDisciplineClassOnDb($classData, $oldClass);

			if ($updated !== FALSE){
				$wasUpdatedSafely = TRUE;
			}else{
				$wasUpdatedSafely = FALSE;
			}
		}else{
			$wasUpdatedSafely = FALSE;
		}

		return $wasUpdatedSafely;
	}

	private function updateOfferDisciplineClassOnDb($classData, $oldClass){
		$where = array('id_offer' => $classData['id_offer'], 'id_discipline' => $classData['id_discipline'], 'class' => $oldClass);

		$this->db->where($where);
		$updated = $this->db->update('offer_discipline', $classData);

		return $updated;
	}

	public function checkIfClassExistsInDiscipline($offerId, $disciplineId, $classToCheck){

		$classExists = $this->checkIfClassExists($offerId, $disciplineId, $classToCheck);

		return $classExists;
	}

	private function checkIfClassExists($idOffer, $idDiscipline, $classToCheck){

		$foundClass = $this->getClass($idDiscipline, $idOffer, $classToCheck);

		if($foundClass !== FALSE){
			$classAlreadyExists = sizeof($foundClass) > 0;
		}else{
			$classAlreadyExists = FALSE;
		}

		return $classAlreadyExists;
	}

	private function getClass($disciplineId, $offerId, $class){

		$conditions = array(
			'id_offer' => $offerId,
			'id_discipline' => $disciplineId,
			'class' => $class
		);

		$foundClass = $this->db->get_where('offer_discipline', $conditions)->row_array();

		$foundClass = checkArray($foundClass);

		return $foundClass;
	}

	private function getOfferDisciplineClass($classData){

		$searchResult = $this->db->get_where('offer_discipline', $classData);
		$foundOfferDisciplineClasses = $searchResult->result_array();

		$foundOfferDisciplineClasses = checkArray($foundOfferDisciplineClasses);

		return $foundOfferDisciplineClasses;
	}

	public function removeDisciplineFromOffer($idDiscipline, $idOffer){

		$offerExists = $this->checkIfOfferExists($idOffer);

		$this->load->model('program/discipline_model');
		$disciplineExists = $this->discipline_model->checkIfDisciplineExists($idDiscipline);

		$dataIsOk = $offerExists && $disciplineExists;

		if($dataIsOk){
			$this->eraseDisciplineFromOffer($idDiscipline, $idOffer);

			$registeredOfferDiscipline = $this->getOfferDiscipline($idDiscipline, $idOffer);

			if($registeredOfferDiscipline !== FALSE){
				$wasRemoved= FALSE;
			}else{
				$wasRemoved = TRUE;
			}

		}else{
			$wasRemoved = FALSE;
		}

		return $wasRemoved;
	}

	private function eraseDisciplineFromOffer($idDiscipline, $idOffer){

		$offerDiscipline = array(
			'id_offer' => $idOffer,
			'id_discipline' => $idDiscipline
		);

		$this->db->delete('offer_discipline', $offerDiscipline);
	}

	private function saveOfferDisciplineClass($classData){

		$this->db->insert('offer_discipline', $classData);
	}

	/**
	 * Used to check if the data previous inserted was saved on offer_discipline table
	 * @param $idDiscipline - Discipline code to search for
	 * @param $idOffer - Offer id to search for
	 */
	private function getOfferDiscipline($idDiscipline, $idOffer){
		$searchResult = $this->db->get_where('offer_discipline', array('id_discipline' => $idDiscipline, 'id_offer' => $idOffer));
		$foundOfferDisciplines = $searchResult->result_array();

		$foundOfferDisciplines = checkArray($foundOfferDisciplines);

		return $foundOfferDisciplines;
	}

	/**
	 * Get the disciplines classes of an offer list of a specific course in a specific semester
	 * @param $courseId - Id of the course to search for offer lists
	 * @param $semester - Id of the semester to search for
	 * @param $disciplineId - Id of the discipline to search for classes
	 * @return if there is approved offer lists, an Array with the disciplines of the offer list, if does not, return FALSE
	 */
	public function getApprovedOfferListDisciplineClasses($courseId, $semester, $disciplineId){

		define("APPROVED_STATUS", "approved");

		$this->db->select('offer_discipline.*');
		$this->db->from('offer');
		$this->db->join('offer_discipline', "offer.id_offer = offer_discipline.id_offer");
		$this->db->where('offer_discipline.id_discipline', $disciplineId);
		$this->db->where('offer.offer_status', APPROVED_STATUS);
		$this->db->where('offer.course', $courseId);
		$this->db->where('offer.semester', $semester);
		$foundOfferClasses = $this->db->get()->result_array();

		$foundOfferClasses = checkArray($foundOfferClasses);

		return $foundOfferClasses;
	}

	public function getProposedOfferLists(){

		$offerLists = $this->getProposedOffers();

		return $offerLists;
	}

	private function getProposedOffers(){

		$searchResult = $this->db->get_where('offer', array('offer_status' => "proposed"));
		$foundOffer = $searchResult->row_array();

		$foundOffer = checkArray($foundOffer);

		return $foundOffer;
	}

	public function getCourseOfferList($courseId, $semesterId){

		$searchResult = $this->db->get_where('offer', array('course' => $courseId, 'semester' => $semesterId));
		$foundOffers = $searchResult->row_array();

		$foundOffers = checkArray($foundOffers);

		return $foundOffers;
	}

	public function getOfferSemester($offerId){

		$offerExists = $this->checkIfOfferExists($offerId);

		if($offerExists){

			$searchResult = $this->db->get_where('semester', array('offer' => $offerId));
			$foundOfferSemester = $searchResult->row_array();

			$foundOfferSemester = checkArray($foundOfferSemester);

		}else{
			$foundOfferSemester = FALSE;
		}

		return $foundOfferSemester;
	}

	private function checkIfOfferExists($offerId){

		$searchResult = $this->db->get_where('offer', array('id_offer' => $offerId));
		$foundOffers = $searchResult->row_array();

		$offerExists = sizeof($foundOffers) > 0;

		return $offerExists;
	}
}
