<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class My_PHPMailer {
    //MY_ para informar ao framework de que se trata de uma classe customizada, ou seja, não faz parte do framework. Pode ser alterada no arquivo config.php em application/config/
    public function My_PHPMailer() {
        require_once('PHPMailer/PHPMailerAutoload.php');
    }
}
?>