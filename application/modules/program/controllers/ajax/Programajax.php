<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ProgramAjax extends MX_Controller {

    public function addInformationOnPortal(){
        $programId = $this->input->post("program_id");
        $data = $this->getDataFromForm($programId);            

        if($data){
            $data['visible'] = TRUE;
            $savedId = $this->program_model->setInformationField($programId, $data);
            if($savedId){
                alert(function(){
                    echo "Informação adicionada com sucesso.";
                }, "success", FALSE);

                $submitFileBtn = array(
                    "id" => "add_field_file_btn",
                    "class" => "btn btn-primary btn-flat",
                    "content" => "Incluir arquivo",
                    "type" => "submit"
                );
                formToAddFile($programId, $savedId, $submitFileBtn);
                $extraInfo = $this->program_model->getInformationFieldByProgram($programId);
                showExtraInfo($extraInfo, $programId);

            }
            else{
                alert(function(){
                    echo "Informação não foi salva. Tente novamente.";
                }, "danger", FALSE);
            }
        }

    }

    public function editInformationOnPortal(){
        $title = $this->input->post("title");
        $programId = $this->input->post("program_id");
        $infoId = $this->input->post("info_id");
        $this->load->model('program/program_model');
        $info = $this->program_model->getExtraInfoById($infoId);

        $titleHasChanged = FALSE;
        if($title != $info['title']){
            $titleHasChanged = TRUE;
        }
        $data = $this->getDataFromForm($programId, $titleHasChanged);            

        if($data){
            $data['id'] = $infoId;
            $saved = $this->program_model->updateInformationField($infoId, $data);
            if($saved){
                alert(function(){
                    echo "Informação alterada com sucesso.";
                }, "success", FALSE);
            }
            else{
                alert(function(){
                    echo "Informação não foi alterada. Tente novamente.";
                }, "danger", FALSE);
            }
        }

    }

    private function getDataFromForm($programId, $checkIfTitleExists = TRUE){
        $title = $this->input->post("title");
        $details = $this->input->post("details");
       
        $validTitle = !is_null($title) && !empty($title);
        if($validTitle){
            $this->load->model("program/program_model");
            $data = array(
                'id_program' => $programId,
                'title' => $title,
                'details' => $details,
            );  
            if($checkIfTitleExists){
                $titleExists = $this->program_model->checkIfTitleExists($title, $programId);
                if($titleExists){
                    alert(function(){
                        echo "Já existe uma informação extra com esse título.";
                    }, "danger", FALSE);
                    $data = FALSE;
                }
            }
        }
        else{
            alert(function(){
                    echo "Você deve preencher o título.";
                }, "danger", FALSE);
            $data = FALSE;
        }

        return $data;
    }

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
            $saved = $this->program_model->updateInformationField($infoId, array('file_path' => $path));

            if($saved){
                alert(function(){
                    echo "Arquivo incluído com sucesso.";
                }, "success", FALSE);
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
                'button' => "<a href='#' onclick='hide_show(\"{$infoId}\")' class='btn btn-danger'><i class='fa fa-eye-slash'></i></a>"
            );
        }
        else{

            $data = array(
                'label' => "<span class='label label-danger'>Oculto no portal</span>",
                'button' => "<a href='#' onclick='hide_show(\"{$infoId}\")' class='btn btn-success'><i class='fa fa-eye'></i></a>"
            );
        }

        $data['link_to_edit'] = "program/editFieldToShowInPortal/{$infoId}";
        $json = json_encode($data);
        echo $json;
    }
}