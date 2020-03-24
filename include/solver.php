<?php


/**
 * TimeStampRange
 */
class TimeStampRange
{
	public $from;
	public $to;

	function __construct($from, $to) {
		$this->from = $from;
		$this->to = $to;
	}
}
/**
 * The object in charge of finding a common availability time
 */
class Solver {

	// [0] => [["from"] date, ["to"] => date]
	static public function solve($fewer_entries_user, $others) {
		$commonRange = $fewer_entries_user;
		foreach ($others as $currentUserRanges) {
			$commons = Solver::common($commonRange, $currentUserRanges);
			// var_dump($commons);

			if (empty($commons)) {
				$commonRange = [];
				continue;
			} else {
				$commonRange = $commons;
			}
		}

		if (!empty($commonRange)) {
			echo "<h3>Common slot time</h3>";
			echo "<table>";
			foreach ($commonRange as $dataRange) {
				$from = $dataRange['from']->format('d-m-Y H:i');
				$to = $dataRange['to']->format('d-m-Y H:i');
				echo "<tr><td>$from</td><td>$to</td></tr>";
			}
			echo "</table>";
		}
	}

	private static function areOverlapping($dateRange1, $dateRange2): bool {
		$timestamp_datarange1_from = $dateRange1['from']->format('Y-m-d H:i');
		$timestamp_datarange1_to = $dateRange1['to']->format('Y-m-d H:i');

		$timestamp_datarange2_from = $dateRange2['from']->format('Y-m-d H:i');
		$timestamp_datarange2_to = $dateRange2['to']->format('Y-m-d H:i');

		// var_dump($timestamp_datarange1_from, $timestamp_datarange1_to);
		// echo "<br/>";
		// var_dump($timestamp_datarange2_from, $timestamp_datarange2_to);

		if (strtotime($timestamp_datarange1_from) >= strtotime($timestamp_datarange2_to)) {
			return false;
		} elseif (strtotime($timestamp_datarange1_to) <= strtotime($timestamp_datarange2_from)) {
			return false;
		}
		return true;
	}

	private static function common($ranges, $userRanges) {
		$commons = [];
		foreach ($ranges as $range) {
			foreach ($userRanges as $userRange) {
				if (Solver::areOverlapping($range, $userRange)) {

					$max = max(strtotime($range['from']->format('Y-m-d H:i')), strtotime($userRange['from']->format('Y-m-d H:i')));
					$min = min(strtotime($range['to']->format('Y-m-d H:i')), strtotime($userRange['to']->format('Y-m-d H:i')));

					$fromDate = new DateTime();
					$fromDate->setTimestamp($max);

					$toDate = new DateTime();
					$toDate->setTimestamp($min);

					$array = ['from' => $fromDate, 'to' => $toDate];
					array_push($commons, $array);
				}
			}
		}
		return $commons;
	}

}

?>