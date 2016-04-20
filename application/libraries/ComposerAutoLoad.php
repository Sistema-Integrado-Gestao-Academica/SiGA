<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Loads the autoload script from Composer
 */
class ComposerAutoLoad {

    public function __construct() {
        include("./vendor-dependencies/autoload.php");
    }
}