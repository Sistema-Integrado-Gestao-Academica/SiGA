<?php

class TestException extends Exception{

	public function __contruct($message, $exception_code){
		parent::__contruct($message, $exception_code);
	}
}