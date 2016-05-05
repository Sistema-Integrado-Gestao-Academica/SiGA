<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_aumenta_tamanho_do_nome_do_programa extends CI_Migration{

  public function up(){
    // Fixing the sizeof program name. Some programs have a really big name
    $program_name_size = "ALTER TABLE program MODIFY program_name VARCHAR(80)";
    $this->db->query($program_name_size);

  }

  public function down(){

  }
}
