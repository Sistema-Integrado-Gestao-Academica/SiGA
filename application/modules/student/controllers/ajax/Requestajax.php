<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/constants/DocumentConstants.php");

class RequestAjax extends MX_Controller {

    public function checkDocumentType(){

        $documentType = $this->input->post('documentType');

        $submitBtn = function(){
            $btn = array(
                "id" => "request_document_btn",
                "class" => "btn bg-primary btn-flat",
                "content" => "Solicitar documento",
                "type" => "submit"
            );

            echo form_button($btn);
        };

        $receiveDocumentOption = function(){
            $option = array(
                'name' => 'receive_option',
                'id' => 'receive_option',
                'value' => TRUE,
                'checked' => TRUE,
            );

            echo "<div class='form-group'>";
            echo form_checkbox($option);
            echo form_label("Este documento pode ser disponibilizado online.", "receive_option");
            echo "<span class='help-block'>Caso contrário você terá que buscar o documento na secretaria.</span>";
            echo "</div>";
        };

        switch($documentType){

            case DocumentConstants::QUALIFICATION_JURY:
                $receiveDocumentOption();
                $submitBtn();
                break;

            case DocumentConstants::DEFENSE_JURY:
                break;

            case DocumentConstants::PASSAGE_SOLICITATION:
                break;

            case DocumentConstants::TRANSFER_DOCS:
                break;

            case DocumentConstants::DECLARATIONS:

                $docRequest = new DocumentConstants();
                $declarationTypes = $docRequest->getDeclarationTypes();

                echo "<div class='form-group'>";
                echo form_label("Escolha o tipo de declaração:", "declarationType");
                echo form_dropdown("declarationType", $declarationTypes, '', "id='declarationType' class='form-control' style='width:40%;'");
                echo"</div>";

                $receiveDocumentOption();
                $submitBtn();
                break;

            case DocumentConstants::OTHER_DOCS:

                $otherDocument = array(
                    "name" => "other_document_request",
                    "id" => "other_document_request",
                    "type" => "text",
                    "class" => "form-campo form-control",
                    "placeholder" => "Informe o nome do  documento desejado aqui.",
                    "maxlength" => "50",
                    'style' => "width:50%;",
                    'required' => TRUE
                );

                echo "<div class='form-group'>";
                echo form_label("Informe o documento desejado:", "other_document_request");
                echo form_input($otherDocument);
                echo "</div>";

                $receiveDocumentOption();
                $submitBtn();
                break;

            default:
                emptyDiv();
                break;
        }
    }

}
