<br>
<br>

<?php 
$session = $this->session->userdata("current_user");

mastermindReportsTable($session['user']['id']);

?>