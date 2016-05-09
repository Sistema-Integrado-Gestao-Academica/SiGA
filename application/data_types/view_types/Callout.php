<?php

abstract class Callout{

    const INFO_TYPE = "info";
    const WARNING_TYPE = "warning";
    const DANGER_TYPE = "danger";

    private $calloutId;
    private $type;
    private $principalMessage;
    private $aditionalMessage;

    public function __construct($type = "info", $principalMessage,
                                $aditionalMessage = FALSE, $calloutId = FALSE){

        $this->setCalloutId($calloutId);
        $this->setType($type);
        $this->setPrincipalMessage($principalMessage);
        $this->setAditionalMessage($aditionalMessage);
    }

    abstract public function draw();

    public function writePrincipalMessage(){

        $principalMessage = $this->getPrincipalMessage();

        if($principalMessage !== FALSE){

            echo "<h4>".$principalMessage."</h4>";
        }
    }

    public function writeAditionalMessage(){

        $aditionalMessage = $this->getAditionalMessage();

        if($aditionalMessage !== FALSE){

            echo "<p>".$aditionalMessage."</p>";
        }
    }

    protected function calloutDeclaration(){

        $calloutId = $this->getCalloutId();
        $calloutType = $this->getType();

        if($calloutId !== FALSE){
            echo "<div id= '".$calloutId."' class=\"callout callout-".$calloutType."\">";
        }else{
            echo "<div class=\"callout callout-".$calloutType."\">";
        }
    }

    protected function calloutEndDeclaration(){
        echo "</div>";
    }

    // Setters
    protected function setCalloutId($calloutId){
        $this->calloutId = $calloutId;
    }

    protected function setType($type){

        switch($type){
            case self::INFO_TYPE:
            case self::WARNING_TYPE:
            case self::DANGER_TYPE:
                break;

            default:
                $type = self::INFO_TYPE;
                break;
        }

        $this->type = $type;
    }

    protected function setPrincipalMessage($principalMessage){
        $this->principalMessage = $principalMessage;
    }

    protected function setAditionalMessage($aditionalMessage){
        $this->aditionalMessage = $aditionalMessage;
    }

    // Getters
    public function getCalloutId(){
        return $this->calloutId;
    }

    public function getType(){
        return $this->type;
    }

    public function getPrincipalMessage(){
        return $this->principalMessage;
    }

    public function getAditionalMessage(){
        return $this->aditionalMessage;
    }
}