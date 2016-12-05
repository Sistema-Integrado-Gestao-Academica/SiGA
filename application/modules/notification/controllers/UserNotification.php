<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class UserNotification extends MX_Controller{

    public function index(){

        $this->load->model("program/course_model");
        $user = getSession()->getUserData();
        $courses = $this->course_model->getCoursesOfSecretary($user->getId());

        $data = array(
            "courses" => makeDropdownArray($courses, 'id_course', 'course_name')
        );

        loadTemplateSafelyByPermission(
            PermissionConstants::NOTIFY_USERS_PERMISSION,
            "notification/index",
            $data
        );
    }

    private function getAllowedUsersToNotify($getStudents=TRUE, $getTeachers=TRUE, $courses=array()){

        $addKeyToUsersArray = function($users){
            $keyUsers = array();
            if(!empty($users)){
                foreach ($users as $user) {
                    $keyUsers[$user['id']] = $user;
                }
            }
            return $keyUsers;
        };

        $this->load->model("program/course_model");

        if(empty($courses)){
            $user = getSession()->getUserData();
            $courses = $this->course_model->getCoursesOfSecretary($user->getId());
        }

        $users = array("students" => array(), "teachers" => array());

        if(!empty($courses)){

            foreach ($courses as $course){
                $courseId = isset($course['id_course']) ? $course['id_course'] : $course;

                // var_dump($courseId);
                if($getTeachers){
                    $teachers = $this->course_model->getCourseTeachers($courseId);
                    // var_dump($teachers);
                    $teachers = $addKeyToUsersArray($teachers);
                    // Used the + operator to preserver numerical indexes
                    $users["teachers"] = $users["teachers"] + $teachers;
                }

                if($getStudents){
                    $students = $this->course_model->getCourseStudents($courseId);
                    $students = $addKeyToUsersArray($students);
                    // Used the + operator to preserver numerical indexes
                    $users["students"] = $users["students"] + $students;
                }
            }
        }

        if($getStudents && $getTeachers){
            $users = $users["teachers"] + $users["students"];
        }elseif($getStudents && !$getTeachers){
            $users = $users["students"];
        }elseif(!$getStudents && $getTeachers){
            $users = $users["teachers"];
        }else{
            $users = array();
        }

        return $users;
    }

    private function filterUsersToNotifyByName($name){

        $allowedUsers = $this->getAllowedUsersToNotify();

        $this->load->model("auth/usuarios_model");
        $foundUsers = $this->usuarios_model->getUserByName($name);

        $users = $foundUsers;

        foreach ( (array) $foundUsers as $key => $foundUser) {
            if(!array_key_exists($foundUser['id'], $allowedUsers)){
                unset($users[$key]);
            }
        }

        return $users;
    }

    public function getUsersToNotify(){

        $userToSearch = $this->input->post("user");

        $users = $this->filterUsersToNotifyByName($userToSearch);

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

            echo form_label("Notificar por email também.", "email_too");
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

    public function notifyGroupOfUsers(){
        $message = $this->input->post("notification_message");
        $course = $this->input->post("courses_to_notify");
        $notifyTeachers = $this->input->post("notify_teachers") !== NULL;
        $notifyStudents = $this->input->post("notify_students") !== NULL;
        $onlyBar = $this->input->post("email_too") === NULL;

        $allowedUsers = $this->getAllowedUsersToNotify($notifyStudents, $notifyTeachers, array($course));

        $this->load->module("notification/notification");

        $sender = getSession()->getUserData();
        $barNotificationSent = array();
        $emailSent = array();
        foreach ($allowedUsers as $user) {
            $result = $this->notification->notifyUser($user, $message, $handler=FALSE, $sender, $onlyBar);

            $barNotificationSent[] = $result[0];
            $emailSent[] = $result[1];
        }

        $checkIfAllTrue = function($array){
            $allTrue = TRUE;
            foreach ($array as $value) {
                if(!$value){
                    $allTrue = FALSE;
                    break;
                }
            }
            return $allTrue;
        };

        $status = $checkIfAllTrue($barNotificationSent) ? "success" : "danger";
        $msg = $checkIfAllTrue($barNotificationSent) ? "Todos usuários foram notificados pelo sistema com sucesso!" : "Não foi possível enviar a notificação para alguns usuários.";

        $msg .= (!$checkIfAllTrue($emailSent) && !$onlyBar) ? "<br><b><font color='red'>Infelizmente não foi possível enviar alguns e-mails.</font></b>" : "";

        getSession()->showFlashMessage($status, $msg);
        redirect("notify_users");
    }

}
