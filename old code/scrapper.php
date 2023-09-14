<?php 
/*
  ************************ SCRAPPING SCRIPT **********************
  *********************** Coded By Algobasket *********************
  ****************** Contact : algobasket@gmail.com ***************
  *********************** Github : algobasket *********************    
*/ 





// CURL function to get site content 

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


// CURL function to get site content and info 

function getCurlPageInfo($url)  
{ 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  //curl_setopt($ch, CURLOPT_HEADER, 1); 
  //curl_setopt($ch, CURLOPT_NOBODY, 1); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
  $data = curl_exec($ch);
  // Check if any error occurred
  if (!curl_errno($ch)) {
    $info = curl_getinfo($ch);
  }
  curl_close($ch);  
  return ['data' => $data,'info' => $info]; 
} 





// Function to extract HTML content between assigned tags

 function extract_section($inputstring,$start,$end,$includestart,$includeend,$returnarray=0,$separator = "",$leave_start_elements = 0,$leave_end_elements = 0,$striphtml = 0,$search_arr = 0,$replace_arr = 0)
 {
         $final_result_arr = array();   
            if($returnarray == 1) 
            {      
                $result_arr = explode(($separator)? $result : $start.$result, $inputstring);
                //print_r($result_arr);
                foreach($result_arr as $result)
                {
                  $temp = extract_section(($separator)? $result : $start.$result, $start, $end,$includestart,$includeend,0,"", 0, 0, $striphtml, $search_arr, $replace_arr);

                  array_push($final_result_arr, $temp);
                  
                }
                $arr_count = count($final_result_arr);
              
                if($leave_start_elements>0  || $leave_end_elements>0)
                {
                if(($leave_end_elements > $arr_count) || ($leave_start_elements > $arr_count)) 
                  {  return $final_result_arr;  }
                elseif(($leave_start_elements + $leave_end_elements) > $arr_count)  
                  {  return array_slice($final_result_arr, $leave_start_elements);  }
                else
                  {  return array_slice($final_result_arr, $leave_start_elements, $arr_count - $leave_start_elements - $leave_end_elements);  }
                }
                return $final_result_arr;
            }

            $startpos=strpos($inputstring,$start);
            if($startpos === false) return 0;
            $startlength=strlen($start);
            $endpos=$startpos+strpos(strstr($inputstring,$start),$end);
            $endlength=strlen($end);

            if($includestart==0)
            {
              if($includeend==0)
              {
                $outputstring=substr($inputstring,$startpos+$startlength,$endpos-$startpos-$startlength);
              }else{
                $outputstring=substr($inputstring,$startpos+$startlength,$endpos-$startpos-$startlength+$endlength);	
              }
            }else{

                if($includeend==0)
                {
                    $outputstring=substr($inputstring,$startpos,$endpos-$startpos);	
                }else{

                    $outputstring=substr($inputstring,$startpos,$endpos-$startpos+$endlength);	
                }
            } 

        if($search_arr && $replace_arr)  
            $outputstring = str_replace($search_arr, $replace_arr, trim($outputstring));
        if($striphtml == 1) $outputstring = strip_tags($outputstring);
            $outputstring = trim($outputstring);
          return $outputstring;
   }



// Function to perform a Google search for PPC Advertiser

function googleSearchForPpcAdvertise($query)   
{ 
    $url = "https://www.google.com/search?q=" . urlencode($query);
    $response = getCurlContent($url);
    
    return $response;      
}  

// Function to perform a Google search Email

function googleSearchEmail($query)   
{ 
    $url = "https://www.google.com/search?q=" . urlencode($query);
    $response = getCurlContent($url);
    $res = preg_match_all(
        "/[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}/i",
        $response,
        $matches
      );
      if($res)
      {
            foreach(array_unique($matches[0]) as $email)
            {
               $data[] = $email;
            }
      }else{
        return 0;
      }
    return $data;    
} 





// Function to perform a Google search Email

function googleSearchPhone($query) 
{  
  $url = "https://www.google.com/search?q=" . urlencode($query);
  $response = getCurlContent($url); 
  $pattern = "/\b\d{3}[-.]?\d{3}[-.]?\d{4}\b/";
  preg_match_all($pattern, $response, $matches);

  $data = array();

  if (!empty($matches[0])) {
      foreach (array_unique($matches[0]) as $phone) {
          $data[] = $phone;
      }
  } else {
      return 0;
  }
  return $data;
} 






// Function to perform a Google search Address

function googleSearchAddress($query)
{
  $url = "https://www.google.com/search?q=" . urlencode($query);
  $response = getCurlContent($url); 
  $pattern = "/<div class=\"BNeawe iBp4i AP7Wnd\">(.+?)<\/div>/";
  preg_match_all($pattern, $response, $matches);

  $data = array();
  if (!empty($matches[1])) {
      foreach (array_unique($matches[1]) as $location) {
          $data[] = strip_tags($location); // Remove HTML tags
      }
  } else {
      return 0;
  } 
  return $data;
} 



function findPageDepth($url, $maxDepth = 10) 
{
    static $visited = []; // Keep track of visited URLs
    static $depth = 0;    // Current depth

    if ($depth > $maxDepth) {
        return; // Limit the depth to avoid infinite loops
    }

    if (!in_array($url, $visited)) {
        // Output the URL with its depth
         str_repeat('-', $depth) . "> $url (Depth: $depth)\n";

        // Mark the URL as visited
        $visited[] = $url;

        // Fetch the page content
        $html = getCurlContent($url);  

        if ($html) {
            // Use regular expressions or a DOM parser to extract links from the HTML
            $pattern = '/<a\s+.*?href=["\'](https?:\/\/[^"\']+).*?>/i';
            preg_match_all($pattern, $html, $matches);

            // Iterate through the found links and recursively process them
            foreach ($matches[1] as $link) {
                $depth++;
                findPageDepth($link, $maxDepth);
                $depth--;
            }
        }
    }
}


function calculateUrlDepth($url)  
{
    // Remove any query parameters and fragments from the URL
    $url = strtok($url, '?#');

    // Count the number of slashes ("/") in the URL's path
    $path = parse_url($url, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    
    return count($segments);
} 

function getPageLoadTime($url) {
    $start_time = microtime(true);
    $response = file_get_contents($url); // Send a request to the website
    $end_time = microtime(true);

    if ($response === false) {
        return "Error: Unable to fetch URL.";
    }

    $load_time = $end_time - $start_time;
    return $load_time;
}


function limitWords($text, $limit)
{
        $words = explode(" ", $text);
        $limitedWords = array_slice($words, 0, $limit);
        return implode(" ", $limitedWords);
}   

function googleAnalyticsInfo($html) 
{
  if (preg_match('/<script\s+async\s+src="https:\/\/www\.googletagmanager\.com\/gtag\/js\?id=([^"]+)"><\/script>/', $html, $matches))
  {  
     $measurementId = $matches[1];
     $gAnalyticsMatched = isGoogleAnalyticsMatched($measurementId);  
     return ['gAnalyticsFound' => 1,"gAnalyticsMatched" =>$gAnalyticsMatched]; 
   }else{
     return ['gAnalyticsFound' => 0,"gAnalyticsMatched" => 0];    
   } 
}


function isGoogleAnalyticsMatched($measurementId)
{
    $trackingUrl = 'https://www.google-analytics.com/collect';
    $payload = [
        'v' => '1',
        'tid' => $measurementId,
        't' => 'pageview',
        'cid' => '555', // You can generate a random Client ID
    ];

    $ch = curl_init($trackingUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === 'OK') {
        return 1;
    } else {
        return 0;
    }
} 



function hasSSL($url) 
{       
    // Check if the URL starts with "https://"
    if (strpos($url, "https://") === 0) {
        return 1; // URL uses SSL/TLS (HTTPS)
    } else {
        return 0; // URL does not use SSL/TLS (HTTP)
    }
}           


function getMetaDescription($html)
{
    // Use a regular expression to extract the content of the meta description tag
    preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\'](.*?)["\']/i', $html, $matches);

    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return "";
    }
}

function getCanonicalURL($html) 
{
    // Use a regular expression to extract the content of the canonical link tag
    preg_match('/<link[^>]*rel=["\']canonical["\'][^>]*href=["\'](.*?)["\']/i', $html, $matches);

    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return "";
    }
}

function hasContactForm($html)  
{
    // Check if the response contains HTML elements associated with contact forms
    if (strpos($html, '<form') !== false && strpos($html, 'contact') !== false) {
        return 1;
    } else {
       return 0;  
    }
}



function siteWordCount($html)
{
  // Remove HTML tags and trim whitespace
    $text = strip_tags($html);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // Count words
    $word_count = str_word_count($text);
    return $word_count;  
}



function getSiteTitle($html)
{
    // Use a regular expression to extract the content of the title element
    preg_match('/<title[^>]*>(.*?)<\/title>/i', $html, $matches);

    if (isset($matches[1])) {
        return trim($matches[1]);
    } else {
        return "";
    }
}



function countImages($html) 
{
    // Use a regular expression to find all <img> elements in the HTML
    preg_match_all('/<img[^>]*>/i', $html, $matches);

    return count($matches[0]);
}



function countHeadings($html)
{
    // Use a regular expression to find all heading elements (h1, h2, h3, etc.) in the HTML
    preg_match_all('/<h[1-6][^>]*>/i', $html, $matches);

    return count($matches[0]);
}



function h1InTitle($html) 
{
    // Use a regular expression to find all heading elements (h1, h2, h3, etc.) in the HTML
    preg_match_all('/<h[1][^>]*>/i', $html, $matches);
    if(count($matches[0]) > 0)
    {
       return 1; 
    }else{
       return 0; 
    }
}


function countParagraphs($html)
{
    // Use a regular expression to find all <p> elements in the HTML
    preg_match_all('/<p[^>]*>/i', $html, $matches);

    return count($matches[0]);
}

function checkFaviconExists($html)
{
    // Check if the HTML contains a link to a favicon
    return (strpos($html, '<link rel="icon"') !== false || strpos($html, '<link rel="shortcut icon"') !== false);
}


// function checkIndexability($url)
// {
//      // Construct the URL for the robots.txt file
//       $robots_url = rtrim($url, '/') . '/robots.txt';

//       $ch = curl_init($robots_url);

//       // Set cURL options
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//       $response = curl_exec($ch);

//       if ($response === false) {
//           //echo "cURL error: " . curl_error($ch);
//       } else {
//           // Check the contents of the robots.txt file
//           $is_indexable =  strpos($response, 'User-agent: *') !== false && strpos($response, 'Disallow:') === false;

//           if ($is_indexable) {
//               return 1; 
//           } else {
//              return 0;;
//           }
//       }
//        // Check if the robots.txt file allows indexing
       
// }

// function checkIndexabilityStatus($html)
// {
//     // Parse the HTTP response headers 
//     $lines = explode("\n", $html); 

//     $index = false; // Initialize indexability status to false
//     $follow = false; // Initialize followability status to false

//     // Look for the "X-Robots-Tag" header
//     foreach ($lines as $line) {
//         if (stripos($line, "X-Robots-Tag:") !== false) {
//             $parts = explode(":", $line);
//             if (count($parts) >= 2) {
//                 $value = trim($parts[1]);
//                 if (stripos($value, "noindex") !== false) {
//                     $index = false; // Website is not indexable
//                 } elseif (stripos($value, "index") !== false) {
//                     $index = true; // Website is indexable
//                 }
//                 if (stripos($value, "nofollow") !== false) {
//                     $follow = false; // Links should not be followed
//                 } elseif (stripos($value, "follow") !== false) {
//                     $follow = true; // Links should be followed
//                 }
//             }
//         }
//     }

//     // Determine and return the indexability status
//     if ($index && $follow) {
//         return "Indexable,Links Followed";
//     } elseif ($index && !$follow) {
//         return "Indexable,Links Not Followed";
//     } elseif (!$index && $follow) {
//         return "Not Indexable,Links Followed";
//     } else {
//         return "Not Indexable,Links Not Followed";
//     }
// } 


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



function getMetaKeywords($html)
{
    // Use regular expression to extract the content of the meta keywords tag
    preg_match('/<meta\s+name="keywords"\s+content="([^"]+)"/i', $html, $matches);

    if (isset($matches[1])) {
        return trim($matches[1]);
    } else {
        return "";
    }
}



// function getXRobotsTag($html)
// {
//     // Split the HTTP response headers into lines
//     $lines = explode("\n", $html);

//     // Look for the X-Robots-Tag header
//     foreach ($lines as $line) {
//         if (stripos($line, "X-Robots-Tag:") !== false) {
//             return trim(substr($line, strpos($line, ":") + 1));
//         }
//     }

//     // If X-Robots-Tag header is not found, return an empty string
//     return "";
// }

function isXRobotsTagAllowed($url) {
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Execute the request
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Check if X-Robots-Tag header is present in the response headers
    return stripos($response, 'X-Robots-Tag:') !== false;
}




function getHTags($html,$number)  
{
  // Suppress warnings and errors during DOMDocument parsing
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();

    $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $errors = libxml_get_errors();

    libxml_clear_errors();

    // Create a DOMXPath instance for querying
    $xpath = new DOMXPath($dom);

    // Find all <h1> elements in the HTML 
    $h1_elements = $xpath->query('//h'.$number);

    if ($h1_elements->length > 0) {
        $h1Txt = array();
        foreach ($h1_elements as $h1) {
            // Get the text content of the <h1> element
            $h1Txt[] = $h1->textContent;
        }
    } else {
        $h1Txt = 0; // Assign an array with the message
    }
    return $h1Txt;  
}



function getSocialLinks($html)     
{    
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Disable error reporting for HTML parsing
    $dom->loadHTML($html);
    libxml_clear_errors(); // Clear any HTML parsing errors

    // Create a DOMXPath object
    $xpath = new DOMXPath($dom);

    // Define a list of social media domains to search for in links
    $social_domains = array(
        "facebook.com",
        "twitter.com",
        "instagram.com",
        "linkedin.com",
        // Add more social media domains as needed
    ); 

    // Initialize an array to store the social media links found
    $social_links = array();

    // Iterate through all <a> (anchor) elements in the HTML
    foreach ($xpath->query('//a') as $a) {
        $href = $a->getAttribute('href');
        foreach ($social_domains as $domain) {
            if (strpos($href, $domain) !== false) {
                $social_links[] = $href;
                break;
            }
        }
    } 
    return $social_links; 
} 


function getPhoneNumbers($html) 
{  
    // Suppress warnings and errors during DOMDocument parsing
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html); 

    // Retrieve and clear any errors that occurred during parsing
    $errors = libxml_get_errors();
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // Define a regular expression pattern to match phone numbers
    $pattern = '/\b\d{3}[-.\s]?\d{3}[-.\s]?\d{4}\b/';

    // Use XPath to query for elements that might contain phone numbers
    $elements = $xpath->query('//p|//a|//div|//span|//li|//td|//strong|//em|//b|//i');

    $phoneNumbers = []; 

    foreach ($elements as $element) {
        $text = $element->textContent;
        // Use preg_match_all to find all phone numbers in the text
        if (preg_match_all($pattern, $text, $matches)) {
            // Merge the matches into the phoneNumbers array
            $phoneNumbers = array_merge($phoneNumbers, $matches[0]);
        }
    }

    // Remove duplicates from the array
    $phoneNumbers = array_unique($phoneNumbers);

    return $phoneNumbers;
}

function addBgColor($s)     
{
   if($s >= 1)   
   {
      $class =  "bg-success";
   }elseif($s == 0){  
     $class = "bg-danger";
   }elseif($s == "OK"){
     $class = "bg-success"; 
   }elseif($s == "FAIL"){
     $class = "bg-danger"; 
   }elseif($s == "index"){
     $class = "bg-success"; 
   } 
   return $class;  
}       



?> 