<?php 

//include 'lofi.php';


function getMp3StreamTitle($streamingUrl, $interval, $offset = 0, $headers = true)
{
$needle = 'StreamTitle=';
$ua = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36';
$opts = [
'http' => [
'method' => 'GET',
'header' => 'Icy-MetaData: 1',
'user_agent' => $ua
]
];
if (($headers = get_headers($streamingUrl)))
foreach ($headers as $h)
if (strpos(strtolower($h), 'icy-metaint') !== false && ($interval = explode(':', $h)[1]))
break;
$context = stream_context_create($opts);
if ($stream = fopen($streamingUrl, 'r', false, $context))
{
while($buffer = stream_get_contents($stream, $interval, $offset)) {
if (strpos($buffer, $needle) !== false)
{
fclose($stream);
$title = explode($needle, $buffer)[1];
return substr($title, 1, strpos($title, ';') - 2);
}
$offset += $interval;
}
}
}

$out = (getMp3StreamTitle('http://65.108.202.157:1000/radio.mp3', 8192));


$clean = str_replace(' ', '-', $out);
$exo = explode('-', $out, 2);
 
//$result = curl_exec($ch);

// Deezer 
$query = "https://api.deezer.com/search?q=$clean"; 
$file = file_get_contents($query); 
$parsedFile = json_decode($file); 
$albumart = $parsedFile->data[0]->album->cover_xl; 
$artist =  $parsedFile->data[0]->artist->name;
$title =  $parsedFile->data[0]->title;
$duration =  $parsedFile->data[0]->duration;

$dir = dirname(__FILE__).'/cover/'.$artist.'_'.$title.'.jpg'; 
$dir = str_replace(' ', '-', $dir);
$dir = str_replace(',', '-', $dir);
$dir2 = dirname(__FILE__).'/cover/'.$artist.'_'.$title.'.png'; 
file_put_contents($dir, file_get_contents($albumart)); 
file_put_contents($dir2, file_get_contents($albumart)); 



$yeet2 = 'https://api.streamafrica.net/boxradio/web/nowplaying/cover/'.$artist.'_'.$title.'.jpg';
$yeet2 = str_replace(' ', '-', $yeet2);
$yeet2 = str_replace(',', '-', $yeet2);

$array['artist'] = $exo[0];
$array['song']= $exo[1];
$array['artwork']=$yeet2;
$array['artworkCDN']=$albumart;
$array['duration']=gmdate("i:s", $duration);

//$array['res.artist']=$artist;

$urlHost = $_SERVER['HTTP_HOST'];

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");




echo(json_encode($array));

//echo();
?>
