<?php

header('Content-Type: application/json');
// Select use deezer if you want or itunes.
$ApiType = "itunes";

//Input data query.
$GetDataInput = filter_input(INPUT_GET, 'query');

//Replace trash lol
$FilterReplace = str_replace(array('.mp3', '.aac', '.wav'), '', $GetDataInput);

// Deezer API function
function getDeezer($DataInputDeezer){
    $url = 'https://api.deezer.com/search?q=' . urlencode($DataInputDeezer);
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
    $Total = $JSD
    ->total;
    
    $DeezerArray ['results'] = ["artist"=>$Artist, "title"=>$Title, "album"=>$Album, "artwork"=>$Artwork, "time"=>gmdate("i:s", $Duration)];
    
    $EncodeArray = json_encode($DeezerArray);
    
    if($Total == "0"){
    return "Deezer Data Not Found";
        
    }
    else return $EncodeArray;
}


// Itunes API Function.
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
    $Total = $JSD
    ->resultCount;
    
    $ChangeArtworkSize = str_replace('100x100bb.jpg','1000x1000bb.jpg', $Artwork100);
    
      $ItunesArray ['results'] = ["artist"=>$Artist, "title"=>$Title, "album"=>$Album, "artwork"=>$ChangeArtworkSize, "time"=>gmdate("i:s", $Duration)];
    
    $EncodeArray = json_encode($ItunesArray);
    
     if($Total == "0"){
    return "Itunes Data Not Found";
        
    }
    else return $EncodeArray;

}


// Selection Handling.

if ($ApiType == "deezer") {
   echo getDeezer($FilterReplace);
}

elseif ($ApiType == "itunes") {
echo getiTunes($FilterReplace);    // code...
}
