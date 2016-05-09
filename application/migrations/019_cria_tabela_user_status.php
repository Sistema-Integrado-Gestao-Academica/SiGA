<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_user_status extends CI_Migration {

	public function up() {
		
		// Create table student_status
		$student_status = array(
			'id_status' => array('type' => 'INT', 'auto_increment' => TRUE),
			'status' => array('type' => 'varchar(20)')
		);
		$this->dbforge->add_field($student_status);
		$this->dbforge->add_key('id_status', true);
		$this->dbforge->create_table('user_status', TRUE);

		// Inserting data to student_status
		$status = array("Matriculado", "Qualificado", "Concluído", "Egresso");
		
		$i = 1;
		foreach ($status as $status) {
			$data = array('id_status' => $i, 'status' => $status);
			$this->db->insert('user_status', $data);
			$i++;
		}

		// Adding the status column on the users table
		$status_column = array(
			'status' => array('type' => 'INT', 'null' => true)
		);
		$this->dbforge->add_column('users', $status_column);

		// Adding the foreign key constraint
		$user_status_fk = "ALTER TABLE users ADD CONSTRAINT USER_STATUS_FK FOREIGN KEY (status) REFERENCES user_status(id_status)";
		$this->db->query($user_status_fk);

		// Adding the user 2 status
		$this->db->where('id', 2);
		$this->db->update('users', array('status' => 1));
	}

	public function down() {
		$this->db->query("ALTER TABLE users DROP FOREIGN KEY USER_STATUS_FK");
		$this->dbforge->drop_table('user_status');
		$this->dbforge->drop_column('users', 'status');
	}
}

?>
