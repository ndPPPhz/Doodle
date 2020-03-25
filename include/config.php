<?php
/**
 * The object which manages the config file
 */
class ConfigManager {
	public $config;
	private $config_file;

	private $users = [
		'user0' => 'test0',
		'user1' => 'test1'
	];

	function __construct(string $config_file) {
		$this->config_file = $config_file;

		// If the file extists, decode it and assign it to $config
		if (file_exists($this->config_file)) {
			$this->config = json_decode(file_get_contents($this->config_file), true);
		} else {
			// Otherwise generate a new one from scratch
			$this->config = [
				USERKEY => $this->users,
				DATAKEY => []
			];
			$this->serialize_config($this->config);
		}
	}

	private function serialize_config(array $config) {
		file_put_contents($this->config_file, json_encode($config, JSON_PRETTY_PRINT));
	}

	// Add a new entry to the config file
	function add(array $date, string $user) {
		$this->config[DATAKEY][$user] = array_merge($this->config[DATAKEY][$user] ?? [], $date);
	}

	// Save the last state of the config into the config file
	function save() {
		$this->serialize_config($this->config);
	}

	// Get all the users' time preferences
	function getUsersEntries(): array {
		return $this->config[DATAKEY];
	}
}
?>