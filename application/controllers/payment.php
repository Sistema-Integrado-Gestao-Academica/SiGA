<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	public function newPayment(){
		
		loadTemplateSafelyByGroup('secretario', 'payment/new_payment');
	}

}
