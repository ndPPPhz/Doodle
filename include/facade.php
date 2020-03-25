<?php
require_once 'config.php';
require_once 'login.php';
require_once 'date_range.php';
require_once 'solver.php';
/**
 * The facade object
 */
class DoodleFacade {
	private $config_manager;

	function __construct(string $config_file) {
		$this->config_manager = new ConfigManager($config_file);
	}

	function log(string $username, string $password): bool {
		$logger = new Logger($this->config_manager->config);
		return $logger->log($username, $password);
	}

	function addEntries(array $dates, array $froms, array $tos) {
		// Mapping closure to transform dates into DateRanges
		$closure = function($count) use ($dates, $froms, $tos) {
			return new DateRange($dates[$count], $froms[$count], $tos[$count]);
		};

		$entries_count = count($dates);
		$date_ranges = array_map($closure, range(0, $entries_count - 1));
		
		$this->config_manager->add($date_ranges, $_SESSION[LOGGED_USER]);
		$this->config_manager->save();
	}

	function getUsersEntries(): array {
		return $this->config_manager->getUsersEntries();
	}

	function solve(): array {
		$data = $this->config_manager->getUsersEntries();

		// Generate DateRanges from the JSON
		// For each user's data 
		$closure = function($user_data) {
			// Map each data range containted within $user_data to a DateRange
			$date_ranges = array_map("DateRange::constructWithJSON", $user_data);
			return array_map(function($dataRange) { return $dataRange->asDateTime(); }, $date_ranges);
		};

		// Data contains all the user's entries grouped by username
		$user_and_data_ranges = array_map($closure, $data);

		$counts = array_map("count", $user_and_data_ranges);
		// Get the user with less entries
		$min = min($counts);
		// And flip the array to get his username
		$key = array_flip($counts)[$min];

		// Use the key to extract him from the array
		$fewer_entries_user = $user_and_data_ranges[$key];
		$user_and_date_ranges_copy = $user_and_data_ranges;

		// And remove him from the array containing all the users
		unset($user_and_date_ranges_copy[$key]);

		return Solver::solve($fewer_entries_user, $user_and_date_ranges_copy);
	}
}
?>