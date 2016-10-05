<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class UserNotification extends MX_Controller{

    public function index(){
        loadTemplateSafelyByPermission(
            PermissionConstants::NOTIFY_USERS_PERMISSION,
            "notification/index"
        );
    }

    public function getUsersToNotify(){

        $userToSearch = $this->input->post("user");

        $this->load->model("auth/usuarios_model");

        $users = $this->usuarios_model->getUserByName($userToSearch);

        if(!empty($users)){

            $quantityOfUsers = count($users);
            echo "<h4><i class='fa fa-list'></i> <b>{$quantityOfUsers}</b> usuário(s) encontrado(s):</h4>";

            buildTableDeclaration();

            buildTableHeaders(array(
                'Nome',
                'E-mail',
                'Ações'
            ));

            foreach ($users as $user){
                echo "<tr>";
                    echo "<td>";
                        echo $user['name'];
                    echo "</td>";

                    echo "<td>";
                        echo $user['email'];
                    echo "</td>";

                    echo "<td>";
                        echo form_button(array(
                            "id" => "notify_user_{$user['id']}",
                            "onclick" => "showNotifyUserModal({$user['id']})",
                            "class" => "btn btn-info",
                            "content" => "<i class='fa fa-caret-square-o-right'></i> Notificar!"
                        ));
                    echo "</td>";

                echo "</tr>";
            }

            buildTableEndDeclaration();
        }else{
            callout("info", "Não foram encontrados usuários com o nome <b>'{$userToSearch}'</b>.");
        }
    }

    public function getNotifyUserModal(){

        $userId = $this->input->post("user");

        $this->load->model("auth/usuarios_model");
        $user = $this->usuarios_model->getUserById($userId);

        $title = "Notificar <i><b>{$user['name']}</b></i>";

        $body = function() use ($user){

            alert(function() use ($user){
                $alert = "<h5><p>Por padrão, sua mensagem será disponibilizada na barra de notificações de <i>{$user['name']}</i> (No canto superior direito da tela, no ícone <i class='fa fa-bell-o'></i>).</p>";
                $alert .= "<p>Para enviar também um e-mail com a mesma mensagem, marque a caixa '<b>Notificar por e-mail também</b>'.</p></h5>";
                echo $alert;
            }, "info", "Informações sobre a notificação");

            newInputField('hidden', 'user', $user['id']);

            echo form_label("Mensagem da notificação", "notification_message");
            echo form_textarea(array(
                'id' => 'notification_message',
                'name' => 'notification_message',
                'placeholder' => "Informe a mensagem que será enviada para {$user['name']}.",
                'class' => "form-control",
                'rows' => '5'
            ));

            echo form_checkbox(array(
                'name' => 'email_too',
                'id' => 'email_too',
                'value' => "0",
                'checked' => TRUE,
            ));

            echo form_label("Notificar por email também.", "only_bar");
        };

        $footer = function(){
            echo "<div class='row'>";
                echo form_button(array(
                    "id" => "notify_user",
                    "class" => "btn btn-success btn-block btn-lg",
                    "content" => "<i class='fa fa-check-circle-o'></i> NOTIFICAR!",
                    "type" => "submit"
                ));
            echo "</div>";
        };

        newModal("notify_user_{$user['id']}_modal", $title, $body, $footer, "notify_specific_user");
    }

    public function notifySpecificUser(){

        $userId = $this->input->post("user");
        $message = $this->input->post("notification_message");
        $onlyBar = $this->input->post("email_too") === NULL;

        $this->load->model("auth/usuarios_model");
        $receiver = $this->usuarios_model->getUserById($userId);

        $this->load->module("notification/notification");
        $result = $this->notification->notifyUser($receiver, $message, $handler=FALSE, $sender=FALSE, $onlyBar);

        $barNotificationSent = $result[0];
        $emailSent = $result[1];

        $status = $barNotificationSent ? "success" : "danger";
        $msg = $barNotificationSent ? "Usuário notificado com sucesso!" : "Não foi possível enviar a notificação para o usuário. Cheque os dados informados e tente novamente.";

        $msg .= (!$emailSent && !$onlyBar) ? "<br><b><font color='red'>Não foi possível enviar o e-mail.</font></b>" : "";

        getSession()->showFlashMessage($status, $msg);
        redirect("notify_users");
    }

}

?>