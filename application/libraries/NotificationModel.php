<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/notification/ActionNotification.php");

class NotificationModel{
	
	const NOTIFICATION_TABLE = "notification";
	const ID_COLUMN = "id_notification";
	const USER_COLUMN = "id_user";
	const CONTENT_COLUMN = "content";
	const SEEN_COLUMN = "seen";
	const LINK_COLUMN = "link";
	const TYPE_COLUMN = "type";
	const TIME_COLUMN = "time";

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
		$saveNotification .= $notification->type()."', '";

		if($notification->type() === ActionNotification::class){
			$saveNotification .= $notification->link()."', ";
		}

		$saveNotification .= " CURRENT_TIMESTAMP)";

		return $saveNotification;
	}
}