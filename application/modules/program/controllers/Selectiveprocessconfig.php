
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");
require_once(MODULESPATH."/program/domain/selection_process/SelectionProcess.php");

class SelectiveProcessConfig extends MX_Controller {

    public function __construct(){
        $this->load->model('program/selectiveprocess_model', 'process_model');
        $this->load->model('program/selectiveprocessconfig_model', 'process_config_model');
    }

    public function index($processId){
        $process = $this->process_model->getById($processId);

        if(!$process){
            show_404();
        }

        $allDocs = $this->process_config_model->getAvailableDocs();
        $processDocs = $this->process_config_model->getProcessDocs($processId);
        $processDocs = $processDocs ? $processDocs : [];

        $data = [
            'process' => $process,
            'allDocs' => $allDocs,
            'processDocs' => $processDocs
        ];

        loadTemplateSafelyByPermission(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            "program/selection_process_config/index",
            $data
        );
    }

    public function saveDocs($processId){
        $self = $this;
        withPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $processId){
                $allDocs = $self->process_config_model->getAvailableDocs();

                $selectedDocs = [];
                foreach($allDocs as $doc){
                    $docId = $doc['id'];
                    $selectedDoc = $self->input->post("doc_{$docId}");
                    if(!is_null($selectedDoc)){
                        $selectedDocs[$docId] = ['id' => $selectedDoc];
                    }
                }

                $saved = $self->process_config_model->addSelectedDocsToProcess($selectedDocs, $processId);

                $status = $saved ? 'success' : 'danger';
                $message = $saved ? 'Documentos salvos com sucesso!' : 'Não foi possível salvar os documentos selecionados.';

                $session = getSession();
                $session->showFlashMessage($status, $message);
                redirect("selection_process/config/{$processId}");
            }
        );
    }
}
