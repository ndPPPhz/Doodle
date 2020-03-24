<?php
	/**
	 * 
	 */
	class DateRange implements JsonSerializable {
		public function __construct($date, $from, $to) {
			$this->date = $date;
			$this->from = $from;
			$this->to = $to;
		}

		public function constructWithJSON($json) {
			$new = new DateRange("0","0","0");
			foreach ($json AS $key => $value) $new->{$key} = $value;
			return $new;
		}

		public function jsonSerialize() {
			return get_object_vars($this);
		}

		public function asDateTime() {
			$fromDateTime = new DateTime($this->date." ".$this->from);
			$toDateTime = new DateTime($this->date." ".$this->to);
			return ['from' => $fromDateTime, 'to' => $toDateTime];
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

			return array_map($func, range(0, count($this->dates)-1));
		}

		function storeNewDates() {
			$dateRanges = $this->generateDateRanges();
			$this->configManager->add($dateRanges, $_SESSION['currentUser']);
			$this->configManager->save();
		}
	}
?>