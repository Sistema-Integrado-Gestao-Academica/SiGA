<?php

class EventPresentation{

    // Event natures
    const CONGRESS = "Congresso";
    const MEETING = "Encontro";
    const SYMPOSIUM = "Simpósio";
    const SEMINAR = "Seminário";

    // Presentation natures
    const ORAL_COMMUNICATION = "Comunicação oral";
    const POSTER = "Pôster";
    const LECTURE = "Palestra";

    private static $EVENT_NATURES = array(
        0 => self::CONGRESS,
        1 => self::MEETING,
        2 => self::SYMPOSIUM,
        3 => self::SEMINAR
    );

    private static $PRESENTATION_NATURES = array(
    	0 => self::ORAL_COMMUNICATION,
        1 => self::POSTER,
    	2 => self::LECTURE
    );

    public static function getEventNatures(){
    	return self::$EVENT_NATURES;
    }

    public static function getPresentationNatures(){
    	return self::$PRESENTATION_NATURES;
    }
}