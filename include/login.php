<?php
	/**
	 * 
	 */
	class Logger {
		
		private $config;

		function __construct($config) {
			$this->config = $config;
		}
		
		public function log($username, $password): bool {
			$isUsernameSet = isset($this->config['users'][$username]);
			$doesPasswordMatch = $this->config['users'][$username] == $password;

			if ($isUsernameSet && $doesPasswordMatch) {
				$_SESSION['currentUser'] = $username;
				return true;
			} else {
				return false;
			}
		}
	}
?>