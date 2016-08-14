<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class EnrollmentAjax extends MX_Controller {

    public function searchGuestUsersToEnroll(){

        $guestName = $this->input->post("guestName");
        $course = $this->input->post("course");

        $this->load->model("auth/usuarios_model");
        $courseGuests = $this->usuarios_model->getCourseGuests($course, $guestName);

        if($courseGuests !== FALSE){
            echo "<h3><i class='fa fa-users'></i> Usuários que podem ser matriculados com o nome '{$guestName}':</h3><br>";

            displayGuestForEnrollment($courseGuests, $course);

        }
        else{
            echo "<br>";
            callout("info", "Não existem usuários disponíveis para matrícula com o nome '{$guestName}' no momento.");
        }
    }

    public function searchDisciplinesToRequest(){

        $disciplineName = $this->input->post('disciplineName');
        $courseId = $this->input->post('courseId');
        $userId = $this->input->post('userId');
        $isUpdate = $this->input->post('isUpdate');

        // Semester data
        $this->load->model("program/semester_model");
        $currentSemester = $this->semester_model->getCurrentSemester();
        $semesterId = $currentSemester['id_semester'];

        // Offer data
        $this->load->model("secretary/offer_model");
        $courseOffer = $this->offer_model->getOfferBySemesterAndCourse($semesterId, $courseId);

        $offerId = $courseOffer['id_offer'];
        
        // Check if the day is in enrollment period
        $enrollmentPeriod = $this->offer_model->checkIfIsInEnrollmentPeriod($offerId);

        $disciplinesClasses = FALSE;
        if($courseOffer !== FALSE && $enrollmentPeriod){
            $this->load->model("program/discipline_model");
            $disciplinesClasses = $this->discipline_model->getClassesByDisciplineName($disciplineName, $offerId);
        }
        else{
            $disciplinesClasses = FALSE;
        }
        if($disciplinesClasses !== FALSE){

            $quantityOfClasses = count($disciplinesClasses);
            echo "<h4><i class='fa fa-list'> </i> Turmas disponíveis <b>({$quantityOfClasses})<b></h4>";

            echo "<div class=\"box-body table-responsive no-padding\">";
            echo "<table class=\"table table-bordered table-hover\">";
            echo "<tbody>";

                echo "<tr>";
                echo "<th class=\"text-center\">Código</th>";
                echo "<th class=\"text-center\">Disciplina</th>";
                echo "<th class=\"text-center\">Turma</th>";
                echo "<th class=\"text-center\">Vagas restantes</th>";
                echo "<th class=\"text-center\">Ações</th>";
                echo "</tr>";

                    foreach($disciplinesClasses as $class){
                        echo "<tr>";
                            echo "<td>";
                            echo $class['id_offer_discipline'];
                            echo "</td>";

                            echo "<td>";
                            echo $class['discipline_code']." - ".$class['discipline_name']." - ".$class["name_abbreviation"];
                            echo "</td>";

                            echo "<td>";
                            echo $class['class'];
                            echo "</td>";

                            echo "<td>";
                            $currentVacancies = $class['current_vacancies'];
                            echo $currentVacancies;
                            echo "</td>";

                            if($isUpdate){
                                $requestId = $this->input->post('requestId');
                                $path = "add_discipline_to_request/{$requestId}/{$class['id_offer_discipline']}";
                            }else{
                                $path = "student/temporaryrequest/addTempDisciplineToRequest/{$class['id_offer_discipline']}/{$courseId}/{$userId}";
                            }
                            $attrs = "class='btn btn-primary' ";
                            $attrs .= $currentVacancies == 0 ? "disabled='true'" : "";
                            echo "<td>";
                            echo anchor($path,"Adicionar à matrícula", $attrs);
                            echo "</td>";
                        echo "</tr>";
                    }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";

        }else{

            echo "<div class='callout callout-info'>";
            echo "<h4>Fora do período de matrículas do semestre atual.</h4>";
            echo "</div>";
        }
    }


    public function courseForGuest(){

        $courseId = $this->input->post("courseId");
        $courseName = $this->input->post("courseName");

        $session = getSession();
        $user = $session->getUserData();
        $userId = $user->getId();
        $userName = $user->getName();

        $this->load->model("auth/usuarios_model");
        $success = $this->usuarios_model->updateCourseGuest($userId, $courseId, EnrollmentConstants::CANDIDATE_STATUS);

        echo "<h4>Olá, <b>{$userName}</b>";
        echo "<br><br>";

        if($success){

            echo "<div class='panel panel-success' id='course_guest_panel'>";
            echo "<div class='panel-body' id='course_guest_title'>";
            echo "<b>Curso solicitado:</b> {$courseName}";
            echo "</div>";
            echo "<div class='panel-footer'>";
            echo "<b> Status da solicitação:</b>";
            echo "<span class='label label-info'>Aberta</span>";
            echo "</div>";
            echo "</div>";
        }
        else{

            echo "<div class='callout callout-info'>";
            echo "<h4>Não foi possível solicitar sua inscrição</h4>";
            echo "<p>Tente novamente</p>";
            echo "</div>";
        }
    }
}
