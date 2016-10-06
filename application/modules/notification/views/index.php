
<h2 class="principal">Notificar usuários do sistema</h2>

<?php

    $modalBody = function() use ($courses){

        alert(function(){
            $alert = "<h5><p>Escolha um curso para notificar os professores e/ou estudantes participantes.</p></h5>";
            echo $alert;
        });

        echo "<h4 class='text-center'><i class='fa fa-check'></i> Informe a mensagem e o curso que será notificado:</h4><br>";
        echo "<div class='row'>";
            echo "<div class='col-md-8'>";
                echo form_label("Escolha o curso o qual os usuários serão notificados", "courses_to_notify");
                echo form_dropdown("courses_to_notify", $courses, "", "id='courses_to_notify' class='form-control'");
            echo "</div>";
            echo "<div class='col-md-4'>";
                echo form_checkbox(array(
                    'name' => 'email_too',
                    'id' => 'email_too',
                    'value' => "0",
                    'checked' => TRUE,
                ));

                echo form_label("Notificar por email também.", "email_too");
            echo "</div>";
        echo "</div>";
        echo "<br>";

        echo "<div class='row'>";
        echo "<div class='col-md-12'>";
            echo form_label("Mensagem da notificação", "notification_message");
            echo form_textarea(array(
                'id' => 'notification_message',
                'name' => 'notification_message',
                'placeholder' => "Informe a mensagem que será enviada para os usuários.",
                'class' => "form-control",
                'rows' => '5'
            ));
        echo "</div>";
        echo "</div>";

        echo "<br>";
        echo "<h4 class='text-center'><i class='fa fa-check'></i><i class='fa fa-group'></i> Escolha quem notificar:</h4><br>";
        echo "<div class='row'>";
            echo "<div class='col-md-6 text-center'>";
                echo form_checkbox(array(
                    'id' => 'notify_teachers',
                    'name' => 'notify_teachers',
                    'value' => "1",
                    'checked' => TRUE,
                ));
                echo form_label("Professores", "notify_teachers");
            echo "</div>";

            echo "<div class='col-md-6 text-center'>";
                echo form_checkbox(array(
                    'id' => 'notify_students',
                    'name' => 'notify_students',
                    'value' => "1",
                    'checked' => TRUE,
                ));
                echo form_label("Alunos", "notify_students");
            echo "</div>";
        echo "</div>";
    };

    $modalFooter = function(){
        echo "<div id='notification_error_warn'>";
            alert(function(){
                echo "<h5>Escolha quem serão os usuários notificados do curso, <b>professores e/ou alunos</b>.</h5>";
            }, "danger", FALSE, "warning", $dismissible=FALSE);
        echo "</div>";

        echo "<div class='row'>";
            echo form_button(array(
                "id" => "notify_group_of_users_btn",
                "class" => "btn btn-success btn-block btn-lg",
                "content" => "<i class='fa fa-check-circle-o'></i> NOTIFICAR GRUPO!",
                "type" => "submit"
            ));
        echo "</div>";
    };
?>

<?php
    alert(function(){
        $alert = "<h5><p>Você pode notificar um usuário específico ou notificar todos os estudantes e/ou professores de um curso do qual você é secretário(a).</p>";
        echo $alert;
    });
 ?>

<div class="row">
    <button id="notify_users_group" class="btn btn-primary btn-lg btn-flat" data-toggle="modal" data-target="#notify_users_group_modal"> <i class="fa fa-group"></i> Notificar grupo de usuários</button>
</div>

<?php
    newModal("notify_users_group_modal", "Notificar grupos de usuários", $modalBody, $modalFooter, "notify_group_of_users");
?>

<br>
<br>
<h3 class="text-center"><i class="fa fa-user"></i> Notificar usuário específico</h3>

<?php include("_search_user_form.php"); ?>

<script src=<?=base_url("js/user_notification.js")?>></script>