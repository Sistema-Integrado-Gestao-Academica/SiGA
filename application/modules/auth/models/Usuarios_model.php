<?php

require_once(MODULESPATH."auth/exception/LoginException.php");
require_once(MODULESPATH."secretary/constants/EnrollmentConstants.php");

class Usuarios_model extends CI_Model {

	const USER_TABLE = "users";

	const USER_ID_COLUMN = "id";
	const ACTIVE_COLUMN = "active";

	public function save($user) {
		$this->db->insert("users", $user);
	}

	public function saveGroup($user, $group){

		$user = $this->getUserDataByLogin($user['login']);
		$userId = $user['id'];

		$user_group = array("id_user" => $userId, "id_group" => $group);

		$this->db->insert("user_group",$user_group);
	}

	public function addGroupToUser($idUser, $idGroup){

		$userExists = $this->checkIfUserExists($idUser);

		$this->load->model('module_model');
		$groupExists = $this->module_model->checkIfGroupExists($idGroup);

		$dataIsOk = $userExists && $groupExists;

		if($dataIsOk){

			$this->addUserGroup($idUser, $idGroup);

			$registeredUserGroup = $this->getUserGroupByUserAndGroup($idUser, $idGroup);

			if($registeredUserGroup !== FALSE){
				$wasAdded = TRUE;
			}else{
				$wasAdded = FALSE;
			}

		}else{
			$wasAdded = FALSE;
		}

		return $wasAdded;
	}

	private function addUserGroup($idUser, $idGroup){

		$userGroup = array(
			'id_user' => $idUser,
			'id_group' => $idGroup
		);

		$this->db->insert('user_group', $userGroup);
	}

	public function removeUserGroup($idUser, $idGroup){

		$userExists = $this->checkIfUserExists($idUser);

		$this->load->model('module_model');
		$groupExists = $this->module_model->checkIfGroupExists($idGroup);

		$dataIsOk = $userExists && $groupExists;
		if($dataIsOk){
			$this->deleteUserGroup($idUser, $idGroup);

			$registeredUserGroup = $this->getUserGroupByUserAndGroup($idUser, $idGroup);

			if($registeredUserGroup !== FALSE){
				$wasDeleted = FALSE;
			}else{
				$wasDeleted = TRUE;
			}
		}else{
			$wasDeleted = FALSE;
		}

		return $wasDeleted;
	}

	/**
	 * Used to check if previous added or removed user group from user_group table was correctly done
	 * @param $idUser - User id to search for
	 * @param $idGroup - Group id to search for
	 * @return an array with the found users groups if found or FALSE if does not
	 */
	private function getUserGroupByUserAndGroup($idUser, $idGroup){
		if(!empty($idUser)){
			$this->db->where('id_user', $idUser);
		}
		if(!empty($idGroup)){
			$this->db->where('id_group', $idGroup);
		}
		$searchResult = $this->db->get('user_group');
		$foundUserGroup = $searchResult->result_array();

		$foundUserGroup = checkArray($foundUserGroup);

		return $foundUserGroup;
	}

	private function deleteUserGroup($idUser, $idGroup){

		$userGroup = array(
			'id_user' => $idUser,
			'id_group' => $idGroup
		);

		$this->db->delete('user_group', $userGroup);
	}

	/**
	 * Validate the given user login and password
	 * @param $login - String with the user login
	 * @param $password - String with the user password to check
	 * @return An array with the user data if the login was succeeded
	 * @throws LoginException in case of empty login or password and invalid login or password
	 */
	public function validateUser($login, $password){
		$thereIsLoginAndPassword = !empty($password) && !empty($login);

		if($thereIsLoginAndPassword){

			$loginExists = $this->existsThisLogin($login);
			$passwordIsRight = $this->checkPasswordForThisLogin($password, $login);

			$accessGranted = $loginExists && $passwordIsRight;

			if($accessGranted){

				$userData = $this->getUserDataByLogin($login);

				try{
					$id = $userData['id'];
					$name = $userData['name'];
					$login = $userData['login'];
					$email = $userData['email'];
					$active = $userData['active'];

					$user = new User($id, $name, FALSE, $email, $login, FALSE, FALSE, FALSE, FALSE, $active);

				}catch(UserException $e){
					$user = FALSE;
				}

				return $user;

			}else{
				throw new LoginException("Login ou senha inválidos.");
			}

		}else{
			throw new LoginException("É necessário um login e uma senha para acessar o sistema.");
		}
	}

	public function verifyIfIsTemporaryPassword($userId){

		$this->db->select('temporary_password');
		$user = $this->db->get_where('users', array('id' => $userId));

		$foundPassword = $user->row_array();

		$isTemporaryPassword = $foundPassword['temporary_password'];

		return $isTemporaryPassword;

	}

	public function getUsersOfGroup($idGroup, $name = FALSE){

		$this->db->distinct();
		$this->db->select('users.id, users.name, users.cpf, users.email');
		$this->db->from('users');
		$this->db->join('user_group', "users.id = user_group.id_user");
		$this->db->where('user_group.id_group', $idGroup);

		if($name !== FALSE){
			$this->db->like('users.name', $name);
		}

		$foundUsers = $this->db->get()->result_array();

		$foundUsers = checkArray($foundUsers);

		return $foundUsers;
	}

	public function getGroups($idUser){

		$this->db->select('group.group_name');
		$this->db->from('user_group');
		$this->db->join('group', "group.id_group = user_group.id_group");
		$this->db->where('id_user', $idUser);
		$foundGroups = $this->db->get()->result_array();

		$foundGroups = checkArray($foundGroups);

		return $foundGroups;
	}

	public function removeAllUsersOfGroup($idGroup){

		$this->load->model('module_model');
		$groupExists = $this->module_model->checkIfGroupExists($idGroup);

		if($groupExists){

			$this->deleteAllUsersOfGroup($idGroup);

			$registeredUserGroup = $this->getUserGroupByUserAndGroup('',$idGroup);

			if($registeredUserGroup !== FALSE){
				$wasDeleted = FALSE;
			}else{
				$wasDeleted = TRUE;
			}

		}else{
			$wasDeleted = FALSE;
		}

		return $wasDeleted;
	}

	private function deleteAllUsersOfGroup($idGroup){
		$this->db->where('id_group', $idGroup);
		$this->db->delete('user_group');
	}

	public function getAllUsers(){
		$this->db->select('id, name, cpf, email');
		$this->db->where('name !=', 'admin');
		$foundUsers = $this->db->get('users')->result_array();

		$foundUsers = checkArray($foundUsers);
		return $foundUsers;
	}

	public function getUserByName($userName){

		$foundUser = $this->getUserByPartialName($userName);
		$foundUser = checkArray($foundUser);
		return $foundUser;
	}

	public function getNameByUserId($userId){
		$this->db->select('name');
		$this->db->where('id', $userId);
		$foundName = $this->db->get('users')->result_array();
		$foundName = checkArray($foundName);
		return $foundName[0]['name'];
	}

	private function getUserByPartialName($userName){
		$this->db->select('id, name');
		$this->db->like('name', $userName);
		$foundUser = $this->db->get('users')->result_array();

		$foundUser = checkArray($foundUser);

		return $foundUser;
	}
	
	public function getUserByEmail($email){

		$this->db->select('id, email, name');
		$this->db->from('users');
		$this->db->where("email", $email);

		$foundUser = $this->db->get()->row_array();
		$foundUser = checkArray($foundUser);
	
		if($foundUser !== FALSE){

			$foundUser = $this->getUserDataForEmail($foundUser);
		}

		return $foundUser;
	}

	/**
	 * Get the user data by its login
	 * @param $login - String with the user login
	 * @return An array with the user data if exists or FALSE if does not
	 */
	public function getUserDataByLogin($login){
		$this->db->select('id, name, email, login, active');
		$this->db->where("login", $login);
		$foundUser = $this->db->get("users")->row_array();

		$foundUser = checkArray($foundUser);

		return $foundUser;
	}

	/**
	 * Check if the registered password for the given login match the given password
	 * @param $password - String with the password NOT encrypted
	 * @param $login - String with the login
	 * @return TRUE if the passwords match or FALSE if does not
	 */
	public function checkPasswordForThisLogin($password, $login){
		
		$this->db->select('password');
		$searchResult = $this->db->get_where('users', array('login' => $login));

		$foundPassword = $searchResult->row_array();

		$foundPassword = checkArray($foundPassword);

		if($foundPassword !== FALSE){
			$foundPassword = $foundPassword['password'];
			$encryptedGivenPassword = md5($password);
			$passwordsMatch = $encryptedGivenPassword === $foundPassword;
		}else{
			$passwordsMatch = FALSE;
		}

		return $passwordsMatch;
	}

	/**
	 * Check if a given login exists
	 * @param $loginToCheck - $String with the login to check
	 * @return TRUE if exists in the database or FALSE if does not
	 */
	private function existsThisLogin($loginToCheck){

		$this->db->select('login');
		$searchResult = $this->db->get_where('users', array('login' => $loginToCheck));

		$foundLogin = $searchResult->row_array();

		$wasFound = sizeof($foundLogin) > 0;

		return $wasFound;
	}

	public function addCourseToGuest($userId, $courseId){

		$courseGuest = array(
			'id_user' => $userId,
			'id_course' => $courseId,
			'status' => EnrollmentConstants::CANDIDATE_STATUS
		);

		$success = $this->db->insert('course_guest', $courseGuest);

		return $success;
	}

	public function getCourseGuests($courseId, $guestName = FALSE){
		
		$this->db->select('users.id, users.name, users.cpf, users.email');
		$this->db->from('users');
		$this->db->join('course_guest', "users.id = course_guest.id_user");
		$this->db->where('course_guest.id_course', $courseId);
		$this->db->where('course_guest.status', EnrollmentConstants::CANDIDATE_STATUS);

		if($guestName !== FALSE){
			$this->db->like('users.name', $guestName);
		}
		
		$users = $this->db->get()->result_array();

		$users = checkArray($users);

		return $users;

	}

	public function deleteUserFromCourseGuest($userId){
		
		$courseGuest = array(
			'id_user' => $userId,
		);

		$this->db->delete('course_guest', $courseGuest);
	}

	public function getUserCourse($userId){

		$this->db->select("course.*, course_student.enroll_date, course_student.enrollment");
		$this->db->from('course');
		$this->db->join('course_student', "course.id_course = course_student.id_course");
		$this->db->where('course_student.id_user', $userId);

		$foundCourse = $this->db->get()->result_array();

		$foundCourse = checkArray($foundCourse);

		return $foundCourse;
	}

	/**
	 * Get the registered status for a given user
	 * @param $userId - The user id to search for a status
	 * @return the user status if it exists or "-" if does not
	 */
	public function getUserStatus($userId){

		$userStatusId = $this->getUserStatusId($userId);

		if($userStatusId !== FALSE){
			$userStatus = $this->getUserStatusName($userStatusId);
		}else{
			$userStatus = "-";
		}

		return $userStatus;
	}

	/**
	 * Get the status by its id
	 * @param $statusId - The status id
	 * @return a string with the status
	 */
	private function getUserStatusName($statusId){
		$this->db->select('status');
		$searchResult = $this->db->get_where('user_status', array('id_status' => $statusId));

		$foundStatus = $searchResult->row_array();

		$foundStatus = $foundStatus['status'];

		return $foundStatus;
	}

	/**
	 * Get the user registered status
	 * @param $userId - The user id to search for the status
	 * @return A string with the user status if found, or FALSE if not
	 */
	private function getUserStatusId($userId){
		$this->db->select('status');
		$searchResult = $this->db->get_where('users', array('id' => $userId));

		$foundStatus = $searchResult->row_array();

		if(sizeof($foundStatus) > 0){
			$foundStatus = $foundStatus['status'];
			if($foundStatus === NULL){
				$foundStatus = FALSE;
			}
		}else{
			$foundStatus = FALSE;
		}

		return $foundStatus;
	}

	/**
	 * Function to look if an user is or not a course secretary
	 */
	public function get_user_secretary($user_id){

		$this->db->select('id_course');
		$user_is_secretary = $this->db->get_where("secretary_course",array('id_user'=>$user_id))->row_array();

		$user_is_secretary = checkArray($user_is_secretary);
		if($user_is_secretary){

			$this->db->select('course_name');
			$course_name = $this->db->get_where("course",$user_is_secretary)->row_array();

			$return_secretary = array_merge($user_is_secretary,$course_name);

		}else{
			$return_secretary = FALSE;
		}
		return $return_secretary;
	}

	public function getUserGroupNameByIdGroup($groupId){
		$this->db->select('group_name');
		$group = $this->db->get_where('group', array('id_group'=>$groupId))->row_array();
		$group = checkArray($group);

		$groupName = $group['group_name'];
		return $groupName;
	}

	public function buscaTodos() {
		$this->db->select('id, name');
		$this->db->where('name !=', 'admin');
		$users = $this->db->get('users')->result_array();

		$users_id = array();
		$users_name = array();

		for ($i=0 ; $i < sizeof($users); $i++){
			$users_id[$i] = $users[$i]['id'];
			$users_name[$i] = $users[$i]['name'];
		}
		$return_users = array_combine($users_id, $users_name);

		return $return_users;
	}

	public function getAllSecretaries() {

		$allSecretaries = $this->getSecretaries();

		$secretaries = array();
		foreach($allSecretaries as $secretary){
			$secretaries[$secretary['id']] = $secretary['name'];
		}

		return $secretaries;
	}

	private function getSecretaries(){

		define('SECRETARY_ID', 6);

		$this->db->select('users.id, users.name');
		$this->db->from('users');
		$this->db->join('user_group', 'users.id = user_group.id_user');
		$this->db->where('user_group.id_group', SECRETARY_ID);
		$foundSecretaries = $this->db->get()->result_array();
		$foundSecretaries = checkArray($foundSecretaries);
		return $foundSecretaries;
	}

	public function getUserById($id_user){
		
		$this->db->select('id, name, email, login, active');

		$foundUser = $this->db->get_where('users',array('id'=>$id_user))->row_array();

		$foundUser = checkArray($foundUser);

		return $foundUser;
	}

	public function busca($str, $atributo) {
		$this->db->where($str, $atributo);
		$usuario = $this->db->get("users")->row_array();
		$usuario = checkArray($usuario);
		return $usuario;
	}

	public function altera($user) {
		$this->db->where('login', $user->getLogin());
		$res = $this->db->update("users", array(
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'password' => $user->getPassword()
		));

		return $res;
	}

	public function update($user) {
		
		$this->db->where('id', $user->getId());
		$result = $this->db->update("users", array(
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'password' => $user->getPassword(),
			'home_phone' => $user->getHomePhone(),
			'cell_phone' => $user->getCellPhone()
		));

		return $result;
	}

	public function remove($usuario) {		
		$res = $this->db->delete("users", array("login" => $usuario['login']));
		return $res;
	}

	public function deleteUserById($userId) {		
		
		$userDeleted = $this->removeUserGroup($userId, GroupConstants::GUEST_USER_GROUP_ID);
		$this->db->where('id', $userId);
		$userDeleted = $this->db->delete("users");
		
		return $userDeleted;
	}

	public function updatePassword($id, $newPassword, $temporaryPassword){
		
		$this->db->where('id', $id);
		$this->db->update("users", array(
			'password' => $newPassword,
			'temporary_password' => $temporaryPassword
		));

		$isUpdated = $this->checkIfUpdatePassword($id, $newPassword);

		return $isUpdated;
	}

	public function checkIfUpdatePassword($id, $newPassword){

		$this->db->select('password');
		$foundUser = $this->db->get_where('users', array('id' => $id));
		$foundPassword = $foundUser->row_array();

		if($foundPassword['password'] == $newPassword){

			$isUpdated = TRUE;
		}
		else{
			$isUpdated = FALSE;
		}

		return $isUpdated;
	}

	public function getAllUserGroups(){

		$this->db->select('id_group, group_name');
		$this->db->order_by('group_name','ASC');
		$this->db->from('group');
		$userGroups = $this->db->get()->result_array();
		$userGroups = checkArray($userGroups);
		return $userGroups;
	}

	public function getAllowedUserGroupsForFirstRegistration(){

		$this->db->select('id_group, group_name');
		$where= "group_name = 'convidado'";
		$this->db->where($where);

		$this->db->from('group');
		$userGroups = $this->db->get()->result_array();
		$userGroups = checkArray($userGroups);
		return $userGroups;
	}

	/**
	  * Check if a given user type id is the admin id.
	  * @param $id_to_check - User type id to check
	  * @return True if the id is of the admin, or false if does not.
	  */
	public function checkIfIdIsOfAdmin($id_to_check){

		// The administer name on database
		define("ADMINISTER", "administrador");

		$this->db->select('group_name');
		$this->db->from('group');
		$this->db->where('id_group', $id_to_check);
		$tuple_found = $this->db->get()->row();

		$type_name_found = $tuple_found->type_name;

		if($type_name_found === ADMINISTER){
			$isAdmin = TRUE;
		}else{
			$isAdmin = FALSE;
		}

		return $isAdmin;
	}

	public function checkIfUserExists($idUser){

		$this->db->select('id');
		$searchResult = $this->db->get_where('users', array('id' => $idUser));
		$foundUser = $searchResult->row_array();

		$userExists = sizeof($foundUser) > 0;

		return $userExists;
	}

	public function getUserDataForEmail($foundUser){
		
		if($foundUser != FALSE){

			$id = $foundUser['id'];
			$name = $foundUser['name'];
			$email = $foundUser['email'];
			$user = new User($id, $name, FALSE, $email, FALSE, FALSE, FALSE);
		}
		else{
			$user = NULL;
		}
		return $user;
	}

	private function getUserDataById($userId){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('users.id', $userId);
		$user = $this->db->get()->result_array();

		$user = checkArray($user);

		return $user;
	}


	public function verifyEmailAndPassword($userId, $email, $password){

		$dataIsOk = FALSE;

		$user = $this->getUserById($userId);

		$validEmail = $email == $user['email'];
		if($validEmail){
			$login = $user['login'];
			$dataIsOk = $this->checkPasswordForThisLogin($password, $login);
		}
		else{
			$dataIsOk = FALSE;
		}
		return $dataIsOk;
	}

	public function getObjectUser($userId){

		$foundUsers = $this->getUserDataById($userId);
		
		if($foundUsers != FALSE){
			
			foreach ($foundUsers as $foundUser) {
				$id = $foundUser['id'];
				$name = $foundUser['name'];
				$email = $foundUser['email'];
				$cpf = $foundUser['cpf'];
				$homePhone = $foundUser['home_phone'];
				$cellPhone = $foundUser['cell_phone'];
				$login = $foundUser['login'];
				$password = $foundUser['password'];
				$user = new User($id, $name, $cpf, $email, $login, $password, FALSE, $homePhone, $cellPhone);
			}

		}
		else{
			$user = NULL;
		}

		return $user;
	}

	public function setGuestAsUnknown($userId){
		
		$this->db->where('id_user', $userId);
		$success = $this->db->update("course_guest", array(
			'status' => EnrollmentConstants::UNKNOWN_STATUS
		));

		return $success;		
	}

}
