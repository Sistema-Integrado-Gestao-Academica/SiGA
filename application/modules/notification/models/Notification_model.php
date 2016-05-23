<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."notification/domain/ActionNotification.php");

class Notification_model extends CI_Model{
	
	const NOTIFICATION_TABLE = "notification";
	const ID_COLUMN = "id_notification";
	const USER_COLUMN = "id_user";
	const CONTENT_COLUMN = "content";
	const SEEN_COLUMN = "seen";
	const LINK_COLUMN = "link";
	const TYPE_COLUMN = "type";
	const TIME_COLUMN = "time";

	const MAX_NOTIFICATIONS_ON_BAR = 10;

	private function getCIInstance(){
		$ci =& get_instance();
		return $ci;
	}

	public function saveNotification($notification){

		$saveNotification = $this->createSaveNotificationQuery($notification);

		$ci = $this->getCIInstance();

		// Try to save
		$ci->db->trans_start();

		$ci->db->query($saveNotification);

		$ci->db->trans_complete();

		// Get result
        if($ci->db->trans_status() === FALSE){
            $saved = FALSE;
        }else{
        	$saved = TRUE;
        }

        return $saved;
	}

	private function createSaveNotificationQuery($notification){

		if($notification->seen()){
			$seen = 1;
		}else{
			$seen = 0;
		}

		$saveNotification = "INSERT INTO ".self::NOTIFICATION_TABLE."(".self::USER_COLUMN.", ".self::CONTENT_COLUMN.", ".self::SEEN_COLUMN.", ".self::TYPE_COLUMN.", ";

		if($notification->type() === ActionNotification::class){
			$saveNotification .= self::LINK_COLUMN.", ";
		}
		
		$saveNotification .= self::TIME_COLUMN.")";

		$saveNotification .= " VALUES('".$notification->user()->getId()."', '";
		$saveNotification .= $notification->content()."', ";
		$saveNotification .= $seen.", '";
		$saveNotification .= $notification->type()."', ";

		if($notification->type() === ActionNotification::class){
			$saveNotification .= "'".$notification->link()."', ";
		}

		$saveNotification .= " CURRENT_TIMESTAMP)";

		return $saveNotification;
	}

	public function getUserNotifications($user){

		$ci = $this->getCIInstance();

		$ci->db->where(self::USER_COLUMN, $user->getId());
		$ci->db->order_by(self::SEEN_COLUMN, "asc");
		$ci->db->order_by(self::TIME_COLUMN, "desc");
		$ci->db->limit(self::MAX_NOTIFICATIONS_ON_BAR);
		$notifications = $ci->db->get(self::NOTIFICATION_TABLE)->result_array();

		$notifications = checkArray($notifications);

		return $notifications;
	}

	public function setNotificationSeen($notificationId){

		$ci = $this->getCIInstance();

		$ci->trans_start();

		$ci->db->where(self::ID_COLUMN, $notificationId);
		$ci->db->update(self::NOTIFICATION_TABLE, array(self::SEEN_COLUMN => 1));

		$ci->db->trans_complete();

		// Get result
        if($ci->db->trans_status() === FALSE){
            $updated = FALSE;
        }else{
        	$updated = TRUE;
        }

        return $updated;
	}
}