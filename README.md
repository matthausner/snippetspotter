# Installation

in your local working directory run 

```git clone https://github.com/matthausner/snippetspotter```

```cd imports/extensions```

```git clone https://github.com/jwilsson/spotify-web-api-php```
 

# Usage 

in your application, import the necessary classes: 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/imports/extensions/snippetspotter/SnippetSpotter.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/imports/extensions/snippetspotter/SpotifyAccessTokenFactory.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/imports/extensions/snippetspotter/TimeIntervalConverter.php';

- on https://developer.spotify.com, register your application and obtain a client id and a client secret

- generate a user-specific Spotify access token based on the id and secret:

- create a new instance of SpotifyAccessTokenFactory and assign it to a variable:
```$factory = new SpotifyAccessTokenFactory();```
- call ```$factory->create($clientID, $clientSecret, $redirectURL); ``` 
- the $redirectUrl should be the URL of the page where the create method is called
- after a short amount of time (e.g. 1 second) redirect the user to the previous page: ```header("refresh:1;url=" . $previous);```
- store the result e.g. in an additional column in the 'user' table of your database 

- create a new instance of SnippetSpotter and assign it to a variable:
```$spotter = new SnippetSpotter();```
- fetch the previously generated access token, the name of album on Spotify and the album time-specific timestamp of the snippet (hours, minutes, seconds as an array of integers);
- call ```$spotter->getSpotifyLinkAndDescription($accessToken, $albumName, $albumHoursMinutesAndSeconds);```
_ store the result in a variable


- the result is an array of strings containing a track time-specific Spotify Unique Resource Identifier (URI) and a human-readable description
- the URI can be used to link to the track on Spotify, the description as a link text or an alternative text for an image link

