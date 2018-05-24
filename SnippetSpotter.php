<?php
class SnippetSpotter {
	
  /**
+     * Returns a Spotify track object and a track-specific timestamp in milliseconds based on on album name and an album-specific timestamp

+     	@param  string          $albumName  		searchString containing the (partial) name of the album
* 		@param  int             $albumTimestamp 	album Timestamp in Milliseconds to be spotted
+     * @return [string]          					Spotify API track object and timestamp in milliseconds of the track 
+     */
	public function spotSnippet($albumName, $albumTimestamp, $api) {
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


	public function embedSpotifyPlayer($hoerspiel, $verwendung, $accessToken) {
			   $api = new SpotifyWebAPI\SpotifyWebAPI();
			   $api->setAccessToken($accessToken);
               $name = $hoerspiel->getName();
               $beginn = explode(':', $verwendung->getBeginn());
               $albumMinutes = $beginn[0];
               $albumSeconds = $beginn[1];

               $timeIntervalConverter = new TimeIntervalConverter();
               $albumTimestamp = $timeIntervalConverter->convertToMilliseconds($albumMinutes, $albumSeconds);
               $snippetInformation = $this->spotSnippet($name, $albumTimestamp, $api);

               $track = $snippetInformation[0];
               $trackTimestamp = $snippetInformation[1];
               $trackTimes = $timeIntervalConverter->convertToMinutesAndSeconds($trackTimestamp);
               $trackMinutes = $trackTimes[0];
               $trackSeconds = $trackTimes[1];
               echo($track->name);
               echo(", ");
               $trackMinutes_padded = sprintf("%02d", $trackMinutes);
               echo $num_padded; 
               echo($trackMinutes_padded);
               echo(":");
               $trackSeconds_padded = sprintf("%02d", $trackSeconds);
               echo($trackSeconds_padded);
               echo("<br>");
               echo('<iframe src="https://open.spotify.com/embed/track/' . $track->id . '" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>');
        }
    } 
?>