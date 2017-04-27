
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/SelectionProcessException.php');
require_once(MODULESPATH."/auth/constants/PermissionConstants.php");

class SelectiveProcessHomolog extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('program/selectiveprocess_model', 'process_model');
        $this->load->model('program/selectiveprocessconfig_model', 'process_config_model');
        $this->load->model(
            'program/selectiveProcessSubscription_model',
            'process_subscription_model'
        );
    }

    // List all finalized subscriptions to the secretary
    public function subscriptions($processId){
        $process = $this->process_model->getById($processId);
        $self = $this;
        withPermissionAnd(
            PermissionConstants::SELECTION_PROCESS_PERMISSION,
            function() use ($self, $process){
                return $self->checkIfUserIsSecretary($process->getCourse());
            },
            function() use ($self, $processId){
                $self->subscriptionsPage($processId);
            }
        );
    }

    private function subscriptionsPage($processId){
        $process = $this->process_model->getById($processId);
        $finalizedSubscriptions = $this
            ->process_subscription_model
            ->getProcessSubscriptions($processId, $finalized=TRUE);

        $this->load->service(
            'program/SelectionProcessSubscription',
            'subscription_service'
        );
        $subscriptionService = $this->subscription_service;
        $getSubscriptionDocs = function($subscription) use ($subscriptionService){
            return $subscriptionService->getSubscriptionDocs($subscription);
        };

        $requiredDocs = $this->process_config_model->getProcessDocs($processId);

        $data = [
            'process' => $process,
            'finalizedSubscriptions' => $finalizedSubscriptions,
            'getSubscriptionDocsService' => $getSubscriptionDocs,
            'requiredDocs' => $requiredDocs,
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
                return $self->checkIfUserIsSecretary($process->getCourse());
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

        $data = [
            'process' => $process,
            'subscription' => $subscription,
            'teachers' => $teachers
        ];

        $this->load->template(
            "program/selection_process_homolog/homologate",
            $data
        );
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
                return $self->checkIfUserIsSecretary($process->getCourse());
            },
            function() use ($self, $subscription, $process, $subscriptionTeachers){
                $self->registerHomologation($subscription, $process, $subscriptionTeachers);
            }
        );
    }

    private function registerHomologation($subscription, $process, $subscriptionTeachers){
        $this->load->service(
            'program/SelectionProcessEvaluation',
            'evaluation_service'
        );

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

    private function checkIfUserIsSecretary($course){
        // Check if the logged user is secretary of the course
        $this->load->model('secretary/secretary_model');
        $userId = getSession()->getUserData()->getId();
        return $this->secretary_model->isSecretaryOfCourse($userId, $course);
    }

}
