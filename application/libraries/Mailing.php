<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Mailing {

    public function Mailing() {
        require_once(COMPOSER_DEPENDENCIES.'phpmailer/phpmailer/PHPMailerAutoload.php');
    }
}