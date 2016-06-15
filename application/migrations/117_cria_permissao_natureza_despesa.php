<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_permissao_natureza_despesa extends CI_Migration {

	public function up() {
		
		// Creating permission
		$this->db->insert('permission', array('permission_name' => "Naturezas de despesa", 'route' => "expense_nature", "id_permission"=>32));
		
		// Adding permission to finantial secretary
		$this->db->insert('group_permission', array('id_group' => 10, 'id_permission' => 32));

		// Adding a column to expense nature (type) code
		$this->dbforge->add_column('expense_type', array(
			'code' => array('type' => 'int', "null" => TRUE),
			'status' => array('type' => 'varchar(10)')
		));

		// Copy id column to code column
		$this->db->query("UPDATE expense_type SET code = id");

		// Adding status default to default expense types
		$object = array('status' => 'default');
		
		$this->db->where('id', 339014);
		$this->db->update('expense_type', $object);
		
		$this->db->where('id', 339030);
		$this->db->update('expense_type', $object);
		
		$this->db->where('id', 339033);
		$this->db->update('expense_type', $object);
		
		$this->db->where('id', 339036);
		$this->db->update('expense_type', $object);
		
		$this->db->where('id', 339039);
		$this->db->update('expense_type', $object);
		
		$this->db->where('id', 339147);
		$this->db->update('expense_type', $object);
		
		$this->db->where('id', 449052);
		$this->db->update('expense_type', $object);


	}

	public function down() {
		
	}

}