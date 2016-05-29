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

        // Semester data
        $this->load->model("program/semester_model");
        $currentSemester = $this->semester_model->getCurrentSemester();
        $semesterId = $currentSemester['id_semester'];

        // Offer data
        $this->load->model("secretary/offer_model");
        $courseOffer = $this->offer_model->getOfferBySemesterAndCourse($semesterId, $courseId);

        $disciplinesClasses = FALSE;
        if($courseOffer !== FALSE){

            $offerId = $courseOffer['id_offer'];

            $this->load->model("program/discipline_model");
            $disciplinesClasses = $this->discipline_model->getClassesByDisciplineName($disciplineName, $offerId);

        }else{
            $disciplineClasses = FALSE;
        }

        if($disciplinesClasses !== FALSE){

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
                            echo $class['discipline_name']."-".$class["name_abbreviation"];
                            echo "</td>";

                            echo "<td>";
                            echo $class['class'];
                            echo "</td>";

                            echo "<td>";
                            echo $class['current_vacancies'];
                            echo "</td>";

                            echo "<td>";
                            echo anchor("student/temporaryrequest/addTempDisciplineToRequest/{$class['id_offer_discipline']}/{$courseId}/{$userId}","Adicionar à matrícula", "class='btn btn-primary'");
                            echo "</td>";
                        echo "</tr>";
                    }

            echo "</tbody>";
            echo "</table>";
            echo "</div>";

        }else{

            echo "<div class='callout callout-info'>";
            echo "<h4>Não foram encontradas disciplinas com o nome '".$disciplineName."' para a oferta do semestre atual.</h4>";
            echo "<p>Confira se a lista de oferta do semestre atual já foi aprovada.</p>";
            echo "</div>";
        }
    }


}
