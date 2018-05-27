<?php
class TimeIntervalConverter {

	const MILLISECONDS_PER_HOUR = 3600000;
	const MILLISECONDS_PER_MINUTE = 60000;
	const MILLISECONDS_PER_SECOND = 1000;

	public function convertToHoursMinutesAndSeconds($milliseconds) {
		$hours = floor($milliseconds/self::MILLISECONDS_PER_HOUR);
		$minutes = floor($milliseconds%self::MILLISECONDS_PER_HOUR/self::MILLISECONDS_PER_MINUTE);
		$seconds = floor(($milliseconds%self::MILLISECONDS_PER_MINUTE)/self::MILLISECONDS_PER_SECOND);
		return [$hours, $minutes, $seconds];
	}

	public function convertToMilliseconds($hours, $minutes, $seconds) {
		return $hours * self::MILLISECONDS_PER_HOUR + $minutes * self::MILLISECONDS_PER_MINUTE + $seconds * self::MILLISECONDS_PER_SECOND;
	}
}
?>