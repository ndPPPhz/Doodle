<?php
/**
 * The object in charge of finding a common availability time
 */
class Solver {

	static public function solve(array $fewer_entries_user, array $others): array {
		// Initially set common range equal to all of the first user ranges  
		$commonRange = $fewer_entries_user;
		foreach ($others as $currentUserRanges) {
			// For all the user returns the common time ranges
			$commons = Solver::common($commonRange, $currentUserRanges);

			// If they don't have anything in common, then stop the loop
			// and set commonRange as empty
			if (empty($commons)) {
				$commonRange = [];
				continue;
			} else {
				// Otherwhise set commonRange equal to all the shared time ranges and
				// iterate the algorythm again for the next user
				$commonRange = $commons;
			}
		}
		return $commonRange;
	}

	private static function common(array $ranges, array $userRanges): array {
		$commons = [];
		foreach ($ranges as $range) {
			foreach ($userRanges as $userRange) {
				if (Solver::areOverlapping($range, $userRange)) {

					$max = max(strtotime($range['from']->format('d-m-Y H:i')), strtotime($userRange['from']->format('d-m-Y H:i')));
					$min = min(strtotime($range['to']->format('d-m-Y H:i')), strtotime($userRange['to']->format('d-m-Y H:i')));

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

	private static function areOverlapping(array $dateRange1, array $dateRange2): bool {
		$timestamp_datarange1_from = $dateRange1['from']->format('d-m-Y H:i');
		$timestamp_datarange1_to = $dateRange1['to']->format('d-m-Y H:i');

		$timestamp_datarange2_from = $dateRange2['from']->format('d-m-Y H:i');
		$timestamp_datarange2_to = $dateRange2['to']->format('d-m-Y H:i');

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
}
?>