<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer_model extends CI_Model {

	public function newOffer($currentSemester){

		$proposedOffers = $this->getProposedOffers();

		// Means that there is no proposed offers registered
		if($proposedOffers === FALSE){
			
			$offer = array('offer_status' => "proposed");
			$this->createNewOffer($offer);
			$proposedOffers = $this->getProposedOffers();
		}
		
		$offerId = $proposedOffers['id_offer'];

		// $offerId = 0;
		// foreach($proposedOffers as $offer){
			
		// 	$currentOfferId = $offer['id_offer'];
			
		// 	// Check if the offer id is not already associated to a semester
		// 	$semester = $this->getOfferSemester($currentOfferId);
		// 	if($semester === FALSE){
		// 		$offerId = $currentOfferId;
		// 		break;
		// 	}
		// }

		$this->linkOfferToCurrentSemester($offerId, $currentSemester);

		$offerData = array(
			'id_semester' => $currentSemester,
			'id_offer' => $offerId,
			'status' => "proposed"
		);

		return $offerData;
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

	private function createNewOffer($offer){

		$this->db->insert('offer', $offer);
	}

	private function linkOfferToCurrentSemester($offerId, $currentSemester){

		$dataToUpdate = array(
			'offer' => $offerId
		);

		$this->db->where('id_semester', $currentSemester);
		$this->db->update('semester', $dataToUpdate);
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
