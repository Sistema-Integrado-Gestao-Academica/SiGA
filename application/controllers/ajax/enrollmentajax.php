<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/usuario.php");
require_once(APPPATH."/constants/GroupConstants.php");

class EnrollmentAjax extends CI_Controller {

    public function searchGuestUsersToEnroll(){

        $guestName = $this->input->post("guestName");
        $course = $this->input->post("course");

        $user = new Usuario();
        $guests = $user->getUsersOfGroup(GroupConstants::GUEST_USER_GROUP_ID, $guestName);

        if($guests !== FALSE){
            echo "<h3><i class='fa fa-users'></i> Usuários que podem ser matriculados com o nome '{$guestName}':</h3><br>";

            buildTableDeclaration();

            buildTableHeaders(array(
                'Nome',
                'E-mail',
                'Ações'
            ));

            foreach ($guests as $user){
                echo "<tr>";
                    echo "<td>";
                        echo $user['name'];
                    echo "</td>";
                    echo "<td>";
                        echo $user['email'];
                    echo "</td>";
                    echo "<td>";
                        echo anchor("enrollment/enrollStudent/{$course}/{$user['id']}", "Matricular", "class='btn btn-primary'");
                    echo "</td>";

                echo "</tr>";
            }

            buildTableEndDeclaration();
        }else{
            echo "<br>";
            callout("info", "Não existem usuários disponíveis para matrícula com o nome '{$guestName}' no momento.");
        }
    }

}
