<?php
	/**
	 * Custom class which defines a date range
	 */
	class DateRange implements JsonSerializable {
		public $date;
		public $from;
		public $to;
		
		function __construct(string $date, string $from, string $to) {
			$this->date = $date;
			$this->from = $from;
			$this->to = $to;
		}

		function constructWithJSON($json) {
			$new_date_range = new DateRange("0","0","0");
			foreach ($json AS $key => $value) $new_date_range->{$key} = $value;
			return $new_date_range;
		}

		function jsonSerialize(): array {
			return get_object_vars($this);
		}

		// It transforms the DateRange into an array of 2 DateTime, one for the starting point (from)
		// and one for the end point (to)
		function asDateTime(): array {
			$fromDateTime = new DateTime($this->date." ".$this->from);
			$toDateTime = new DateTime($this->date." ".$this->to);
			return ['from' => $fromDateTime, 'to' => $toDateTime];
		}
	}
?>