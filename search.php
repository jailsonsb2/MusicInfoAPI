<?php

$qr = filter_input(INPUT_GET, "query");

$FilterReplace = str_replace([".mp3", ".aac", ".wav"], "", $qr);

// Deezer
$itunes =
    "https://itunes.apple.com/search?term=" .
    urlencode($FilterReplace) .
    "&media=music&limit=1";
$query = "https://api.deezer.com/search?q=" . urlencode($FilterReplace);
$file = file_get_contents($query);
$parsedFile = json_decode($file);
$itunesfg = file_get_contents($itunes);
$iTunesJsonDecode = json_decode($itunesfg);
$iTunesParseGenre = $iTunesJsonDecode->results[0]->primaryGenreName;
$iTunesParseArtist = $iTunesJsonDecode->results[0]->artistName;
$iTunesParseTrack = $iTunesJsonDecode->results[0]->trackName;
$iTunesParseAlbum = $iTunesJsonDecode->results[0]->collectionName;
$iTunesParseDuration = $iTunesJsonDecode->results[0]->trackTimeMillis;
$iTunesDownloadArtwork = $iTunesJsonDecode->results[0]->artworkUrl100;
$iTunesTrackId = $iTunesJsonDecode->results[0]->collectionId;
$itunesNull = $iTunesJsonDecode->resultCount;
$albumart = $parsedFile->data[0]->album->cover_xl;
$albumy = $parsedFile->data[0]->album->title;
$artist = $parsedFile->data[0]->artist->name;
$title = $parsedFile->data[0]->title;
$DeezerStreamUrl = $parsedFile->data[0]->link;
$duration = $parsedFile->data[0]->duration;
$id = $parsedFile->data[0]->album->md5_image;
$error = $parsedFile->total;

$iTunesArt = str_replace(
    "100x100bb.jpg",
    "1000x1000bb.jpg",
    $iTunesDownloadArtwork
);

$directory = "/www/wwwroot/covers.streamafrica.net/";

$dir = $directory . "/web/" . $id . ".jpg";
$dir2 = $directory . "/web/" . $iTunesTrackId . ".jpg";

$filename2 = $id . ".jpg";
$filenameitunes = $iTunesTrackId . ".jpg";
file_put_contents($dir, file_get_contents($albumart));
file_put_contents($dir2, file_get_contents($iTunesArt));

$UploadArtwork1 = "https://covers.streamafrica.net/$filename2";
$UploadArtwork2 = "https://covers.streamafrica.net/$filenameitunes";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://apps.streamafrica.net/covers/api.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "uploaded_file" => new CURLFile($dir),
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "uploaded_file" => new CURLFile($dir2),
]);

$response = curl_exec($ch);

curl_close($ch);

$dr = gmdate("i:s", $duration);

$array["results"] = [
    "artist" => $artist,
    "track" => $title,
    "artwork" => $UploadArtwork1,
    "album" => $albumy,
    "genre" => $iTunesParseGenre,
    "duration" => $dr,
];

$array2["results"] = [
    "artist" => $artist,
    "track" => $title,
    "artwork" => $UploadArtwork1,
    "album" => $albumy,
    "genre" => "unavailable",
    "duration" => $dr,
];

$array["results2"] = [
    "artist" => $iTunesParseArtist,
    "track" => $iTunesParseTrack,
    "artwork" => $UploadArtwork2,
    "album" => $iTunesParseAlbum,
    "genre" => $iTunesParseGenre,
    "duration" => gmdate("i:s", $iTunesParseDuration),
];

$error_array = ["data" => "404 Entity Not Found"];
$empty_array = ["data" => "403 Invalid Query Paramameters"];

if ($error == "0") {
    echo json_encode($array3);
} elseif ($itunesNull == "0") {
    echo json_encode($array2);
} elseif (empty($qr)) {
    echo json_encode($empty_array);
} else {
    print_r(json_encode($array));
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: X-Requested-With");
