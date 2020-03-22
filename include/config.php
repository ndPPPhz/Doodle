<?php

/**
 * The Config manager
 */
class ConfigManager {
	private $config_file;
	public $config;

	private $users = [
		'annino' => 'test0',
		'gigi' => 'test1'
	];

	function __construct($config_file) {
		$this->config_file = $config_file;

		// If the file extists, decode it and assign it to $config
		if (file_exists($this->config_file)) {
			$this->config = json_decode(file_get_contents($this->config_file), true);
		} else {
		// Otherwise generate a new one from scratch
			$this->config = [
				'users' => $this->users,
				'data' => []
			];
			$this->serialize_config($this->config);
		}
	}

	private function serialize_config($config) {
		file_put_contents($this->config_file, json_encode($config, JSON_PRETTY_PRINT));
	}

	// Add a new entry to the config file
	public function add($date, $user) {
		$this->config['data'][$user] = array_merge($this->config['data'][$user] ?? [], $date);
	}

	// Save the last config state into the config file
	public function save() {
		$this->serialize_config($this->config);
	}

	public function getData() {
		return $this->config['data'];
	}
}

?>