
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/SelectionProcessException.php');
require_once(MODULESPATH."/auth/constants/PermissionConstants.php");

class SelectiveProcessHomolog extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('selectionprocess');
        $this->load->model('program/selectiveprocess_model', 'process_model');
        $this->load->model('program/selectiveprocessconfig_model', 'process_config_model');
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );
        $this->load->model(
            'program/selectiveProcessEvaluation_model',
            'process_evaluation_model'
        );

        $this->load->service(
            'program/SelectionProcessEvaluation',
            'evaluation_service'
        );

        $this->load->helper("selectionprocess");
    }

    // List all finalized subscriptions to the secretary
    public function subscriptions($processId){
        $process = $this->process_model->getById($processId);
        $self = $this;
        withPermissionAnd(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $process){
                return checkIfUserIsSecretary($process->getCourse());
            },
            function() use ($self, $process){
                $self->subscriptionsPage($process);
            }
        );
    }

    private function subscriptionsPage($process){
        $processId = $process->getId();
        $finalizedSubscriptions = $this
            ->process_subscription_model
            ->getProcessFinalizedSubscriptions($processId);

        $homologatedSubscriptions = $this
            ->process_subscription_model
            ->getProcessHomologatedSubscriptions($processId);

        $rejectedSubscriptions = $this
            ->process_subscription_model
            ->getProcessRejectedSubscriptions($processId);

        $this->load->service(
            'program/SelectionProcessSubscription',
            'subscription_service'
        );
        $subscriptionService = $this->subscription_service;
        $getSubscriptionDocs = function($subscription) use ($subscriptionService){
            return $subscriptionService->getSubscriptionDocs($subscription);
        };

        $evaluationModel = $this->process_evaluation_model;
        $getSubscriptionTeachers = function($subscriptionId) use ($evaluationModel) {
            return $evaluationModel->getEvaluationTeachers($subscriptionId);
        };

        $requiredDocs = $this->process_config_model->getProcessDocs($processId);

        $this->load->model('course_model');
        $researchLines = $this->course_model->getCourseResearchLines($process->getCourse());

        $data = [
            'process' => $process,
            'finalizedSubscriptions' => $finalizedSubscriptions,
            'homologatedSubscriptions' => $homologatedSubscriptions,
            'rejectedSubscriptions' => $rejectedSubscriptions,
            'getSubscriptionDocsService' => $getSubscriptionDocs,
            'getSubscriptionTeachersService' => $getSubscriptionTeachers,
            'requiredDocs' => $requiredDocs,
            'researchLines' => makeDropdownArray(
                $researchLines,
                'id_research_line',
                'description'
            ),
            'countries' => getAllCountries()
        ];

        $this->load->template(
            "program/selection_process_homolog/subscriptions",
            $data
        );
    }

    // List subscription details and option to homologate it
    public function homologate($subscriptionId){
        $subscription = $this
            ->process_subscription_model
            ->getBySubscriptionId($subscriptionId);
        $process = $this->process_model->getById($subscription['id_process']);

        $self = $this;
        withPermissionAnd(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $process){
                return checkIfUserIsSecretary($process->getCourse());
            },
            function() use ($self, $subscription, $process){
                $self->homologateSubscriptionPage($subscription, $process);
            }
        );
    }

    private function homologateSubscriptionPage($subscription, $process){
        $teachers = $this
            ->process_model
            ->getProcessTeachers($process->getId());

        $definedTeachers = $this
            ->process_evaluation_model
            ->getEvaluationTeachers($subscription['id']);
        $definedTeachers = $this->teachersToDataTable($definedTeachers);

        $data = [
            'process' => $process,
            'subscription' => $subscription,
            'teachers' => $teachers,
            'definedTeachers' => json_encode($definedTeachers, JSON_UNESCAPED_UNICODE)
        ];

        $this->load->template(
            "program/selection_process_homolog/homologate",
            $data
        );
    }

    private function teachersToDataTable($teachers){
        $teachers = !empty($teachers) ? $teachers : [];
        $teachersToDisplay = [];
        foreach($teachers as $teacher){
            $removeBtn = "<a id='remove_{$teacher['id']}' class='btn btn-danger'><i class='fa fa-minus-square'></i></a>";
            $teachersToDisplay[] = [
                'id' => $teacher['id'],
                'name' => $teacher['name'],
                'removeBtn' => $removeBtn
            ];
        }
        return $teachersToDisplay;
    }

    // Register the secretary homologation
    public function registerSubscriptionHomologation($subscriptionId){

        $subscriptionTeachers = $this->input->post('subscriptionTeachers');

        $subscription = $this
            ->process_subscription_model
            ->getBySubscriptionId($subscriptionId);
        $process = $this->process_model->getById($subscription['id_process']);

        $self = $this;
        withPermissionAnd(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $process){
                return checkIfUserIsSecretary($process->getCourse());
            },
            function() use ($self, $subscription, $process, $subscriptionTeachers){
                $self->registerHomologation($subscription, $process, $subscriptionTeachers);
            }
        );
    }

    private function registerHomologation($subscription, $process, $subscriptionTeachers){
        try{
            $homologated = $this->evaluation_service->homologateSubscription(
                $subscription, $process, $subscriptionTeachers
            );

            $status = $homologated ? 'success' : 'danger';
            $msg = $homologated
                ? 'Inscrição homologada com sucesso!'
                : 'Não foi possível homologar esta inscrição, confira os dados informados.';
            getSession()->showFlashMessage($status, $msg);

            $redirectToUrl = "/selection_process/homolog/subscriptions/{$process->getId()}";
            $success = ['redirectTo' => $redirectToUrl];
            echo json_encode($success, JSON_UNESCAPED_UNICODE);
        }catch(SelectionProcessException $e){
            $error = [
                'error' => TRUE,
                'message' => $e->getMessage()
            ];
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
        }
    }

    public function reject($subscriptionId){
        $subscription = $this
            ->process_subscription_model
            ->getBySubscriptionId($subscriptionId);
        $process = $this->process_model->getById($subscription['id_process']);

        $self = $this;
        withPermissionAnd(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $process){
                return checkIfUserIsSecretary($process->getCourse());
            },
            function() use ($self, $subscription, $process){
                $self->rejectSubscription($subscription, $process);
            }
        );
    }

    private function rejectSubscription($subscription, $process){
        $rejected = $this->evaluation_service->rejectSubscription($subscription);

        $status = $rejected ? 'success' : 'danger';
        $message = $rejected
            ? 'Inscrição rejeitada com sucesso!'
            : 'Não foi possível rejeitar esta inscrição.';

        getSession()->showFlashMessage($status, $message);
        redirect("selection_process/homolog/subscriptions/{$process->getId()}");
    }
}
