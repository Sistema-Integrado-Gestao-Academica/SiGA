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
 * Check if the attribute is empty
 * @param $attribute - Attribute to check
 * @return TRUE if the attribute is empty
 */
function isEmpty($attribute){
    
    if($attribute != ""){
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