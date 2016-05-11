<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/libraries/NotificationModel.php");

class NotificationAjax extends CI_Controller {

    public function setNotificationSeen(){

        $notificationId = $this->input->post("notification");

        $this->db->where(NotificationModel::ID_COLUMN, $notificationId);
        $this->db->update(NotificationModel::NOTIFICATION_TABLE, array(NotificationModel::SEEN_COLUMN => TRUE));
    }
}