<br>
<br>

<?php 

require_once(APPPATH."/controllers/security/session/SessionManager.php");

$session = SessionManager::getInstance(); 
$user = $session->getUserData();
$userId = $user->getId();
studentsReportsTable($userId);

?>