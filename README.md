A Music Info API I wrote in PHP.

Sorry for messy code i am a noob in PHP :(

i use Deezer API or Apple Music. to retreive the Song Info.
 Spotify Version Coming Soon.

Just made it simple :)



```JSON
{
"results": {
"artist": "Migos",
"track": "Walk It Talk It",
"text": "Migos - Walk It Talk It",
"artwork": "https://cn49wdcszwji8n4.gcws.cloud/TWlnb3MgLSBXYWxrIEl0IFRhbGsgSXQ=",
"album": "Culture II",
"genre": "Hip-Hop/Rap",
"duration": "04:36"
}
}
```

How to use it ? just download the php file into your server and do .... https://domain.com/search.php?query=Song-Title


<h1>How to parse result in PHP<h1/>


```php
<?php
$EndPoint = "https://yourdomain.com/search.php?query=Song-Title";
$FGC = json_decode(file_get_contents($EndPoint));
// get artist name from the json.
$artist = $FGC->results->artist;


// This Prints Out The Artist Name
echo($artist);

```
