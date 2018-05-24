<?php
require 'vendor/autoload.php';

class SpotifyAccessTokenFactory {

	public function create($clientID, $clientSecret, $redirectURL) {
		$session = new SpotifyWebAPI\Session(
    		$clientID,
    		$clientSecret,
    		$redirectURL
		);

		if (isset($_GET['code'])) {
    		$session->requestAccessToken($_GET['code']);
    		return $session->getAccessToken();

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
	}
}
?>