<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("NotificationModel.php");

class Bnotification{


	public function sendNotification($notification){

		$model = new NotificationModel();

		$saved = $model->saveNotification($notification);

		return $saved;
	}
}