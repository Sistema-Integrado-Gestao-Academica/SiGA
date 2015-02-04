<?php
class Migration_Cria_tabela_de_disciplinas extends CI_migration {

	public function up() {
		// Discipline table
		$this->dbforge->add_field(array(
				'discipline_code' => array('type' => 'INT'),
				'discipline_name' => array('type' => 'varchar(70)'),
				'name_abbreviation' => array('type' => 'varchar(8)'),
				'credits'		  => array('type' => 'INT'),
				'workload'	 	  => array('type' => 'INT')
		));

		$this->dbforge->add_key('discipline_code', true);
		$this->dbforge->add_key('discipline_name', true);
		$this->dbforge->create_table('discipline', true);
		
		$discipline_code_uk = "ALTER TABLE discipline ADD CONSTRAINT DISCIPLINE_CODE_UK UNIQUE (discipline_code)";
		$this->db->query($discipline_code_uk);

		//Adding initial discipline
		$object = array('discipline_code'   => 111, 
						'discipline_name'   => 'Desenho de Software', 
						'name_abbreviation' => 'DSW',
						'credits' 			=> 4,
						'workload' 			=> 60
				  );
		$this->db->insert('discipline', $object);
		
		//Adding Discipline permissions and groups
		$object = array('id_permission' => 9, 'permission_name' => 'Disciplinas', 'route' => 'discipline');
		$this->db->insert('permission', $object);
		
		$object = array('id_group' => 2, 'id_permission' => '9');
		$this->db->insert('group_permission', $object);
		
		$object = array('id_group' => 3, 'id_permission' => '9');
		$this->db->insert('group_permission', $object);
	}

	public function down() {
		$this->dbforge->drop_table('discipline');
	}
}
