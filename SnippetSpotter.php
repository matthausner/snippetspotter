<?php
class SnippetSpotter {
	
  /**
+     * Returns a Spotify track object and a track-specific timestamp in milliseconds based on on album name and an album-specific timestamp

+     	@param  string          $albumName  		searchString containing the (partial) name of the album
* 		@param  int             $albumTimestamp 	album Timestamp in Milliseconds to be spotted
+     * @return [string]          					Spotify API track object and timestamp in milliseconds of the track 
+     */
	public function spotSnippet($api, $albumName, $albumTimestamp) {
		$results = $api->search($albumName, 'album');
		$album = $results->albums->items[0];
		$tracks = $api->getAlbumTracks($album->id, ['limit' => 50]);
		$spottedTrack = "Unknown Track";
		$trackTimestamp = 0;

		foreach ($tracks->items as $track) {
			$accumulatedTrackDurations += $track->duration_ms;
	    	if ($accumulatedTrackDurations < $albumTimestamp) {
	    		continue; 
	    	} else { 
	    		$spottedTrack = $track;
	    		$trackTimestamp =  $albumTimestamp + $track->duration_ms - $accumulatedTrackDurations;
	    		break; 
	    		} 
			}
		return [$spottedTrack, $trackTimestamp];
	}


	public function embedSpotifyPlayer($accessToken, $albumName, $albumMinutesAndSeconds, $width = 300, $height = 80) {
		$api = new SpotifyWebAPI\SpotifyWebAPI();
		$api->setAccessToken($accessToken);
        $beginn = explode(':', $albumMinutesAndSeconds);
        $albumMinutes = $beginn[0];
        $albumSeconds = $beginn[1];

        $timeIntervalConverter = new TimeIntervalConverter();
        $albumTimestamp = $timeIntervalConverter->convertToMilliseconds($albumMinutes, $albumSeconds);
        $snippetInformation = $this->spotSnippet($api, $albumName, $albumTimestamp);

        $track = $snippetInformation[0];
        $trackTimestamp = $snippetInformation[1];
        $trackTimes = $timeIntervalConverter->convertToMinutesAndSeconds($trackTimestamp);
        $trackMinutes = $trackTimes[0];
        $trackSeconds = $trackTimes[1];
        $trackMinutes_padded = sprintf("%02d", $trackMinutes);
        $trackSeconds_padded = sprintf("%02d", $trackSeconds);

        echo($track->name . ", " . $trackMinutes_padded . ":" . $trackSeconds_padded . "<br>");
               
        echo('<iframe src="https://open.spotify.com/embed/track/' . $track->id . '" width="' . $width . '" height=' . $height . ' frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>');
        }
    } 
?>