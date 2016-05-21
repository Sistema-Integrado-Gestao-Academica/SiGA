<br>
<?php 
	displayUserGroups($idUser, $userGroups);
	echo "<br>";
	displayAllGroupsToUser($idUser, $allGroups, $userGroups);
	echo "<br>";

	echo anchor('user_report', "Voltar", "class='btn btn-danger'");
?>