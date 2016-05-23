<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Atualiza_tabela_class_hour extends CI_Migration {

    public function up() {

        $this->db->where("hour", "7");
        $this->db->or_where("hour", "8");
        $this->db->or_where("hour", "9");
        $this->db->delete('class_hour');
    }

    public function down() {

    }
}

?>

