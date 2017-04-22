<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CourseAjax extends MX_Controller {


    public function saveResearchLine(){
        $researchLine = $this->input->post("research_line");
        if($researchLine != "" && $researchLine != NULL){
            $courseId = $this->input->post("course_id");
            $this->load->model("course_model");
            $newResearchLine = array(
                'description' => $researchLine,
                'id_course' => $courseId
            );

            $wasSaved = $this->course_model->saveResearchLine($newResearchLine);
            if ($wasSaved){
                echo "<div class='alert alert-success'>Linha de pesquisa salva com sucesso.</div>";
            }else{
                echo "<div class='alert alert-danger'>Não foi possível salvar o linha de pesquisa do curso.</div>";
            }
            
        }
        else{
            echo "<div class='alert alert-danger'>A linha de pesquisa deve ser preenchida.</div>";
        }
    }
}