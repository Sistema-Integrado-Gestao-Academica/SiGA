<?php
class Migration_Cria_tabela_de_permissoes_de_usuario extends CI_migration {

	public function up() {
		// Permission table
		$this->dbforge->add_field(array(
				'id_permission' => array('type' => 'INT','auto_increment' => true),
				'permission_name' => array('type' => 'varchar(20)'),
				'route' => array('type' =>'VARCHAR(20)','null' => FALSE)
		));
		$this->dbforge->add_key('id_permission', true);
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
	}
}
