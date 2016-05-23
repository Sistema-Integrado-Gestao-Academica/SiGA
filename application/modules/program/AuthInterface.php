<?php

interface AuthInterface{
	
	public static function getSession();

	/*
	 * Group methods 
	 */
	public static function getGroupByName($name);
	public static function checkUserGroup($group);
	public static function usersToSecretary();
}