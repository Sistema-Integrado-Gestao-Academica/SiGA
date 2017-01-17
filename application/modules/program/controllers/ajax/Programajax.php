<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ProgramAjax extends MX_Controller {

    public function addFieldFile(){
        $programId = $this->input->post("program_id");
        $infoId = $this->input->post("info_id");
        $fileName = $this->input->post("field_file");
        $ids = array(
            "p" => $programId
        );

        $fieldId = "field_file";
        $folderName = "portal";
        $allowedTypes = "jpg|png|pdf|jpeg";

        $path = uploadFile($fileName, $ids, $fieldId, $folderName, $allowedTypes);
        if($path){
            $this->load->model("program/program_model");
            $saved = $this->program_model->setFieldFilePath($programId, $infoId, $path);

            if($saved){
                alert(function(){
                    echo "Arquivo incluído com sucesso.";
                }, "success", FALSE);
                $extraInfo = $this->program_model->getInformationFieldByProgram($programId);
                showExtraInfo($extraInfo);
            }
            else{
                alert(function(){
                    echo "Arquivo não foi incluído. Tente novamente.";
                }, "danger", FALSE);
            }
        }
        else{
            $this->load->library('upload');
            $errors = $this->upload->display_errors();
            callout("danger", $errors);

        }
    }

    public function addInformationOnPortal(){
        $programId = $this->input->post("program_id");
        $title = $this->input->post("title");
        $details = $this->input->post("details");
       
        $validTitle = !is_null($title) && !empty($title); 
        if($validTitle){

            $this->load->model("program/program_model");
            $data = array(
                'id_program' => $programId,
                'title' => $title,
                'details' => $details,
                'visible' => TRUE
            );  

            $savedId = $this->program_model->setInformationField($programId, $data);
            
            if($savedId){
                alert(function(){
                    echo "Informação adicionada com sucesso.";
                }, "success", FALSE);
                $hidden = array(
                    "id" => "program_id",
                    "name" => "program_id",
                    "type" => "hidden",
                    "value" => $programId
                );

                $infoHidden = array(
                    "id" => "info_id",
                    "name" => "info_id",
                    "type" => "hidden",
                    "value" => $savedId
                );

                echo form_open_multipart("program/program/addInformationFile", array( 'id' => 'add_field_file_form' ));
                echo form_input($hidden);
                echo form_input($infoHidden);
                
                $fieldFile = array(
                    "name" => "field_file",
                    "id" => "field_file",
                    "type" => "file",
                    "required" => TRUE,
                    "class" => "filestyle",
                    "data-buttonBefore" => "true",
                    "data-buttonText" => "Procurar o arquivo",
                    "data-placeholder" => "Nenhum arquivo selecionado.",
                    "data-iconName" => "fa fa-file",
                    "data-buttonName" => "btn-primary"
                );

                $submitFileBtn = array(
                    "id" => "add_field_file_btn",
                    "class" => "btn btn-primary btn-flat",
                    "content" => "Incluir arquivo",
                    "type" => "submit"
                );
                echo "<br>";
                echo "<div class='row'>";
                    echo form_label("Você pode incluir um arquivo para essa informação. <br><small><i>(Arquivos aceitos '.jpg, .png e .pdf')</i></small>:", "field_file");
                    echo "<div class='col-lg-8'>";
                        echo form_input($fieldFile); 
                    echo "</div>";

                    echo "<div class='col-lg-4'>";
                        echo form_button($submitFileBtn);
                    echo "</div>";
                echo "</div>";
            echo form_close();

            $extraInfo = $this->program_model->getInformationFieldByProgram($programId);
            showExtraInfo($extraInfo);

            }
            else{
                alert(function(){
                    echo "Arquivo não foi incluído. Tente novamente.";
                }, "danger", FALSE);
            }
        }
        else{
            alert(function(){
                    echo "Você deve preencher o título.";
                }, "danger", FALSE);
        }

    }

    public function changeExtraInfoStatus(){
        $infoId = $this->input->post('infoId');

        $this->load->model("program/program_model");
        $info = $this->program_model->getExtraInfoById($infoId);

        if($info['visible']){
            $newStatus = FALSE;
        }
        else{
            $newStatus = TRUE;
        }
        $saved = $this->program_model->changeInfoStatus($infoId, $newStatus);

        if($newStatus){
            $data = array(
                'label' => "<span class='label label-success'>Visível no portal</span>",
                'button' => "<a href='#' onclick='hide_show(\"{$infoId}\")' class='btn btn-danger'>Ocultar no portal</a>"
            );
        }
        else{

            $data = array(
                'label' => "<span class='label label-danger'>Oculto no portal</span>",
                'button' => "<a href='#' onclick='hide_show(\"{$infoId}\")' class='btn btn-success'>Mostrar no portal</a>"
            );
        }

        $json = json_encode($data);
        echo $json;
    }
}