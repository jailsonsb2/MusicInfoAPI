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


$clean = str_replace(' ', '', $out);


// Deezer 
$query = "https://api.deezer.com/search?q=$clean"; 
$file = file_get_contents($query); 
$parsedFile = json_decode($file); 
$albumart = $parsedFile->data[0]->album->cover_xl; 
$artist =  $parsedFile->data[0]->artist->name;
$title =  $parsedFile->data[0]->title;

$dir = dirname(__FILE__).'/albumarts/'.$artist.'_'.$title.'_1000x1000.jpg'; 
$dir2 = dirname(__FILE__).'/albumarts/'.$artist.'_'.$title.'_1000x1000.png'; 
file_put_contents($dir, file_get_contents($albumart)); 
file_put_contents($dir2, file_get_contents($albumart)); 

$yeet2 = 'https://api.streamafrica.net/boxradio/web/nowplaying/albumarts/'.$artist.'_'.$title.'_1000x1000.png';




echo($yeet2);

//echo();
?>
