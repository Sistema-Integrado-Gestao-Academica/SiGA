<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	public function expensePayments(){
		
		loadTemplateSafelyByGroup('secretario', 'payment/new_payment');
	}

}
