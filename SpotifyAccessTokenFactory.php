<?php

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
			$url = $session->getAuthorizeUrl($options);
			echo 
			'<script>
				window.location.replace("'. $url .'");
			</script>';
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