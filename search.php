<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// deezer, itunes, spotify or azuracast.
$ApiType = "deezer";

//Input data query.
$GetDataInput = filter_input(INPUT_GET, 'query');

//Remove Any Filename if it ends with any audio mime type
$FilterReplace = str_replace(array(
    '.mp3',
    '.aac',
    '.wav'
) , '', $GetDataInput);

function getAzuracast()
{
    // put your azuracast now playing api url here. eg. https://domain.com/api/nowplaying/1
    $API_URL = '';
    $FCG = json_decode(file_get_contents($API_URL));
    $artist = $FCG
        ->now_playing
        ->song->artist;
    $track = $FCG
        ->now_playing
        ->song->title;
    $artwork = $FCG
        ->now_playing
        ->song->art;

    $array['results'] = array(
        'artist' => $artist,
        'title' => $track,
        'artwork' => $artwork
    );

    return json_encode($array);
}

// Spotify API Function
function getSpotify($SpotifyInputData)
{
    // put spotify Client_ID:CLIENT_TOKEN Where you see it :)
    $b64 = base64_encode('CLIENT_ID:CLIENT_SECRET');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $headers = array();
    $headers[] = "Authorization: Basic $b64";
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $JSP = json_decode($result);
    $Acesstoken = $JSP->access_token;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.spotify.com/v1/search?q=' . urlencode($SpotifyInputData) . '&type=track&limit=1');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';
    $headers[] = "Authorization: Bearer $Acesstoken";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $spotresult = curl_exec($curl);
    $JSD = json_decode($spotresult);
    $Artwork = $JSD
        ->tracks
        ->items[0]
        ->album
        ->images[0]->url;
    $Album = $JSD
        ->tracks
        ->items[0]
        ->album->name;
    $Artist = $JSD
        ->tracks
        ->items[0]
        ->artists[0]->name;
    $TrackName = $JSD
        ->tracks
        ->items[0]->name;
    $Duration = $JSD
        ->tracks
        ->items[0]->duration_ms;
    $Stream = $JSD
        ->tracks
        ->items[0]
        ->external_urls->spotify;

    $JsonArray['results'] = array(
        'artist' => $Artist,
        'title' => $TrackName,
        'artwork' => $Artwork,
        'album' => $Album,
        'duration_ms' => $Duration,
        'stream_url' => $Stream
    );
    curl_close($curl);

    return (json_encode($JsonArray));

}

// Deezer API function
function getDeezer($DataInputDeezer)
{
    $url = 'https://api.deezer.com/search?q=' . urlencode($DataInputDeezer);
    $FGC = file_get_contents($url);
    $JSD = json_decode($FGC);
    $Artwork = $JSD->data[0]
        ->album->cover_xl;
    $Title = $JSD->data[0]->title;
    $Artist = $JSD->data[0]
        ->artist->name;
    $Album = $JSD->data[0]
        ->album->title;
    $Duration = $JSD->data[0]->duration;
    $Total = $JSD->total;

    $DeezerArray['results'] = array(
        "artist" => $Artist,
        "title" => $Title,
        "album" => $Album,
        "artwork" => $Artwork,
        "time" => gmdate("i:s", $Duration)
    );

    $EncodeArray = json_encode($DeezerArray);

    if ($Total === "0")
    {
        return "Deezer Data Not Found";

    }
    else return $EncodeArray;
}

// Itunes API Function.
function getiTunes($DataInputItunes)
{
    $url = 'https://itunes.apple.com/search?term=' . urlencode($DataInputItunes) . '&media=music&limit=1';
    $FGC = file_get_contents($url);
    $JSD = json_decode($FGC);
    $Artwork100 = $JSD->results[0]->artworkUrl100;
    $Title = $JSD->results[0]->trackCensoredName;
    $Artist = $JSD->results[0]->artistName;
    $Album = $JSD->results[0]->collectionCensoredName;
    $Duration = $JSD->results[0]->trackTimeMillis;
    $Total = $JSD->resultCount;

    $ChangeArtworkSize = str_replace('100x100bb.jpg', '700x700bb.jpg', $Artwork100);

    $ItunesArray['results'] = array(
        "artist" => $Artist,
        "title" => $Title,
        "album" => $Album,
        "artwork" => $ChangeArtworkSize,
        "time" => gmdate("i:s", $Duration)
    );

    $EncodeArray = json_encode($ItunesArray);

    if ($Total == "0")
    {
        return "Itunes Data Not Found";

    }
    else return $EncodeArray;

}


if (empty($GetDataInput)) {
    echo json_encode(array('Error' => 'Empty Query Parameters'));
}

// Selection Handling.
elseif ($ApiType === "deezer")
{
    echo getDeezer($FilterReplace);
}

elseif ($ApiType === "itunes")
{
    echo getiTunes($FilterReplace); // code...
    
}

elseif ($ApiType === "spotify")
{
    echo getSpotify($FilterReplace);
}

elseif ($ApiType === "azuracast")
{
    echo getAzuracast();
}

