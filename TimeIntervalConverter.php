<?php
class TimeIntervalConverter {
	public function convertToMinutesAndSeconds($milliseconds) {
		$minutes = floor($milliseconds/60000);
		$seconds = floor(($milliseconds%60000)/1000);
		return [$minutes, $seconds];
	}

	public function convertToMilliseconds($minutes, $seconds) {
		return $minutes * 60000 + $seconds * 1000;
	}
}
?>