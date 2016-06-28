<?php

class ProductionType{

	const BIBLIOGRAPHIC = "Bibliográfica";
	const TECHNIQUE = "Técnica";

	// Subtypes for bibliographic type
    const BOOK = "Livro";
    const JOURNAL_ARTICLE = "Artigo em jornal ou revista"; 
    const PERIODIC_ARTICLE = "Artigo em periódico"; 
    const WORK_IN_PROCEEDINGS = "Trabalho em anais";
	const OTHER = "Outro";
	const MUSICAL_SCORE = "Partitura musical";
	const TRANSLATION = "Tradução";

    private static $TYPES = array(
        0 => self::BIBLIOGRAPHIC,
        1 => self::TECHNIQUE,
    );

    private static $SUBTYPES = array(

    	0 => self::BOOK,
    	1 => self::JOURNAL_ARTICLE,
    	2 => self::PERIODIC_ARTICLE,
    	3 => self::WORK_IN_PROCEEDINGS,
		4 => self::OTHER,
		5 => self::MUSICAL_SCORE,
		6 => self::TRANSLATION
    );

    public static function getTypes(){
    	return self::$TYPES;
    }

    public static function getSubtypes(){
    	return self::$SUBTYPES;
    }
}