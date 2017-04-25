

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

        $this->load->helper('selectionprocess');
    }

    public function index($processId){
        
        $subscriptionConfigData = $this->getDataSubscriptionConfig($processId);
        $courseId = $subscriptionConfigData['course']['id_course'];
        $defineTeachersData = $this->getDateDefineTeachers($processId, $courseId);

        $data = $subscriptionConfigData + $defineTeachersData;
        $data['phasesIds'] = $this->getPhasesIds($data['process']);
        $data['canNotEdit'] = FALSE;

        loadTemplateSafelyByPermission(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            "program/selection_process_config/index",
            $data
        );
    }

    public function getPhasesIds($process){
        $settings = $process->getSettings();
        $phases = $settings->getPhases();
        $phaseIds = "";
        if($phases){
            $i = 1;
            $length = sizeof($phases);
            foreach ($phases as $phase) {
                $phaseIds .= $phase->getPhaseId();
                if($i != $length){
                    $phaseIds .= ';';
                }
                $i++;
            }
        }

        return $phaseIds;
    }

    public function subscriptionConfig($processId){
        
        $data = $this->getDataSubscriptionConfig($processId);
        loadTemplateSafelyByPermission(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            "program/selection_process_config/subscription_config",
            $data
        );
    }

    public function getDataSubscriptionConfig($processId){
        
        $data = $this->getProcessDocs($processId);
        $processDocs = $data['processDocs'];
        $processDocs = $processDocs ? $processDocs : [];
        $data['processDocs'] = $processDocs;

        $this->load->model("program/course_model");
        $process = $data['process'];
        $courseResearchLines = $this->course_model->getCourseResearchLines($process->getCourse());
        $data['courseResearchLines'] = $courseResearchLines;
        $course = $this->course_model->getCourseById($process->getCourse());
        $data['course'] = $course;

        return $data;
    }

    private function getProcessDocs($processId){
        $process = $this->process_model->getById($processId);

        if(!$process){
            show_404();
        }

        $allDocs = $this->process_config_model->getAvailableDocs();
        $processDocs = $this->process_config_model->getProcessDocs($processId);

        $data = [
            'process' => $process,
            'allDocs' => $allDocs,
            'processDocs' => $processDocs
        ];

        return $data;
    }

    public function saveSubscriptionConfig($processId){
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
                $this->process_model->updateProcessFlags($processId, array('needed_docs_selected' => TRUE));

                $status = $saved ? 'success' : 'danger';
                $message = $saved ? 'Processo Seletivo salvo com sucesso!' : 'Não foi possível salvar os documentos selecionados.';

                $session = getSession();
                $session->showFlashMessage($status, $message);
                $process = $this->process_model->getById($processId);
                $courseId = $process->getCourse();
                redirect("program/selectiveprocess/courseSelectiveProcesses/{$courseId}");
            }
        );
    }

    private function getDateDefineTeachers($processId, $courseId){

        $this->load->model("program/course_model");
        $course = $this->course_model->getCourseById($courseId);
        $data = $this->getDefineTeachersViewData($processId, $course['id_program']);

        return $data;
    }

    private function updateDefineTeacherTables($processId, $programId){
        $data = $this->getDefineTeachersViewData($processId, $programId);
        $teachers = $data['teachers'];
        $processTeachers = $data['processTeachers'];
        include(MODULESPATH.'program/views/selection_process/define_teachers_tables.php');
    }

    public function getDefineTeachersViewData($processId, $programId){

        $session = getSession();
        $user = $session->getUserData();
        $secretaryId = $user->getId();

        $this->load->model('program/program_model');
        $programsTeachers = $this->program_model->getProgramTeachers($programId);

        $processTeachers = $this->process_model->getProcessTeachers($processId);

        $data = array(
            'teachers' => $programsTeachers,
            'processTeachers' => $processTeachers,
            'programId' => $programId
        );

        return $data;
    }

    public function addTeacherToProcess(){
        $processId = $this->input->post('processId');
        $teacherId = $this->input->post('teacherId');
        $programId = $this->input->post('programId');
        $self = $this;
        withPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $processId, $teacherId, $programId){
                $self->process_model->addTeacherToProcess($processId, $teacherId);
                $self->updateDefineTeacherTables($processId, $programId);
            }
        );
    }

    public function removeTeacherFromProcess(){
        $processId = $this->input->post('processId');
        $teacherId = $this->input->post('teacherId');
        $programId = $this->input->post('programId');
        $self = $this;
        withPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $processId, $teacherId, $programId){
                $self->process_model->removeTeacherFromProcess($processId, $teacherId);
                $self->updateDefineTeacherTables($processId, $programId);
            }
        );
    }
}
