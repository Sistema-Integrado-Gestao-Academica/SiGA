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

    foreach($members as $member){
        echo "<tr>";

            echo "<td>";
                echo $member['name'];
                if($member['coordinator']){
                    echo " <span class='label label-primary'>Coordenador</span>";
                }
            echo "</td>";

            echo "<td>";
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