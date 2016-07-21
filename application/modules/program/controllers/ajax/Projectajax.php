<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODULESPATH."auth/constants/GroupConstants.php";

class ProjectAjax extends MX_Controller {

    public function searchMember(){

        // It could be the name or the CPF of the person
        $memberToSearch = $this->input->post("member");
        $projectId = $this->input->post("project");

        if(!empty($memberToSearch)){
            $this->load->model("auth/usuarios_model");

            // Get the users of teacher and student group only
            $groups = array(
                GroupConstants::STUDENT_GROUP_ID,
                GroupConstants::TEACHER_GROUP_ID
            );
            $users = $this->usuarios_model->getUsersOfGroup($groups, $memberToSearch, $memberToSearch);

            if($users !== FALSE){

                $quantityOfUsers = count($users);
                echo "<h4><i class='fa fa-list'></i> <b>{$quantityOfUsers}</b> usuário(s) encontrado(s):</h4>";

                buildTableDeclaration("add_member_search_table");

                buildTableHeaders(array(
                    'Nome',
                    'CPF',
                    'Ações'
                ));

                foreach ($users as $user){
                    echo "<tr>";
                        echo "<td>";
                            echo $user['name'];
                        echo "</td>";

                        echo "<td>";
                            echo $user['cpf'];
                        echo "</td>";

                        echo "<td>";
                            $submitBtn = array(
                                "class" => "btn btn-primary",
                                "content" => "<i class='fa fa-plus-square'></i> Adicionar à equipe",
                                "type" => "submit"
                            );
                            echo form_open("add_to_team");
                                echo form_hidden("user", $user['id']);
                                echo form_hidden("project", $projectId);
                                echo form_button($submitBtn);
                            echo form_close();
                        echo "</td>";

                    echo "</tr>";
                }

                buildTableEndDeclaration();
            }else{
                callout("info", "Não foram encontrados usuários com o nome ou CPF '".$memberToSearch."'.");
            }
        }
    }
}