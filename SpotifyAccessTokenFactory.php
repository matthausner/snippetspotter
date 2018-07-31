<?php
require 'Core.php';

class SpotifyAccessTokenFactory {

	public function createWithAuthorizationCodeFlow($clientID, $clientSecret, $redirectURL) {
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
        		],
    		];

    		header('Location: ' . $session->getAuthorizeUrl($options));
    		die();
		}	
	}

    public function createWithClientCredentialsFlow($clientID, $clientSecret) {
        $session = new SpotifyWebAPI\Session(
            $clientID,
            $clientSecret
        );

        $session->requestCredentialsToken();
        return $session->getAccessToken();
    }
}
?>