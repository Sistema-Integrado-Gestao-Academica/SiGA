<?php

/**
 * Generates a cryptographically secure random string with the given length using openssl
 * @param $length - The length of the string to be generated
 * @param $checkFunction - Optional. If wanted, a string containing the name of
 *        a method or function to be executed to check the validity of the generated string.
 *        The function must return a boolean saying if the generated string is valid or not.
 *        If FALSE, a new string will be generated and then tested again with the given function.
 *        This parameter can be a closure as well that receives one argument
 *        (that will be the generated string) and return a boolean.
 * @param $object - Optional. If $checkFunction is a method, this variable must be
 *        the object in which will be invoked the method
 * @throws Exception if the given function or method does not exists
 */
function generateRandomString($length, $checkFunction=FALSE, $object=FALSE){
    if($object === FALSE && $checkFunction === FALSE){
        $randomStr = bin2hex(openssl_random_pseudo_bytes($length/2));
    }else{

        if($checkFunction instanceof Closure){
            /*
             * In this case the $checkFunction is a closure.
             * This option was made to be compatible with PHP 5.3
             */

            $isValid = FALSE;
            while(!$isValid){
                // Generates a cryptographically secure random string
                $randomStr = bin2hex(openssl_random_pseudo_bytes($length/2));
                $isValid = $checkFunction($randomStr);
            }
        }else{
            if( ($object !== FALSE && method_exists($object, $checkFunction))
                || (!$object && function_exists($checkFunction)) ){
                $isValid = FALSE;
                while(!$isValid){
                    // Generates a cryptographically secure random string
                    $randomStr = bin2hex(openssl_random_pseudo_bytes($length/2));

                    if($object !== FALSE){
                        // In this case $checkFunction is a method of $object
                        $isValid = $object->{$checkFunction}($randomStr);
                    }else{
                        // In this case $checkFunction is a regular function
                        $isValid = $checkFunction($randomStr);
                    }
                }
            }else{
                throw new Exception("The function or method '{$checkFunction}' does not exists.");
            }
        }
    }

    return $randomStr;
}