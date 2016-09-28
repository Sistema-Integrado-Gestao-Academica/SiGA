<h2 class="principal">Equipe do projeto <b><i><?=$project['name'] ?></i></b> </h2>

<?php include 'search_members_form.php'; ?>

<h4><i class="fa fa-group"></i> Membros deste projeto:</h4>

<?php
buildTableDeclaration();

buildTableHeaders(array(
    'Membro',
    'Ações'
));

if($members !== FALSE){

    $haveCoordinator = projectHaveCoordinator($members);

    foreach($members as $member){
        echo "<tr>";

            echo "<td>";
                echo $member['name'];
                if($member['owner']){
                    echo " <span class='label label-default'>Criador do projeto</span>";
                }
                if($member['coordinator']){
                    echo " <span class='label label-primary'>Coordenador</span>";
                }
            echo "</td>";

            echo "<td>";
            if(!$haveCoordinator){
                // Display this option only when the project have no coordinator

                $isTeacher = $this->module->checkUserGroup(GroupConstants::TEACHER_GROUP, $member['id']);
                if($isTeacher && !$member['coordinator']){
                    suggestAsCoordinatorForm($project['id'], $member['id'], $member['coordinator_activation']);
                }
            }
            echo "</td>";

        echo "</tr>";
    }

}else{
    echo "<tr>";
        echo "<td colspan=2>";
            callout("info", "Nenhum participante neste projeto no momento.");
        echo "</td>";
    echo "</tr>";
}

buildTableEndDeclaration();


function suggestAsCoordinatorForm($project, $member, $activation=FALSE){

    $hidden = array(
        'project' => $project,
        'member' => $member
    );

    $submitBtn = array(
        "id" => "make_coordinator_btn",
        "class" => !$activation ? "btn btn-primary btn-flat": "btn btn-warning btn-flat",
        "content" => !$activation ? "Sugerir como coordenador" : "Reenviar solicitação de coordenador",
        "type" => "submit"
    );
    echo form_open('make_project_coordinator');
        echo form_hidden($hidden);
        echo form_button($submitBtn);
    echo form_close();
}

function projectHaveCoordinator($members){
    $haveCoordinator = FALSE;
    foreach ($members as $member){
        if($member['coordinator']){
            $haveCoordinator = TRUE;
            break;
        }
    }

    return $haveCoordinator;
}