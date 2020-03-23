<?php
require_once 'config.php';
require_once 'login.php';
require_once 'dateManager.php';
require_once 'solver.php';
/**
 * 
 */
class DoodleFacade {
	private $configManager;

	function __construct($config_file) {
		$this->configManager = new ConfigManager($config_file);
	}

	function log($username, $password): bool {
		$logger = new Logger($this->configManager->config);
		return $logger->log($username, $password);
	}

	function addEntries($dates, $froms, $tos) {
		$dateManager = new DateManager($dates, $froms, $tos, $this->configManager);
		$dateManager->storeNewDates();
	}

	function getData() {
		return $this->configManager->getData();
	}

	function solve() {
		// Map the data array in a new array of elements where each element is the
		// result of the count of the n-th subarray
		$data = $this->configManager->getData();

		$transform = function($userData) {
			$dataRanges = array_map("DateRange::constructWithJSON", $userData);
			return array_map(function($dataRange) { return $dataRange->asDateTime(); }, $dataRanges);
		};

		$user_and_dataRanges = array_map($transform, $data);

		$counts = array_map("count", $user_and_dataRanges);
		// Get the min
		$min = min($counts);
		// Flip the array to get the key
		$key = array_flip($counts)[$min];
		// Get the name of the user that has inserted fewer entries
		$fewer_entries_user = $user_and_dataRanges[$key];
		$user_and_dataRanges_copy = $user_and_dataRanges;

		// Remove the user with less entries
		unset($user_and_dataRanges_copy[$key]);
		Solver::solve($fewer_entries_user, $user_and_dataRanges_copy);
	}
}
?>