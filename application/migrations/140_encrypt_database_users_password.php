<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Encrypt_database_users_password extends CI_Migration {

	public function up() {

        $this->db->select('id, password');
        $users = $this->db->get('users')->result_array();        
        
        foreach ($users as $user) {
            $password = $user['password'];
            $info = password_get_info($password);
            if ($info['algo'] == 'unknown'){
                $newPassword = password_hash($password, PASSWORD_DEFAULT);
                $this->db->where('id', $user['id']);
                $this->db->update("users", array('password' => $newPassword));
            }
        }
	}

	public function down(){
	}
}