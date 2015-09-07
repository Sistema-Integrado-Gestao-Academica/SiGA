<br>
<br>

<?php 
$session = $this->session->userdata("current_user");

studentsReportsTable($session['user']['id']);

?>