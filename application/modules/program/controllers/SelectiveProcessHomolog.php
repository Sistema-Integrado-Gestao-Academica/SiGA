
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'exception/UploadException.php');
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
                // Check if the logged user is secretary of the process course
                $self->load->model('secretary/secretary_model');
                $userId = getSession()->getUserData()->getId();
                return $self->secretary_model->isSecretaryOfCourse($userId, $process->getCourse());
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

}
