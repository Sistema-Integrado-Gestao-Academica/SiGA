<?php

abstract class IntegrationTestCase extends PHPUnit_Extensions_Database_TestCase{

	// only instantiate pdo once for test clean-up/fixture load
	static private $pdo = null;

	// only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
	private $conn = null;

	final public function getConnection(){
		if ($this->conn === null) {
			if (self::$pdo == null) {
				self::$pdo = new PDO("mysql:dbname=siga_test;host=localhost", "root", "root");
			}

			$this->conn = $this->createDefaultDBConnection(self::$pdo, "siga_test");
		}
		return $this->conn;
	}

	public function getDataSet(){
		return $this->createMySQLXMLDataSet(dirname(__FILE__).'/../siga_test.xml');
	}
}