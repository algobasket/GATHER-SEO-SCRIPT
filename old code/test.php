<?php
function getRobotsMetaNamesAndIndexStatus($html) {
    $dom = new DOMDocument();
    $dom->loadHTML($html);

    $metaTags = $dom->getElementsByTagName('meta');
    $robotData = [];

    foreach ($metaTags as $tag) {
        if ($tag->getAttribute('name') === 'robots') {
            $robotContent = $tag->getAttribute('content');
            $isIndex = strpos($robotContent, 'index') !== false;
            $isNoIndex = strpos($robotContent, 'noindex') !== false;

            if ($isIndex) {
                $status = 'index';
            } elseif ($isNoIndex) {
                $status = 'noindex';
            } else {
                $status = 'unknown'; // Handle other cases if needed
            }

            if (strpos($robotContent, 'index') !== false) {
                $robotData = [   
                   'content' => $robotContent,
                   'status' => $status,
                ];
            }
        } 
    }

    return $robotData;
}

function getCurlContent($url) 
{ 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  //curl_setopt($ch, CURLOPT_HEADER, 1);
  //curl_setopt($ch, CURLOPT_NOBODY, 1);  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
  $data = curl_exec($ch);

  curl_close($ch);
  return $data;
} 

// Usage example 
$html = getCurlContent('https://www.dunhamlaw.com/'); // Replace with the URL of the webpage you want to extract phone numbers from
$getRobotsMetaNamesAndIndexStatus = getRobotsMetaNamesAndIndexStatus($html);

print_r($getRobotsMetaNamesAndIndexStatus);  

//implode('<br>',$getRobotsMetaNamesAndIndexStatus);     

?>
