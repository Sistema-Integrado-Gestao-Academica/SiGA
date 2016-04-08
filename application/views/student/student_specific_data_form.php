<?php
$session = $this->session->userdata("current_user");

echo "<br>";
echo "<br>";
displayStudentSpecificDataPage($session['user']['id']);

echo "<br>";
echo "<br>";

displayFormUpdateStudentBasicInformation($session['user']['id']);