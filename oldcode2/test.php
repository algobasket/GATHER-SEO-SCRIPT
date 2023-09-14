<?php
set_time_limit(0);  
require 'vendor/autoload.php';
require 'scrapper.php';

use GuzzleHttp\Client;
use React\EventLoop\Factory;
use React\Promise\Promise; 
use React\Promise\PromiseInterface;
use React\Promise\all; // Add this line

$link = "https://www.dunhamlaw.com/";
$start_time = microtime(true); 

$html = getCurlPageInfo($link);
$end_time = microtime(true);
$load_time = $end_time - $start_time; 

$dom = new DOMDocument;
@$dom->loadHTML($html['data']);

$links = array();
$counter = 1; 
$arrayHref = $dom->getElementsByTagName('a');

foreach ($arrayHref as $a) {   
    $href = $a->getAttribute('href'); 
    if (!empty($href) && (strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0)) {  
        $urls[] = $href;
    } 
}

$loop = Factory::create();
$client = new Client();

$getAsync = function ($url) use ($client, $loop) {
    return $client->getAsync($url)->then(
        function ($response) use ($url) {
            return "URL: $url - Status Code: " . $response->getStatusCode();
        },
        function ($error) {
            return "Error: " . $error->getMessage();
        }
    );
};

$promises = array_map($getAsync, $urls);

all($promises)->then( // Use all from the React\Promise namespace
    function ($results) {
        foreach ($results as $result) {
            echo $result . PHP_EOL;
        }
    },
    function ($error) {  
        echo "Error: " . $error->getMessage() . PHP_EOL;
    }
);

$loop->run();
?>
