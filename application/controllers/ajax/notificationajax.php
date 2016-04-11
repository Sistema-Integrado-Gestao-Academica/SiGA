<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationAjax extends CI_Controller {

    public function setNotificationSeen(){

        $notificationId = $this->input->post("notificationId");

        $updated = $this->navbarnotification->setNotificationSeen($notificationId);
        
        if($updated){
            echo "Foi";   
        }else{
            echo "Nao deu";   
        }
    }

}