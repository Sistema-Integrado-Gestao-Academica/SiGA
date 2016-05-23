<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_colunas_tabela_pagamento extends CI_Migration {

	public function up() {

		$this->dbforge->add_column('payment', array(

			'userType' => array('type' => "varchar(7)"),
			'legalSupport' => array('type' => "TEXT"),

			// Finantial source identification
			'resourseSource' => array('type' => "varchar(40)"),
			'costCenter' => array('type' => "varchar(40)"),
			'dotationNote' => array('type' => "varchar(20)"),
			
			// User identification attributes
			'name' => array('type' => "varchar(70)"),
			'id' => array('type' => "varchar(20)"),
			'pisPasep' => array('type' => "varchar(15)"),
			'cpf' => array('type' => "varchar(11)"),
			'enrollmentNumber' => array('type' => "varchar(10)"),
			'arrivalInBrazil' => array('type' => "date"),
			'phone' => array('type' => "varchar(15)"),
			'email' => array('type' => "varchar(50)"),
			'address' => array('type' => "varchar(50)"),
			'projectDenomination' => array('type' => "varchar(70)"),
			'bank' => array('type' => "varchar(20)"),
			'agency' => array('type' => "varchar(10)"),
			'accountNumber' => array('type' => "varchar(15)"),

			// Propose data
			'totalValue' => array('type' => "decimal(10, 2)"),
			'period' => array('type' => "varchar(10)"),
			'weekHours' => array('type' => "varchar(10)"),
			'weeks' => array('type' => "varchar(10)"),
			'totalHours' => array('type' => "varchar(10)"),
			'serviceDescription' => array('type' => "TEXT")
		));	
	}

	public function down() {
		
	}

}