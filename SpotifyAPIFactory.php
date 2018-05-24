<?php
require 'vendor/autoload.php';

class SpotifyAPIFactory {

	public function create($clientID, $clientSecret, $redirectURL) {
		$session = new SpotifyWebAPI\Session(
    		$clientID,
    		$clientSecret,
    		$redirectURL
		);

		$api = new SpotifyWebAPI\SpotifyWebAPI();

		if (isset($_GET['code'])) {
    		$session->requestAccessToken($_GET['code']);
    		$api->setAccessToken($session->getAccessToken());

		} else {
    		$options = [
        		'scope' => [
            	'user-read-email',
        		],
    		];

    		header('Location: ' . $session->getAuthorizeUrl($options));
    		die();
    		'<br>';
		}	
			return $api;
	}
}
?>