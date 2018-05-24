<?php
class SnippetSpotter {
	private $api;

   function __construct($api) {
   		$this->api = $api;
   }

  /**
+     * Returns a Spotify track object and a track-specific timestamp in milliseconds based on on album name and an album-specific timestamp

+     	@param  string          $albumName  		searchString containing the (partial) name of the album
* 		@param  int             $albumTimestamp 	album Timestamp in Milliseconds to be spotted
+     * @return [string]          					Spotify API track object and timestamp in milliseconds of the track 
+     */
public function spotSnippet($albumName, $albumTimestamp) {
	$api = $this->api;
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
}
?>