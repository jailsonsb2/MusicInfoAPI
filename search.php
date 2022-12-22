<?php

$qr = filter_input(INPUT_GET, 'query');
// Deezer
$itunes = 'https://itunes.apple.com/search?term=' . urlencode($qr) . '&media=music&limit=1';
$query = 'https://api.deezer.com/search?q=' . urlencode($qr);
$file = file_get_contents($query);
$parsedFile = json_decode($file);
$itunesfg = file_get_contents($itunes);
$iTunesJsonDecode = json_decode($itunesfg);
$iTunesParseGenre = $iTunesJsonDecode->results[0]->primaryGenreName;
$iTunesDownloadArtwork = $iTunesJsonDecode->results[0]->artworkUrl100;
$iTunesStreamurl = $iTunesJsonDecode->results[0]->collectionViewUrl;
$itunesNull = $iTunesJsonDecode->resultCount;
$albumart = $parsedFile->data[0]
    ->album->cover_xl;
$albumy = $parsedFile->data[0]
    ->album->title;
$artist = $parsedFile->data[0]
    ->artist->name;
$title = $parsedFile->data[0]->title;
$DeezerStreamUrl = $parsedFile->data[0]->link;
$duration = $parsedFile->data[0]->duration;
$id = $parsedFile->data[0]
    ->album->md5_image;
$error = $parsedFile->total;

$texty = "$artist - $title";

$iTunesArtwork = str_replace('100x100bb.jpg', '1000x1000bb.jpg', $iTunesDownloadArtwork);

$directory = "/www/wwwroot/api.streamafrica.net/boxradio/web/nowplaying";
// $dir3 = $directory . '/cover/'  . $id . '.png';
$dir = $directory . '/cover/' . $id . '.jpg';

file_put_contents($dir, file_get_contents($albumart));
// file_put_contents($dir3, file_get_contents($pnghd));


// $yeet2 = 'https://1815083866.rsc.cdn77.org/' . $id . '.png';
$yeet1 = 'https://1815083866.rsc.cdn77.org/' . $id . '.jpg';

$dr = gmdate("i:s", $duration);

$array['results'] = ['artist' => $artist, 'track' => $title, 'text' => $texty, 'artwork' => $yeet1, 'album' => $albumy, 'genre' => $iTunesParseGenre, 'duration' => $dr];

$array['streaming'] = ['iTunes' => $iTunesStreamurl, 'Itunes_Artwork' => $iTunesArtwork, 'Deezer' => $DeezerStreamUrl, 'DeezerArtwork' => $albumart];

$json = json_encode(array(
    'data' => $array
));

$error_array = ['data' => "404 Entity Not Found"];
$empty_array = ['data' => "291 Invalid Query Params."];

if ($error == '0')
{
    echo (json_encode($error_array));
}
elseif (empty($qr))
{
    echo (json_encode($empty_array)); // code...
    
}
else
{
    print_r(json_encode($array));

}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");

