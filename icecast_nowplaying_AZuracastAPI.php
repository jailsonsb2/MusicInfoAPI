<?php


$icecasturl = "http://65.108.202.157:1000";



$icecast_api = "$icecasturl/status-json.xsl";

$azura = "http://exclusive.streamafrica.net/api/nowplaying/48";

$radioname = "box_lofi";



#uncomment the function below if you can use explode
// function getMp3StreamTitle($streamingUrl, $interval, $offset = 0, $headers = true)
// {
//     $needle = 'StreamTitle=';
//     $ua = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36';
//     $opts = ['http' => ['method' => 'GET', 'header' => 'Icy-MetaData: 1', 'user_agent' => $ua]];
//     if (($headers = get_headers($streamingUrl))) foreach ($headers as $h) if (strpos(strtolower($h) , 'icy-metaint') !== false && ($interval = explode(':', $h) [1])) break;
//     $context = stream_context_create($opts);
//     if ($stream = fopen($streamingUrl, 'r', false, $context))
//     {
//         while ($buffer = stream_get_contents($stream, $interval, $offset))
//         {
//             if (strpos($buffer, $needle) !== false)
//             {
//                 fclose($stream);
//                 $title = explode($needle, $buffer) [1];
//                 return substr($title, 1, strpos($title, ';') - 2);
//             }
//             $offset += $interval;
//         }
//     }
// }

// $out = (getMp3StreamTitle('http://65.108.202.157:1000/radio.mp3', 8192));

$icecast_api_fileget = file_get_contents($icecast_api);
$icecast_api_json = json_decode($icecast_api_fileget);
$icecast_api_nowplaying_text = $icecast_api_json->icestats->source->yp_currently_playing;
$icecast_api_nowplaying_artist =$icecast_api_json->icestats->source->artist;
$icecast_api_nowplaying_song = $icecast_api_json->icestats->source->title;
//$icecast_api_duration = $icecast_api_json->icestats->source->stream_start;




// Deezer
$query = 'https://api.deezer.com/search?q='.urlencode($icecast_api_nowplaying_text);
//$file2 = file_get_contents($url);
$file3 = file_get_contents($azura);
$file3parsed = json_decode($file3);
$current_album = $file3parsed->now_playing->song->album;
$next_genre = $file3parsed->now_playing->song->genre;
$next_artist = $file3parsed->playing_next->song->artist;
$next_artwork = $file3parsed->playing_next->song->art;
$next_genre = $file3parsed->playing_next->song->genre;
$current_genre = $file3parsed->now_playing->song->genre;

$next_title = $file3parsed->playing_next->song->title;
$next_text = $file3parsed->playing_next->song->text;

$next_album = $file3parsed->playing_next->song->album;
$next_duration = $file3parsed->playing_next->duration;
//$songss = $file2parsed->currentSong;
//$songs2 = $file2parsed->currentArtist;
$file = file_get_contents($query);
$parsedFile = json_decode($file);
$albumart = $parsedFile->data[0]->album->cover_xl;
$artist = $parsedFile->data[0]->artist->name;
$title = $parsedFile->data[0]->title;
$duration = $parsedFile->data[0]->duration;

$nextsongdl = "XSVG";

//$pnghd = str_replace('jpg', 'png', $albumart);

$directory = "/www/wwwroot/api.streamafrica.net/boxradio/web/nowplaying";
$dir3 = $directory . '/cover/'  . $next_artist . '-' . $next_title . '-' . $nextsongdl . '.jpg';
$dir = $directory . '/cover/'  . $artist . '-' . $title . '-' . $radioname . '.jpg';
$dir = str_replace(' ', '-', $dir);
$dir = str_replace(',', '-', $dir);
$dir3 = str_replace(' ', '-', $dir3);
$dir3 = str_replace(',', '-', $dir3);


file_put_contents($dir, file_get_contents($albumart));
file_put_contents($dir3, file_get_contents($next_artwork));


//$yeet2 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $radioname . '.png';
$yeet2 = 'https://covers.streamafrica.net/' . $next_artist . '-' . $next_title . '-' . $nextsongdl . '.jpg';
$yeet1 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $radioname . '.jpg';
//$yeet3 = 'https://covers.streamafrica.net/' . $artist . '-' . $title . '-' . $radioname . '.png';
//$yeet3 = str_replace(',', '-', $yeet3);
//$yeet3 = str_replace(' ', '-', $yeet3);
$yeet1 = str_replace(' ', '-', $yeet1);
$yeet1 = str_replace(',', '-', $yeet1);
$yeet2 = str_replace(' ', '-', $yeet2);
$yeet2 = str_replace(',', '-', $yeet2);

$dr = gmdate("i:s", $duration);



$array ['now_playing'] = ['artist' => $icecast_api_nowplaying_artist, 'track' => $icecast_api_nowplaying_song, 'text' => $icecast_api_nowplaying_text, 'artwork' => $yeet1, 'artwork_cdn' => $albumart, 'album' => $current_album, 'genre' => $current_genre, 'duration' => $dr];
$array['next_song'] = ['artist' => $next_artist, 'track' => $next_title, 'text' => $next_text, 'artwork' => $yeet2, 'album' => $next_album, 'genre' => $next_genre, 'duration' => gmdate("i:s", $next_duration)];

$array['database'] = ['PostgreSQL' => Active, 'MySQL' => Active];



header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");




$host        = "host = 127.0.0.1";
$port        = "port = 5432";
$dbname      = "dbname = hellodb";
$credentials = "user = hellodb password=hellodb";

$db = pg_connect( "$host $port $dbname $credentials"  );
// if(!$db) {
//     echo "Error : Unable to open database\n";
// } else {
//     echo "Opened database successfully\n";
// }

// //   $sql =<<<EOF
// //       CREATE TABLE SONGS
// //       (ARTIST TEXT PRIMARY KEY     NOT NULL,
// //       SONG           TEXT    NOT NULL,
// //       ALBUM            TEXT     NOT NULL,
// //       ARTWORK        TEXT  NOT NULL,
// //       SONG_TEXT  TEXT  NOT NULL,
// //       ARTWORK_CDN  TEXT  NOT NULL,
// //       GENRE TEXT NOT NULL);
// // EOF;


$sql = <<<EOF
INSERT INTO songs (artist, song, album, genre, artwork,song_text,artwork_cdn) 
VALUES ('$icecast_api_nowplaying_artist','$icecast_api_nowplaying_song','$current_album','$current_genre', '$yeet1','$icecast_api_nowplaying_text','$albumart');

EOF;
$ret = pg_query($db, $sql);

// $ret2 = pg_query($sql2);


// $postgreserror = pg_last_error($db);

$hand = pg_last_error($db);

//   $sql2 =<<<EOF
//       SELECT * from songs;
// EOF;

function errorpost (){
 if(!$ret) {
    echo pg_last_error($db);
} else {
    echo "Records created successfully\n";
}


pg_close($db);
};




$con = mysqli_connect('localhost', 'coverinfo', 'coverinfo', 'coverinfo') or die('connection failed' .mysqli_connect_error());


$sql1 =  <<<EOF
INSERT INTO song (artist, track, text, artwork, album, genre, duration, artwork_cdn) 
VALUES ('$icecast_api_nowplaying_artist', '$icecast_api_nowplaying_song', '$icecast_api_nowplaying_text', '$yeet1', '$current_album', '$current_genre', '$dr', '$albumart');
EOF;

$qr1 = mysqli_query($con,$sql1);




print_r (json_encode($array));
//print_r($qr1);

