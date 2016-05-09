<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_de_tipos_de_cursos extends CI_Migration {

	public function up() {
		// Course type table
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT', 'auto_increment' => true),
			'description' => array('type' => 'VARCHAR(255)')
		));

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('course_type', true);

		// Inserting data
		$object = array('description' => 'EAD');
		$this->db->insert('course_type', $object);
		$object = array('description' => 'Doutorado');
		$this->db->insert('course_type', $object);
		$object = array('description' => 'Mestrado Profissional');
		$this->db->insert('course_type', $object);
		$object = array('description' => 'Mestrado Acadêmico');
		$this->db->insert('course_type', $object);

		$field = array('course_type' => array(
			'name' => 'course_type_id',
			'type' => 'INT'
		));
		$this->dbforge->modify_column('course', $field);

		// $this->db->query("ALTER TABLE course ADD CONSTRAINT IDCOURSE_TYPE_FK FOREIGN KEY (course_type_id) REFERENCES course_type(id)");
	}

	public function down() {
		$field = array('course_type_id' => array(
			'name' => 'course_type',
			'type' => 'varchar(20)'
		));
		$this->dbforge->modify_column('course', $field);

		$this->dbforge->drop_table('course_type');
	}

}

/* End of file 033_cria_tabela_de_tipos_de_cursos.php */
/* Location: ./application/migrations/033_cria_tabela_de_tipos_de_cursos.php */ ?>