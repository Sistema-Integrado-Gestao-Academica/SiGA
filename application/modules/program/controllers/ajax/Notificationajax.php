<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationAjax extends MX_Controller {

    public function setNotificationSeen(){

        $this->load->model("notification/Notification_model");

        $notificationId = $this->input->post("notification");

        $this->db->where(Notification_model::ID_COLUMN, $notificationId);
        $this->db->update(Notification_model::NOTIFICATION_TABLE, array(Notification_model::SEEN_COLUMN => TRUE));
    }
}