<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer extends CI_Controller {

	public function newOffer(){

		$this->load->model('offer_model');

		$currentSesmester = $this->input->post('current_semester_id');
		$offerData = $this->offer_model->newOffer($currentSesmester);

		loadTemplateSafelyByGroup('secretario', 'offer/new_offer', $offerData);
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
