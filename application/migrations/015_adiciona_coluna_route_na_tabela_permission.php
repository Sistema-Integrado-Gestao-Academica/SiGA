<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Adiciona_coluna_route_na_tabela_permission extends CI_Migration {

	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up() {
		
		// $this->db->truncate('permission');

		$fields = array(
                        'route' => array(
			                        'type' =>'VARCHAR',
			                        'constraint' => '20',
			                        'null' =>FALSE
	                        		)
		);

		$this->dbforge->add_column('permission', $fields);

		// Inserting data
		$names = array('Cadastro', 'Funcionários', 'Setores', 'Funções', 'Departamentos', 'Cursos', 'Plano Orcamentário');
		$routes = array('cadastro', 'funcionarios', 'setores', 'funcoes', 'departamentos', 'cursos', 'plano orcamentario');

		$names_routes = array_combine($names, $routes);

		foreach ($names_routes as $permission_name => $route){
		
			$permission = array('permission_name' => $permission_name, 'route' => $route);
			$this->db->insert('permission', $permission);
		}

	}

	public function down() {
		$this->dbforge->drop_column('permission', 'route');
	}

}

/* End of file 015_adiciona_coluna_route_na_tabela_permission.php */
/* Location: ./application/migrations/015_adiciona_coluna_route_na_tabela_permission.php */