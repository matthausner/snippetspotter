<?php
class SnippetSpotter {
	
    /*
    Returns a Spotify uri linking to a position on a specific track and a human readable description, both based on the album name and the position in hours, minutes and seconds

    @param  string          $albumName          searchString containing the name of the album
    @param  string          $albumName  		searchString containing the name of the album
 	@param  int             $albumTimestamp 	album specific position in hours, minutes and seconds as integer array
    @return [string]          					array consisting of the Spotify URI linking to the specified position and a human-readable description
    */
	public function getSpotifyLinkAndDescription($accessToken, $albumName, $albumHoursMinutesAndSeconds) {
		$api = new SpotifyWebAPI\SpotifyWebAPI();
		$api->setAccessToken($accessToken);
        $beginn = explode(':', $albumHoursMinutesAndSeconds);
        $albumHours = $beginn[0];
        $albumMinutes = $beginn[1];
        $albumSeconds = $beginn[2];

        $timeIntervalConverter = new TimeIntervalConverter();
        $albumTimestamp = $timeIntervalConverter->convertToMilliseconds($albumHours, $albumMinutes, $albumSeconds);
        $snippetInformation = $this->getSpotifyTrackAndTimestamp($api, $albumName, $albumTimestamp);

        $track = $snippetInformation[0];
        $trackTimestamp = $snippetInformation[1];
        $trackTimes = $timeIntervalConverter->convertToHoursMinutesAndSeconds($trackTimestamp);
        $trackHours = $trackTimes[0];
        $trackMinutes = $trackTimes[1];
        $trackSeconds = $trackTimes[2];
        $trackHours_padded = sprintf("%02d", $trackHours);
        $trackMinutes_padded = sprintf("%02d", $trackMinutes);
        $trackSeconds_padded = sprintf("%02d", $trackSeconds);
        if ($this->isMobileDevice()) {
            $spotifyURI = "https://open.spotify.com/track/" . $track->id . "#" . $trackMinutes . ":" . $trackSeconds;
        }
        else {
            $spotifyURI = "spotify:track:" . $track->id . "#" . $trackMinutes . ":" . $trackSeconds;
        }
        $humanReadableDescription = $track->name . ", " . $trackMinutes_padded . ":" . $trackSeconds_padded;

        return [$spotifyURI, $humanReadableDescription];
    }

    public function getAlbumSpecificTime($accessToken, $albumName, $trackNumber, $trackHoursMinutesAndSeconds) {
		$api = new SpotifyWebAPI\SpotifyWebAPI();
		$api->setAccessToken($accessToken);
        $beginn = explode(':', $trackHoursMinutesAndSeconds);
        $albumHours = $beginn[0];
        $albumMinutes = $beginn[1];
        $albumSeconds = $beginn[2];

        $timeIntervalConverter = new TimeIntervalConverter();
        $trackTimestamp = $timeIntervalConverter->convertToMilliseconds($albumHours, $albumMinutes, $albumSeconds);
        $albumTimestamp = $this->getAlbumTimestamp($api, $albumName, $trackNumber, $trackTimestamp);

        $albumTime = $timeIntervalConverter->convertToHoursMinutesAndSeconds($albumTimestamp);
        $albumHours = $albumTime[0];
        $albumMinutes = $albumTime[1];
        $albumSeconds = $albumTime[2];
        $albumHours_padded = sprintf("%02d", $albumHours);
        $albumMinutes_padded = sprintf("%02d", $albumMinutes);
        $albumSeconds_padded = sprintf("%02d", $albumSeconds);
        $albumSpecificTime = $albumHours_padded . ":" . $albumMinutes_padded . ":" . $albumSeconds_padded;

        return $albumSpecificTime;
    }

    /*
    Returns a Spotify track object and a track specific timestamp in milliseconds based on on album name and an album-specific timestamp

    @param  spotifyWebAPI   $api                Spotify Web API object containing the user-specific access token 
    @param  string          $albumName          searchString containing the name of the album
    @param  int             $albumTimestamp     album specific timestamp in milliseconds as integer
    @return [string]                            Spotify Web API track object and track specific timestamp in milliseconds
    */ 
    private function getSpotifyTrackAndTimestamp($api, $albumName, $albumTimestamp) {
        $results = $api->search($albumName, 'album');
        $items = $results->albums->items;
        $album = reset($items);
        $albumObject = (Object)$album;
        $id = "";
        if(isset($albumObject->id)) {
            $id = $albumObject->id;
        }
        $tracks = $api->getAlbumTracks($id, ['limit' => 50]);
        $trackTimestamp = 0;
        $accumulatedTrackDurations = 0;
        foreach ($tracks->items as $track) {
            $accumulatedTrackDurations += $track->duration_ms;
            $spottedTrack = $track;
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

    private static function getAlbumTimestamp($api, $albumName, $trackNumber, $trackTime) {
        $results = $api->search($albumName, 'album');
        $items = $results->albums->items;
        $album = reset($items);
        $albumObject = (Object)$album;
        $id = "";
        if(isset($albumObject->id)) {
            $id = $albumObject->id;
        }
        $tracks = $api->getAlbumTracks($id, ['limit' => 50]);
        $accumulatedTrackDurations = 0;
        for ($x=0; $x<$trackNumber-1; $x++) {
            $accumulatedTrackDurations += $tracks->items[$x]->duration_ms;
        }
        $albumTimestamp = $accumulatedTrackDurations + $trackTime;
        return $albumTimestamp;
    }

    private static function isMobileDevice() {
        $aMobileUA = array(
            '/iphone/i' => 'iPhone', 
            '/ipod/i' => 'iPod', 
            '/ipad/i' => 'iPad', 
            '/android/i' => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i' => 'Mobile'
        );
    
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
                return true;
            }
        }
        return false;
    }
}
?>