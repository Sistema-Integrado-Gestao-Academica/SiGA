<?php
	$user = new Usuario();

	$staffUserData = $user->getUserById($staff['id_user']);

	loadStaffEditForm($staff, $staffUserData);
?>
