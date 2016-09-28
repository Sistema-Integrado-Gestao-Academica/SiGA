<?php

/**
 * Check if an array has elements in it
 * @param $array - Array to check
 * @return The array itself if there is elements in it or FALSE if there is no elements in it
 */
function checkArray($array){

	if(is_array($array)){

		if(sizeof($array) > 0){
			// Nothing to do because
		}else{
			$array = FALSE;
		}
	}else{
		$array = FALSE;
	}

	return $array;
}

function makeDropdownArray(Array $array, $key, $value){

    $dropdownArray = array();

    foreach($array as $element){
        $dropdownArray[$element[$key]] = $element[$value];
    }

    return $dropdownArray;
}

/**
 * Check if an array has a next element
 * @param $array - Array to check
 * @return TRUE if the array have a next element, or FALSE if do not have
 */
function hasNext($array){
    if(is_array($array)){
        if(next($array) === FALSE){
            $hasNext = FALSE;
        }else{
            $hasNext = TRUE;
        }
    }else{
    	$hasNext = FALSE;
    }

    return $hasNext;
}

/**
 * Check if the attribute is empty or null
 * @param $attribute - Attribute to check
 * @return TRUE if the attribute is empty or null
 */
function isEmpty($attribute){

    if(!empty($attribute) && !is_null($attribute)){
        $isEmpty = FALSE;
    }
    else{
    	$isEmpty = TRUE;
    }

    return $isEmpty;
}

function decorateNullData($str){

    if($str == NULL){
        $str = "-";
    }

    return $str;
}