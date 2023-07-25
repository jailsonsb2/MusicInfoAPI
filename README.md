A Music Info API I wrote in PHP.

Sorry for messy code i am a noob in PHP :(

i use Deezer API / Apple Music API / Spotify API

visit : https://developer.spotify.com/dashboard/ 
to create your client ID & Client Secret if you want to use spotify API.



```JSON
{
"results": {
"artist": "Migos",
"track": "Walk It Talk It",
"text": "Migos - Walk It Talk It",
"artwork": "https://icdn2.streamafrica.net/stacks/6c9ddfcf.jpg",
"album": "Culture II",
"genre": "Hip-Hop/Rap",
"duration": "04:36"
}
}
```

How to use it ? just download the php file into your server and do .... https://domain.com/search.php?query=Song-Title



<h1>How to parse the api data in PHP</h1>

```php
<?php
$EndPoint = "https://yourdomain.com/search.php?query=Song-Title";
$FGC = json_decode(file_get_contents($EndPoint));
// get artist name from the json.
$artist = $FGC->results->artist;


// This Prints Out The Artist Name
echo($artist);

```

<h1>Requirements</h1>
PHP 7.4 Or Above
