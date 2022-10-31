<?php

//include 'lofi.php';



$azura = "http://exclusive.streamafrica.net/api/nowplaying/48?$rand";

$rand = rand(0, 999);


$radioname = "box_lofi"; // dont leave any spaces in your radio name.

function getMp3StreamTitle($streamingUrl, $interval, $offset = 0, $headers = true)
{
    $needle = 'StreamTitle=';
    $ua = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36';
    $opts = ['http' => ['method' => 'GET', 'header' => 'Icy-MetaData: 1', 'user_agent' => $ua]];
    if (($headers = get_headers($streamingUrl))) foreach ($headers as $h) if (strpos(strtolower($h) , 'icy-metaint') !== false && ($interval = explode(':', $h) [1])) break;
    $context = stream_context_create($opts);
    if ($stream = fopen($streamingUrl, 'r', false, $context))
    {
        while ($buffer = stream_get_contents($stream, $interval, $offset))
        {
            if (strpos($buffer, $needle) !== false)
            {
                fclose($stream);
                $title = explode($needle, $buffer) [1];
                return substr($title, 1, strpos($title, ';') - 2);
            }
            $offset += $interval;
        }
    }
}

$out = (getMp3StreamTitle('http://65.108.202.157:1000/radio.mp3', 8192));

$url = "http://api.streamafrica.net/boxradio/web/nowplaying/lofi.php";

$clean = str_replace(" ", "-", $out);
// Deezer
$query = "https://api.deezer.com/search?q=$clean";
$file2 = file_get_contents($url);
$file3 = file_get_contents($azura);
$file3parsed = json_decode($file3);
$next_album = $file3parsed->now_playing->song->album;
$next_genre = $file3parsed->now_playing->song->genre;
$next_artwork = $file3parsed->playing_next->song->art;
$next_title = $file3parsed->playing_next->song->title;
$next_duration = $file3parsed->playing_next->duration;
$file2parsed = json_decode($file2);
$songss = $file2parsed->currentSong;
$songs2 = $file2parsed->currentArtist;
$file = file_get_contents($query);
$parsedFile = json_decode($file);
$albumart = $parsedFile->data[0]->album->cover_xl;
$artist = $parsedFile->data[0]->artist->name;
$title = $parsedFile->data[0]->title;
$duration = $parsedFile->data[0]->duration;

$nextsongdl = "box_lofi_next";

$dir = dirname(__FILE__) . '/cover/' . $artist . '-' . $title . '-' . $radioname . '.jpg';
$dir3 = dirname(__FILE__) . '/cover/' . $artist . '-' . $title . '-' . $nextsongdl . '.jpg';
$dir = str_replace(' ', '-', $dir);
$dir = str_replace(',', '-', $dir);
$dir2 = str_replace(' ', '-', $dir2);
$dir2 = str_replace(',', '-', $dir2);
$dir2 = dirname(__FILE__) . '/cover/' . $artist . '-' . $title . '-' . $radioname . '.png';
file_put_contents($dir, file_get_contents($albumart));
file_put_contents($dir2, file_get_contents($albumart));
file_put_contents($dir3, file_get_contents($next_artwork));


$yeet2 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $radioname . '.jpg';
//$yeet2 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $nextsongdl . '.jpg';
$yeet1 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $nextsongdl . '.jpg';
$yeet2 = str_replace(' ', '-', $yeet2);
$yeet2 = str_replace(',', '-', $yeet2);
$yeet3 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $radioname . '.png';
$yeet3 = str_replace(',', '-', $yeet3);
$yeet3 = str_replace(' ', '-', $yeet3);


$array['nowplaying'] = ['artist' => $songs2, 'track' => $songss, 'artwork' => $yeet2, 'album' => $next_album, 'genre' => $next_genre, 'duration' => gmdate("i:s", $duration)];
//$array['nextSong'] = ['artist' => $next_artist, 'track' => $next_song, 'artwork' => $yeet1, 'duration' => gmdate("i:s", $next_duration)];
//$array['artist'] = $songs2;
//$array['song'] = $songss;
//$array['artwork_jpg'] = $yeet2;
//$array['playing_next'] = $next;
//$array['duration'] = gmdate("i:s", $duration);

//$array['res.artist']=$artist;
$urlHost = $_SERVER['HTTP_HOST'];

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

echo (json_encode($array));

?>
