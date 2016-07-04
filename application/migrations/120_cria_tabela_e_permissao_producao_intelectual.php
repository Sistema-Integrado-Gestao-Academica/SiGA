<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_cria_tabela_e_permissao_producao_intelectual extends CI_Migration {

	public function up() {
		
		// Increasing route collumn size on permission table
		$this->db->query("ALTER TABLE permission MODIFY route varchar(30)");

		// Creating permission
		$this->db->insert('permission', array('permission_name' => "Produção Intelectual", 'route' => "intellectual_production", "id_permission"=>34));
		
		// Adding permission to teacher
		$this->db->insert('group_permission', array('id_group' => 5, 'id_permission' => 34));
		
		// Adding permission to student
		$this->db->insert('group_permission', array('id_group' => 7, 'id_permission' => 34));

		// Expense details table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'type' => array('type' => 'VARCHAR(25)', 'NULL' => true),
			'subtype' => array('type' => 'VARCHAR(25)', 'NULL' => true),
			'title' => array('type' => 'VARCHAR(255)'),
			'year' => array('type' => 'INT(4)', 'NULL' => true),
			'periodic' => array('type' => 'VARCHAR(55)', 'NULL' => true),
			'qualis' => array('type' => 'VARCHAR(2)', 'NULL' => true),
			'identifier' => array('type' => 'VARCHAR(13)', 'NULL' => true),
			'author' => array('type' => 'INT')
		));

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('intellectual_production', true);

		$this->db->query("ALTER TABLE intellectual_production ADD CONSTRAINT ID_USER_INTELLECTUAL_PRODUCTION_FK FOREIGN KEY (author) REFERENCES users(id)");
	}

	public function down() {
		
	}

}