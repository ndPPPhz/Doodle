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

					$max = max(strtotime($range['from']->format(DATE_FORMAT)), strtotime($userRange['from']->format(DATE_FORMAT)));
					$min = min(strtotime($range['to']->format(DATE_FORMAT)), strtotime($userRange['to']->format(DATE_FORMAT)));

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
		$timestamp_datarange1_from = $dateRange1['from']->format(DATE_FORMAT);
		$timestamp_datarange1_to = $dateRange1['to']->format(DATE_FORMAT);

		$timestamp_datarange2_from = $dateRange2['from']->format(DATE_FORMAT);
		$timestamp_datarange2_to = $dateRange2['to']->format(DATE_FORMAT);

		if (strtotime($timestamp_datarange1_from) >= strtotime($timestamp_datarange2_to)) {
			return false;
		} elseif (strtotime($timestamp_datarange1_to) <= strtotime($timestamp_datarange2_from)) {
			return false;
		}
		return true;
	}
}
?>