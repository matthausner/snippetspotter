<?php
class SnippetSpotter {
	
    /*
    Returns a Spotify uri linking to a position on a specific track and a human readable description, both based on the album name and the position in hours, minutes and seconds

    @param  string      $accessToken                 SpotifyAccessToken
    @param  string      $albumName                   searchString containing the name of the album
 	@param  int         $albumHoursMinutesAndSeconds album specific position in hours, minutes and seconds as integer array
    @return [string]          					     array (tuple) consisting of the Spotify URI linking to the specified position and a human-readable description
    */
	public function getSpotifyLinkAndDescription($accessToken, $albumName, $albumHoursMinutesAndSeconds) {
		$api = new SpotifyWebAPI\SpotifyWebAPI();
		$api->setAccessToken($accessToken);

        $timeIntervalConverter = new TimeIntervalConverter();
        $albumTimestamp = $timeIntervalConverter->convertFormattedTimeToMilliseconds($albumHoursMinutesAndSeconds);
        $snippetInformation = $this->getSpotifyTrackAndTimestamp($api, $albumName, $albumTimestamp);

        $track = $snippetInformation[0];
        $trackTimestamp = $snippetInformation[1];
        $trackTimes = $timeIntervalConverter->convertToHoursMinutesAndSeconds($trackTimestamp);
        $trackHours = $trackTimes[0];
        $trackMinutes = $trackTimes[1];
        if ($trackTimestamp != 0) {
            $trackSeconds = $trackTimes[2];
        } else {
            $trackSeconds = $trackTimes[2] + 1;
        }
        $trackMinutes_padded = sprintf("%02d", $trackMinutes);
        $trackSeconds_padded = sprintf("%02d", $trackSeconds);

        $spotifyURI = "https://open.spotify.com/track/" . $track->id . "#" . $trackMinutes . ":" . $trackSeconds;
        $humanReadableDescription = $track->name . ", " . $trackMinutes_padded . ":" . $trackSeconds_padded;

        return [$spotifyURI, $humanReadableDescription];
    }

    public function getAlbumSpecificTime($accessToken, $albumName, $trackNumber, $trackHoursMinutesAndSeconds) {
		$api = new SpotifyWebAPI\SpotifyWebAPI();
		$api->setAccessToken($accessToken);

        $timeIntervalConverter = new TimeIntervalConverter();
        $trackTimestamp = $timeIntervalConverter->convertFormattedTimeToMilliseconds($trackHoursMinutesAndSeconds);
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
        $tracks = self::getAllAlbumTracks($api, $id);
        $trackTimestamp = 0;
        $accumulatedTrackDurations = 0;
        foreach ($tracks as $track) {
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
        $tracks = self::getAllAlbumTracks($api, $id);
        $accumulatedTrackDurations = 0;
        for ($x=0; $x<$trackNumber; $x++) {
            $accumulatedTrackDurations += $tracks[$x]->duration_ms;
        }
        $albumTimestamp = $accumulatedTrackDurations + $trackTime;
        return $albumTimestamp;
    }

    private static function getAllAlbumTracks($api, $id) {
        $offset = 0;
        $tracks = [];
        $hasNext = true;

        do {
            $response = $api->getAlbumTracks($id, [
        'offset' => $offset,
        ]);

        $offset += 20;
        $tracks = array_merge($tracks, $response->items);
        $hasNext = $response->next;
        } while ($hasNext);

        return $tracks;
    }
}
?>