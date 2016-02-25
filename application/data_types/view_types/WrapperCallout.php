<?php

require_once(APPPATH."/data_types/view_types/Callout.php");

class WrapperCallout extends Callout{

    private $content;

    public function __construct($type = "info", $content = FALSE, $principalMessage = FALSE,
                                $aditionalMessage = FALSE, $calloutId = FALSE){

        parent::__construct($type, $principalMessage, $aditionalMessage, $calloutId);

        $this->setContent($content);
    }

    public function draw(){

        $this->calloutDeclaration();
        $this->writePrincipalMessage();
        $this->writeCleanContent();
        $this->writeAditionalMessage();
        $this->calloutEndDeclaration();
    }

    public function writeCalloutDeclaration(){
        $this->calloutDeclaration();
    }

    public function writeCalloutEndDeclaration(){
        $this->calloutEndDeclaration();
    }

    private function writeCleanContent(){

        $content = $this->getContent();

        if($content !== FALSE){
            echo $content;
        }
    }

    // Setter
    private function setContent($content){
        $this->content = $content;
    }

    // Getter
    public function getContent(){
        return $this->content;
    }
}