<?php

require_once(APPPATH."/data_types/view_types/Callout.php");

class AlertCallout extends Callout{

    public function __construct($type = "info", $principalMessage = FALSE,
                                $aditionalMessage = FALSE, $calloutId = FALSE){

        parent::__construct($type, $principalMessage, $aditionalMessage, $calloutId);
    }

    public function draw(){

        $this->calloutDeclaration();
        $this->writePrincipalMessage();
        $this->writeAditionalMessage();
        $this->calloutEndDeclaration();
    }
}