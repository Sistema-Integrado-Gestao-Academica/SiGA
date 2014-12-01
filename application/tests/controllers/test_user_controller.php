<?php
class UserControllerTest extends PHPUnit_Framework_TestCase{
	
	protected $user_controller;
	
	protected function setup(){
		$this->user_controller= new Usuario();
	}
	
	protected function tearDown(){
		unset($this->user_controller);
	}
	
	
	private function testSalvar(){
		
	}
	
}