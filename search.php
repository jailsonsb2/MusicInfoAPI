<?php



$qr = filter_input(INPUT_GET, 'query');

$radioname = rand(80,900000);

// Deezer
$query = 'https://api.deezer.com/search?q='.urlencode($qr);

$file3 = file_get_contents($azura);
$file3parsed = json_decode($file3);
$current_album = $file3parsed->now_playing->song->album;
$current_genre = $file3parsed->now_playing->song->genre;

//$itfile = file_get_contents($itunes);
//$itparsedFile = json_decode($itfile);
$file = file_get_contents($query);
$parsedFile = json_decode($file);
$albumart = $parsedFile->data[0]->album->cover_xl;
$albumy = $parsedFile->data[0]->album->title;
$artist = $parsedFile->data[0]->artist->name;
$title = $parsedFile->data[0]->title;
$duration = $parsedFile->data[0]->duration;
$error = $parsedFile->total;


//$itunesgenre = $itparsedFile->results[0]->primaryGenreName;

$nextsongdl = rand(800,900000);

$texty = "$artist - $title";

//$pnghd = str_replace('jpg', 'png', $albumart);

$directory = "/www/wwwroot/api.streamafrica.net/boxradio/web/nowplaying";
$dir3 = $directory . '/cover/'  . $next_artist . '-' . $next_title . '-' . $nextsongdl . '.jpg';
$dir = $directory . '/cover/'  . $artist . '-' . $title . '-' . $radioname . '.jpg';
$dir = str_replace(' ', '-', $dir);
$dir = str_replace('?', '-', $dir);
$dir = str_replace(',', '-', $dir);
$dir3 = str_replace(' ', '-', $dir3);
$dir3 = str_replace('?', '-', $dir3);
$dir3 = str_replace(',', '-', $dir3);


file_put_contents($dir, file_get_contents($albumart));
file_put_contents($dir3, file_get_contents($next_artwork));



$yeet2 = 'https://artworkcdn.b-cdn.net/' . $next_artist . '-' . $next_title . '-' . $nextsongdl . '.jpg';
$yeet1 = 'https://artworkcdn.b-cdn.net/' . $artist . '-' . $title . '-' . $radioname . '.jpg';

$yeet1 = str_replace(' ', '-', $yeet1);
$yeet1 = str_replace(',', '-', $yeet1);
$yeet1 = str_replace('?', '-', $yeet1);
$yeet2 = str_replace(' ', '-', $yeet2);
$yeet2 = str_replace(',', '-', $yeet2);

$dr = gmdate("i:s", $duration);



$array ['results'] = ['artist' => $artist, 'track' => $title, 'text' => $texty, 'artwork' => $yeet1, 'album' => $albumy, 'genre' => "N/A", 'duration' => $dr];



$json = json_encode(array('data' => $array));

$king = (file_put_contents("search.json", $json));


$error_array = ['data' => "404 Entity Not Found"];





if ($error == '0')
{
    echo (json_encode($error_array));
}
else {
  print_r (json_encode($array));

}




header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");

