<?php
class TimeIntervalConverter {

	const MILLISECONDS_PER_HOUR = 3600000;
	const MILLISECONDS_PER_MINUTE = 60000;
	const MILLISECONDS_PER_SECOND = 1000;

	public function convertToHoursMinutesAndSeconds($milliseconds) {
		try {
			if ($milliseconds >= 0) {
				$hours = floor($milliseconds/self::MILLISECONDS_PER_HOUR);
				$minutes = floor($milliseconds%self::MILLISECONDS_PER_HOUR/self::MILLISECONDS_PER_MINUTE);
				$seconds = floor(($milliseconds%self::MILLISECONDS_PER_MINUTE)/self::MILLISECONDS_PER_SECOND);
			}

			else {
				$hours = ceil($milliseconds/self::MILLISECONDS_PER_HOUR);
				$minutes = ceil($milliseconds%self::MILLISECONDS_PER_HOUR/self::MILLISECONDS_PER_MINUTE);
				$seconds = ceil(($milliseconds%self::MILLISECONDS_PER_MINUTE)/self::MILLISECONDS_PER_SECOND);
			}
			return [$hours, $minutes, $seconds];
		}
		catch (Exception $e) {
			error_log($e);
			return [0, 0, 0];
		}
	}

	public function convertToMilliseconds($hours, $minutes, $seconds) {
		try {
			return $hours * self::MILLISECONDS_PER_HOUR + $minutes * self::MILLISECONDS_PER_MINUTE + $seconds * self::MILLISECONDS_PER_SECOND;
		}
		catch (Exception $e) {
			error_log($e);
			return 0;
		}
	}

	public function convertFormattedTimeToMilliseconds($formattedTime) {
		$beginn = explode(':', $formattedTime);
        $hours = $beginn[0];
        $minutes = $beginn[1];
        $seconds = $beginn[2];

        $timeIntervalConverter = new TimeIntervalConverter();
        $trackTimestamp = $this->convertToMilliseconds($hours, $minutes, $seconds);
	}
}
?>