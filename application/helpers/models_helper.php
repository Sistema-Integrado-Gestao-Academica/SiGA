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
