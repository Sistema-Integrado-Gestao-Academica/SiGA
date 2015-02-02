<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer_model extends CI_Model {

	public function newOffer($currentSemester){

		$offer = array('offer_status' => "proposed");
		$this->createNewOffer($offer);
		
		$proposedOffers = $this->getProposedOffers();

		$offerId = 0;
		foreach($proposedOffers as $offer){
			
			$currentOfferId = $offer['id_offer'];
			
			// Check if the offer id is not already associated to a semester
			$semester = $this->getOfferSemester($currentOfferId);
			if($semester === FALSE){
				$offerId = $currentOfferId;
				break;
			}
		}

		$this->linkOfferToCurrentSemester($offerId, $currentSemester);

		$offerData = array(
			'id_semester' => $currentSemester,
			'id_offer' => $offerId,
			'status' => "proposed"
		);

		return $offerData;
	}

	private function getProposedOffers(){
		define('PROPOSED', "proposed");

		$searchResult = $this->db->get_where('offer', array('offer_status' => PROPOSED));
		$foundOffer = $searchResult->result_array();

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

	public function getProposedOfferLists(){

		$foundOffers = $this->getProposedOffers();

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
