<?php
/**
 * The class in charge of committing the login
 */
class Logger {
	private $config;

	function __construct($config) {
		$this->config = $config;
	}
	
	function log($username, $password): bool {
		$isUsernameSet = isset($this->config[USERSKEY][$username]);
		$doesPasswordMatch = $this->config[USERSKEY][$username] == $password;
		
		if ($isUsernameSet && $doesPasswordMatch) {
			return true;
		} else {
			return false;
		}
	}
}
?>