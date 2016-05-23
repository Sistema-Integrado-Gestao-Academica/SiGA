<?php  

require_once(APPPATH."/controllers/security/session/SessionManager.php");

$session = SessionManager::getInstance(); 
$user = $session->getUserData();
$userName = $user->getName();
?>
<h2 align="center">Bem vindo à página de secretarias</h2>

<h3>Bem vindo secretário <?=ucfirst($userName)?></h3>
<br><br>
<h4 align='center'> As secretarias designadas a você são: </h4>
