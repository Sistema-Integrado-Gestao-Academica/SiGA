<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_route_na_tabela_permission extends CI_Migration {

	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up() {
		
		// $this->db->truncate('permission');
		$this->dbforge->drop_table('permission');
		
		$fields = array(
			'id_permission' =>array(
						'type' => 'INT',
						'null' => FALSE,
						'auto_increment' => TRUE
					),
                        	'permission_name' => array(
			                        'type' =>'VARCHAR',
			                        'constraint' => '20',
			                        'null' =>FALSE
	                        		),
                        	'route' => array(
			                        'type' =>'VARCHAR',
			                        'constraint' => '20',
			                        'null' =>FALSE
	                        		)
		);

		$this->dbforge->add_field($fields);
		
		$this->dbforge->add_key('id_permission', TRUE);
		$this->dbforge->create_table('permission', true);

		// Inserting data
		$names = array('Cadastro', 'Funcionários', 'Setores', 'Funções', 'Departamentos', 'Cursos', 'Plano Orcamentário');
		$routes = array('cadastro', 'funcionarios', 'setores', 'funcoes', 'departamentos', 'cursos', 'plano orcamentario');

		$names_routes = array_combine($names, $routes);

		$id = 1;
		foreach ($names_routes as $permission_name => $route){
			
			$permission = array('id_permission' => $id, 'permission_name' => $permission_name, 'route' => $route);
			$this->db->insert('permission', $permission);
			$id++;
		}

	}

	public function down() {
		
		$this->dbforge->drop_table('permission');
		
		$fields = array(
			'id_permission' =>array(
						'type' => 'INT',
						'null' => FALSE,
						'auto_increment' => TRUE
					),
                        	'permission_name' => array(
			                        'type' =>'VARCHAR',
			                        'constraint' => '20',
			                        'null' =>FALSE
	                        		)
		);

		$this->dbforge->add_field($fields);
		
		$this->dbforge->add_key('id_permission', TRUE);
		$this->dbforge->create_table('permission', true);

		$routes = array('cadastro', 'funcionarios', 'setores', 'funcoes', 'departamentos', 'cursos', 'plano orcamentario');

		$id = 1;
		foreach ($routes as $permission_name) {
			$permission = array('id_permission' => $id, 'permission_name' => $permission_name);
			$this->db->insert('permission', $permission);
			$id++;
		}

	}

}

/* End of file 015_adiciona_coluna_route_na_tabela_permission.php */
/* Location: ./application/migrations/015_adiciona_coluna_route_na_tabela_permission.php */