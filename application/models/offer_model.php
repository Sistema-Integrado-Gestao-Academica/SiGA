<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

	private function getOfferBySemesterAndCourse($semester, $course){
		$searchResult = $this->db->get_where('offer', array('semester' => $semester, 'course' => $course));
		$foundOffer = $searchResult->row_array();

		if(sizeof($foundOffer) > 0){
			// Nothing to do
		}else{
			$foundOffer = FALSE;
		}

		return $foundOffer;
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

	public function getOfferDisciplines($idOffer){
		$offerExists = $this->checkIfOfferExists($idOffer);

		if($offerExists){

			$this->db->select('discipline.*');
			$this->db->from('discipline');
			$this->db->join('offer_discipline', 'discipline.discipline_code = offer_discipline.id_discipline');
			$this->db->where('offer_discipline.id_offer', $idOffer);
			$disciplines = $this->db->get()->result_array();

			if(sizeof($disciplines) > 0){
				// Nothing to do 
			}else{
				$disciplines = FALSE;
			}

		}else{
			$disciplines = FALSE;
		}

		return $disciplines;
	}
	
	public function getOfferByCourseId($courseId){
		
		$this->db->select('id_offer');
		$this->db->from('offer');
		$this->db->where('course',$courseId);
		$offer = $this->db->get()->result_array();
		
		return $offer;
		
	}
	
	public function addDisciplineToOffer($idDiscipline, $idOffer){

		$offerExists = $this->checkIfOfferExists($idOffer);

		$this->load->model('discipline_model');
		$disciplineExists = $this->discipline_model->checkIfDisciplineExists($idDiscipline);

		$dataIsOk = $offerExists && $disciplineExists;

		if($dataIsOk){
			$this->saveDisciplineToOffer($idDiscipline, $idOffer);

			$registeredOfferDiscipline = $this->getOfferDiscipline($idDiscipline, $idOffer);

			if($registeredOfferDiscipline !== FALSE){
				$wasSaved = TRUE;
			}else{
				$wasSaved = FALSE;
			}

		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	public function removeDisciplineFromOffer($idDiscipline, $idOffer){

		$offerExists = $this->checkIfOfferExists($idOffer);

		$this->load->model('discipline_model');
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

	private function saveDisciplineToOffer($idDiscipline, $idOffer){

		$offerDiscipline = array(
			'id_offer' => $idOffer,
			'id_discipline' => $idDiscipline
		);

		$this->db->insert('offer_discipline', $offerDiscipline);
	}

	/**
	 * Used to check if the data previous inserted was saved on offer_discipline table
	 * @param $idDiscipline - Discipline code to search for
	 * @param $idOffer - Offer id to search for
	 */
	private function getOfferDiscipline($idDiscipline, $idOffer){
		$searchResult = $this->db->get_where('offer_discipline', array('id_discipline' => $idDiscipline, 'id_offer' => $idOffer));
		$foundOfferDisciplines = $searchResult->result_array();

		if(sizeof($foundOfferDisciplines) > 0){
			// Nothing to do
		}else{
			$foundOfferDisciplines = FALSE;
		}

		return $foundOfferDisciplines;
	}

	private function getProposedOffers(){
		
		$searchResult = $this->db->get_where('offer', array('offer_status' => "proposed"));
		$foundOffer = $searchResult->row_array();

		if(sizeof($foundOffer) > 0){
			// Nothing to do
		}else{
			$foundOffer = FALSE;
		}

		return $foundOffer;
	}

	public function getCourseOfferList($courseId, $semesterId){

		$searchResult = $this->db->get_where('offer', array('course' => $courseId, 'semester' => $semesterId));
		$foundOffers = $searchResult->row_array();

		if(sizeof($foundOffers) > 0){
			// Nothing to do
		}else{
			$foundOffers = FALSE;
		}

		return $foundOffers;
	}

	public function getOfferSemester($offerId){
		
		$offerExists = $this->checkIfOfferExists($offerId);
		
		if($offerExists){

			$searchResult = $this->db->get_where('semester', array('offer' => $offerId));
			$foundOfferSemester = $searchResult->row_array();

			if(sizeof($foundOfferSemester) > 0){
				// Nothing to do
			}else{
				$foundOfferSemester = FALSE;
			}

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
