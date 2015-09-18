<br>
<br>

<?php 
$session = $this->session->userdata("current_user");

secretaryReportsTable($session['user']['id']);

?>