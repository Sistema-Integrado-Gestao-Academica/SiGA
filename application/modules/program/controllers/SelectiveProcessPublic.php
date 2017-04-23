
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/UploadException.php');
require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");
require_once(MODULESPATH."/program/domain/selection_process/SelectionProcess.php");

class SelectiveProcessPublic extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('program/selectiveprocess_model', 'process_model');
        $this->load->model('program/selectiveprocessconfig_model', 'process_config_model');
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );
    }

    // List all open selective processes
    public function index(){

        $this->load->model("program/selectiveprocess_model", "process_model");
        $openSelectiveProcesses = $this->process_model->getOpenSelectiveProcesses();

        $courses = $this->getCoursesName($openSelectiveProcesses);

        $data = [
            'openSelectiveProcesses' => $openSelectiveProcesses,
            'courses' => $courses
        ];

        loadTemplateSafelyByPermission(
            PermissionConstants::PUBLIC_SELECTION_PROCESS_PERMISSION,
            "program/selection_process_public/index",
            $data
        );
    }

    public function divulgations($processId){
        $selectiveProcess = $this->process_model->getById($processId);
        $processDivulgations = $this->process_model->getProcessDivulgations($processId);

        $data = array(
            'selectiveprocess' => $selectiveProcess,
            'processDivulgations' => $processDivulgations
        );

        $this->load->helper('selectionprocess');

        loadTemplateSafelyByPermission(
            PermissionConstants::PUBLIC_SELECTION_PROCESS_PERMISSION,
            "program/selection_process_public/divulgations",
            $data
        );
    }

    public function subscribe($processId, $extraData=[]){
        $process = $this->process_model->getById($processId);
        $requiredDocs = $this->process_config_model->getProcessDocs($processId);
        $userData = getSession()->getUserData();
        $userSubscription = $this->process_subscription_model->getByUserAndProcess(
            $processId, $userData->getId()
        );
        $subscriptionDocs = $this->getSubscriptionDocs($userSubscription);
        $this->load->model('course_model');
        $researchLines = $this->course_model->getCourseResearchLines($process->getCourse());

        $data = [
            'process' => $process,
            'requiredDocs' => $requiredDocs,
            'subscriptionDocs' => $subscriptionDocs,
            'userData' => $userData,
            'userSubscription' => $userSubscription,
            'researchLines' => makeDropdownArray(
                $researchLines,
                'id_research_line',
                'description'
            ),
            'filesErrors' => ''
        ];

        loadTemplateSafelyByPermission(
            PermissionConstants::PUBLIC_SELECTION_PROCESS_PERMISSION,
            "program/selection_process_public/subscribe",
            array_merge($data, $extraData)
        );
    }

    private function getSubscriptionDocs($subscription){
        $subscriptionDocs = [];
        if($subscription){
            $subscriptionDocs = $this->process_subscription_model->getSubscriptionDocs(
                $subscription['id']
            );

            // Save the doc ID as the key of array
            $docs = [];
            if($subscriptionDocs){
                foreach($subscriptionDocs as $subscriptionDoc){
                    $docs[$subscriptionDoc['id_doc']] = $subscriptionDoc;
                }
            }
            $subscriptionDocs = $docs;
        }
        return $subscriptionDocs;
    }

    public function subscribeTo($processId){
        $self = $this;
        withPermission(PermissionConstants::PUBLIC_SELECTION_PROCESS_PERMISSION,
            function() use($self, $processId){
                $dataOk = validateWithRule('selection_process_subscription');
                if($dataOk){

                    $this->load->service(
                        'program/SelectionProcessSubscription',
                        'subscription_service'
                    );

                    $candidateData = getSubmittedDataFor('selection_process_subscription');

                    try{
                        $subscribed = $this->subscription_service->newSubscription(
                            $processId,
                            $candidateData
                        );

                        $status = subscribed ? 'success' : 'danger';
                        $msg = subscribed
                            ? 'Sua inscrição foi realizada com sucesso!'
                            : 'Não foi possível realizar sua inscrição, cheque os dados informados.';
                        getSession()->showFlashMessage($status, $msg);

                        if($subscribed){
                            redirect('selection_process/public');
                        }else{
                            $self->subscribe($processId);
                        }
                    }catch(UploadException $e){
                        getSession()->showFlashMessage(
                            'danger',
                            "Erro nos <a href='#required_docs'>documentos submetidos</a>."
                        );
                        $self->subscribe($processId, ['filesErrors' => $e->getErrorData()]);
                    }
                }else{
                    $self->subscribe($processId);
                }
            }
        );
    }

    public function dowloadSubscriptionDoc($docId, $subscriptionId){
        $self = $this;
        withPermission(PermissionConstants::PUBLIC_SELECTION_PROCESS_PERMISSION,
            function() use($self, $docId, $subscriptionId){
                $doc = $self->process_subscription_model->getSubscriptionDoc(
                    $subscriptionId,
                    $docId
                );
                downloadFile($doc['doc_path']);
            }
        );
    }

    private function getCoursesName($openSelectiveProcesses){
        $courses = array();
        if(!empty($openSelectiveProcesses)){
            $this->load->model("program/course_model");
            foreach ($openSelectiveProcesses as $process) {
                $courseId = $process->getCourse();
                $course = $this->course_model->getCourseName($courseId);
                $processId = $process->getId();
                $courses[$processId] = $course;
            }
        }
        return $courses;
    }
}
