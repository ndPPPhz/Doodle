<?php
	/**
	 * 
	 */
	class DateRange implements JsonSerializable
	{
		
		public function __construct($date, $from, $to) {
			$this->date = $date;
			$this->from = $from;
			$this->to = $to;
		}

		public function jsonSerialize() {
			return get_object_vars($this);
		}
	}

	/**
	 * 
	 */
	class DateManager {
		private $dates;
		private $froms;
		private $tos;
		private $configManager;

		function __construct($dates, $froms, $tos, $configManager) {
			$this->dates = $dates;
			$this->froms = $froms;
			$this->tos = $tos;
			$this->configManager = $configManager;
		}

		private function generateDateRanges() {
			$func = function($count) {
				return new DateRange($this->dates[$count], $this->froms[$count], $this->tos[$count]);
			};

			return array_map($func, range(0, count($this->dates)));
		}

		function storeNewDates() {
			$dateRanges = $this->generateDateRanges();
			$this->configManager->add($dateRanges, $_SESSION['currentUser']);
			$this->configManager->save();
		}
	}
?>