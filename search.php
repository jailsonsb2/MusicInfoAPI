<?php

header('Content-Type: application/json');


$ApiType = "deezer";

$GetDataInput = filter_input(INPUT_GET, 'query');


$FilterReplace = str_replace(array('.mp3', '.aac', '.wav'), '', $GetDataInput);



function getDeezer($DataInput){
    $url = 'https://api.deezer.com/search?q=' . urlencode($DataInput);
    $FGC = file_get_contents($url);
    $JSD = json_decode($FGC);
    $Artwork = $JSD
        ->data[0]->album->cover_xl;
    $Title = $JSD
        ->data[0]->title;
    $Artist = $JSD
        ->data[0]->artist->name;
    $Album = $JSD
        ->data[0]->album->title;
    $Duration =$JSD
        ->data[0]->duration;

    $DeezerArray ['results'] = ["artist"=>$Artist, "title"=>$Title, "album"=>$Album, "artwork"=>$Artwork, "time"=>gmdate("i:s", $Duration)];

    $EncodeArray = json_encode($DeezerArray);

    return $EncodeArray;
}



function getiTunes($DataInputItunes){
    $url = 'https://itunes.apple.com/search?term=' . urlencode($DataInputItunes) . '&media=music&limit=1';
    $FGC = file_get_contents($url);
    $JSD = json_decode($FGC);
    $Artwork100 = $JSD
        ->results[0]->artworkUrl100;
    $Title = $JSD
        ->results[0]->trackCensoredName;
    $Artist = $JSD
        ->results[0]->artistName;
    $Album = $JSD
        ->results[0]->collectionCensoredName;
    $Duration =$JSD
        ->results[0]->trackTimeMillis;

    $ChangeArtworkSize = str_replace('100x100bb.jpg','1000x1000bb.jpg', $Artwork100);

    $ItunesArray ['results'] = ["artist"=>$Artist, "title"=>$Title, "album"=>$Album, "artwork"=>$ChangeArtworkSize, "time"=>gmdate("i:s", $Duration)];

    $EncodeArray = json_encode($ItunesArray);

    return $EncodeArray;
}


if ($ApiType == "deezer") {
    echo getDeezer($FilterReplace);
} elseif ($ApiType == "itunes") {
    echo getiTunes($FilterReplace);
}elseif (empty($GetDataInput)){
    echo("Nothing Here");
}
else {
    echo("Error, Select API Type.");
}


