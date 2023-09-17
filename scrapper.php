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
  $info = 0;  
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
  if($info)
  {
     return ['data' => $data,'info' => $info]; 
  }else{
     return ['data' => 0,'info' => 0];  
  }   
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


// function findImagesWithAlt($html)    
// {
//     // Use a regular expression to find all <img> elements in the HTML
//     preg_match_all('/<img[^>]*>/i', $html, $matches);

//     $imagesWithAlt = [];

//     foreach ($matches[0] as $imgTag) {
//         // Use regular expressions to extract the src and alt attributes
//         preg_match('/src=[\'"]([^\'"]*)[\'"]/', $imgTag, $srcMatch);
//         preg_match('/alt=[\'"]([^\'"]*)[\'"]/', $imgTag, $altMatch);

//         $src = isset($srcMatch[1]) ? $srcMatch[1] : ''; // Get the src attribute value
//         $alt = isset($altMatch[1]) ? $altMatch[1] : '0'; // Get the alt attribute value or assign "0" if missing

//         $imagesWithAlt[] = [$src, $alt];
//     }

//     return $imagesWithAlt;
// }


// function findImagesWithAlt($html)    
// {
//     $extensions = ['jpg', 'jpeg', 'png', 'gif']; 
//     // Build a regular expression pattern to match <img> elements with specific extensions
//     $extensionsPattern = implode('|', $extensions);
//     $pattern = '/<img[^>]*src=["\']([^"\']+\.(?:' . $extensionsPattern . '))["\'][^>]*>/i';

//     preg_match_all($pattern, $html, $matches);

//     $imagesWithAlt = [];

//     foreach ($matches[0] as $imgTag) {
//         // Use regular expressions to extract the src and alt attributes
//         preg_match('/src=[\'"]([^\'"]*)[\'"]/', $imgTag, $srcMatch);
//         preg_match('/alt=[\'"]([^\'"]*)[\'"]/', $imgTag, $altMatch);

//         $src = isset($srcMatch[1]) ? $srcMatch[1] : ''; // Get the src attribute value
//         $alt = isset($altMatch[1]) ? $altMatch[1] : '0'; // Get the alt attribute value or assign "0" if missing

//         $imagesWithAlt[] = [$src, $alt];
//     }

//     return $imagesWithAlt;  
// }


function findImagesWithAlt($html) {
    $maxImages = 10; 
    $extensions = ['jpg', 'jpeg', 'png', 'gif']; 
    // Build a regular expression pattern to match <img> elements with specific extensions
    $extensionsPattern = implode('|', $extensions);
    $pattern = '/<img[^>]*src=["\']([^"\']+\.(?:' . $extensionsPattern . '))["\'][^>]*alt=["\']([^"\']+)["\'][^>]*>/i';

    preg_match_all($pattern, $html, $matches);

    $imagesWithAlt = [];
    $imageCount = 0;

    foreach ($matches[1] as $index => $filename) {
        $alt = $matches[2][$index];

        $imagesWithAlt[] = [$filename, $alt];

        $imageCount++;
        if ($imageCount >= $maxImages) {
            break; // Exit the loop when the maximum number of images is reached
        }
    }

    return $imagesWithAlt;
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


function getRobotsMetaNamesAndIndexStatus($html){ 
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
            }else{
              $robotData = [   
                   'content' => 0,
                   'status'  => 0,  
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
    if($html && $number)
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
    }else{
        $h1Txt = 0; 
    }
  
}


function getHTagsLength($html,$number)  
{   if($html && $number)
     {
       $h = getHTags($html,$number);
        if($h != 0)
        {
           $totalLength = 0;
            foreach ($h as $value) {
                $totalLength += strlen($value);
            }
           return $totalLength;  
        }else{
           return 0; 
        }
     }else{
        return 0;
     } 
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
                $social_links[] = trim($href);
                break; 
            }
        }
    } 
    return $social_links;   
} 


// function getPhoneNumbers($html) 
// {  
//     // Suppress warnings and errors during DOMDocument parsing
//     libxml_use_internal_errors(true);

//     $dom = new DOMDocument();
//     $dom->loadHTML($html); 

//     // Retrieve and clear any errors that occurred during parsing
//     $errors = libxml_get_errors();
//     libxml_clear_errors();

//     $xpath = new DOMXPath($dom);

//     // Define a regular expression pattern to match phone numbers
//     $pattern = '/\b\d{3}[-.\s]?\d{3}[-.\s]?\d{4}\b/';

//     // Use XPath to query for elements that might contain phone numbers
//     $elements = $xpath->query('//p|//a|//div|//span|//li|//td|//strong|//em|//b|//i');

//     $phoneNumbers = []; 

//     foreach ($elements as $element) {
//         $text = $element->textContent;
//         // Use preg_match_all to find all phone numbers in the text
//         if (preg_match_all($pattern, $text, $matches)) {
//             // Merge the matches into the phoneNumbers array
//             $phoneNumbers = array_merge($phoneNumbers, $matches[0]);
//         }
//     }

//     // Remove duplicates from the array
//     $phoneNumbers = array_unique($phoneNumbers);

//     return $phoneNumbers;
// }


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
    $phoneNumberCount = 0;

    foreach ($elements as $element) {
        $text = $element->textContent;
        // Use preg_match_all to find all phone numbers in the text
        if (preg_match_all($pattern, $text, $matches)) {
            // Merge the matches into the phoneNumbers array
            $phoneNumbers = array_merge($phoneNumbers, $matches[0]);

            $phoneNumberCount += count($matches[0]);
            if ($phoneNumberCount >= 10) {
                break; // Exit the loop when the first 10 phone numbers are found
            }
        }
    }

    // Remove duplicates from the array
    $phoneNumbers = array_unique($phoneNumbers); 

    // Return the first 10 phone numbers
    return array_slice($phoneNumbers, 0, 10);
}      



// function getAllOutboundLinks($html,$url) 
// {
//     // Create a DOMDocument object
//     $dom = new DOMDocument();

//     // Load the HTML content from the provided URL

//     if ($html === false) {
//         return false; // Failed to fetch HTML
//     }

//     // Load the HTML content into the DOMDocument
//     $dom->loadHTML($html);

//     // Create a DOMXPath object to query the document
//     $xpath = new DOMXPath($dom);

//     // Query for all anchor elements with an "href" attribute
//     $anchorNodes = $xpath->query('//a[@href]');

//     $outboundLinks = array();

//     // Loop through the anchor elements and extract the "href" attribute
//     foreach ($anchorNodes as $anchor) {
//         $href = $anchor->getAttribute('href'); 

//         // Check if the link is an outbound link (not a relative or empty link)
//         if (filter_var($href, FILTER_VALIDATE_URL) && parse_url($href, PHP_URL_HOST) !== parse_url($url, PHP_URL_HOST)) {
//             $outboundLinks[] = $href;
//         }
//     }   
//     return ['outBoundlinks' => $outboundLinks,'outBoundlinksCount' => count($outboundLinks)]; 
// } 


function getAllOutboundLinks($html, $url) {
    // Create a DOMDocument object
    $dom = new DOMDocument();

    // Load the HTML content from the provided URL 

    if ($html === false) {
        return false; // Failed to fetch HTML
    }

    // Load the HTML content into the DOMDocument
    $dom->loadHTML($html);

    // Create a DOMXPath object to query the document
    $xpath = new DOMXPath($dom);

    // Query for all anchor elements with an "href" attribute
    $anchorNodes = $xpath->query('//a[@href]');

    $outboundLinks = array();
    $linkCount = 0;

    // Loop through the anchor elements and extract the "href" attribute
    foreach ($anchorNodes as $anchor) {
        $href = $anchor->getAttribute('href');

        // Check if the link is an outbound link (not a relative or empty link)
        if (filter_var($href, FILTER_VALIDATE_URL) && parse_url($href, PHP_URL_HOST) !== parse_url($url, PHP_URL_HOST)) {
            $outboundLinks[] = $href;

            $linkCount++;
            if ($linkCount >= 10) {
                break; // Exit the loop when the first 10 outbound links are found
            }
        }
    }

    return ['outBoundlinks' => $outboundLinks, 'outBoundlinksCount' => count($outboundLinks)];
}


function getAllBrokenLinks($html,$url) 
{
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Suppress warnings for malformed HTML

    // Load the HTML content into the DOMDocument
    $dom->loadHTML($html);

    $links = $dom->getElementsByTagName('a');
    $baseURL = $url; // Store the base URL of the page 
    $brokenLinks = array();

    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        if ($href) { 

            // Resolve relative URLs to absolute URLs
            $absoluteURL = resolveURL($href, $baseURL);

            // Create a cURL session to check the HTTP status code
            $ch = curl_init($absoluteURL); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36"); 
            curl_exec($ch); 

            // Get the HTTP status code
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Check if the status code is 404 (Page Not Found)
            if ($httpCode == 404) {
                $brokenLinks[] = [ 
                    'url' => $absoluteURL, 
                    'status' => $httpCode,
                ];  
            }
        }
    }

    return $brokenLinks; 
}


// Function to resolve relative URLs to absolute URLs
function resolveURL($href, $baseURL)
{
    // If the URL is already absolute, return it as-is
    if (filter_var($href, FILTER_VALIDATE_URL)) {
        return $href;
    } 

    // Use PHP's built-in function to resolve relative URLs to absolute URLs
    return rtrim($baseURL, '/') . '/' . ltrim($href, '/');
} 


function getXmlSitemapAndRobotsTxtLinks($siteUrl)     
{ 
    
   $commonSitemapPaths = [
        '/sitemap.xml',
        '/sitemap_index.xml',
        '/sitemap/',
        '/sitemap.php',
        '/sitemap.txt',
        '/sitemap.xml.gz',
        '/sitemap1.xml',
        '/post-sitemap.xml',
        '/page-sitemap.xml',
        '/sitemap-index.xml',
        '/sitemapindex.xml',
        '/sitemap_index.xml.gz',
        '/sitemap/index.xml',
    ];

    $foundSitemap = null;
    $foundRobotsTxt = null; 

    foreach ($commonSitemapPaths as $sitemapPath) {
        $sitemapUrl = rtrim($siteUrl, '/') . $sitemapPath;
        $response = @getCurlContent($sitemapUrl); 

        if ($response !== false) {
            // Sitemap found
            $foundSitemap = $sitemapUrl;
            break; // Exit the loop if a sitemap is found
        }
    }

    // If no sitemap found in common locations, check robots.txt
    if ($foundSitemap) 
    { 
        $robotsTxtUrl = rtrim($siteUrl, '/') . '/robots.txt';  
        $robotsTxt = @getCurlContent($robotsTxtUrl);

        if ($robotsTxt !== false) {
            // Check for the sitemap URL in robots.txt
            if (preg_match('/Sitemap:\s*(.*?)\s/', $robotsTxt, $matches)) {
                $foundSitemap = trim($matches[1]);
            }
            $foundRobotsTxt = $robotsTxt;
        }
    } 

    return [ 
        'sitemap'   => $foundSitemap,
        'robotsTxt' => $foundRobotsTxt, 
    ];
}



function siteBigramCount($html) {
    // Remove script and style tags and trim whitespace
    $text = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>|<style\b[^>]*>[\s\S]*?<\/style>|<[^>]*>/', ' ', $html);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // Split the text into words
    $words = str_word_count($text, 1);

    // Combine adjacent words into bigrams
    $bigrams = array();
    $word_count = count($words);
    for ($i = 0; $i < $word_count - 1; $i++) {
        $bigram = ucwords(strtolower($words[$i])) . ' ' . ucwords(strtolower($words[$i + 1])); // Capitalize the first letter of each word
        $bigrams[] = $bigram;
    }

    // Count unique bigrams
    $unique_bigrams = array_unique($bigrams);
    $bigram_count = array();
    foreach ($unique_bigrams as $unique_bigram) {
        $count = count(array_keys($bigrams, $unique_bigram));
        if ($count > 2) { // Only count if found more than twice
            $bigram_count[$unique_bigram] = $count;
        }
    }

    // Sort bigrams by count in descending order
    arsort($bigram_count);

    // Return the top 10 bigrams
    $bigrams =  array_slice($bigram_count, 0, 10);

    // Display the top 10 bigrams and their counts
    $join = "";
    foreach ($bigrams as $bigram => $count) {
         $join .=  "$bigram ($count), ";
    }

    return $join;
}



function siteTrigramCount($html) {
    // Remove script and style tags and trim whitespace
    $text = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>|<style\b[^>]*>[\s\S]*?<\/style>|<[^>]*>/', ' ', $html);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // Split the text into words
    $words = str_word_count($text, 1);

    // Combine adjacent words into trigrams
    $trigrams = array();
    $word_count = count($words);
    for ($i = 0; $i < $word_count - 2; $i++) {
        $trigram = ucwords(strtolower($words[$i])) . ' ' . ucwords(strtolower($words[$i + 1])) . ' ' . ucwords(strtolower($words[$i + 2])); // Capitalize the first letter of each word
        $trigrams[] = $trigram;
    }

    // Count unique trigrams
    $unique_trigrams = array_unique($trigrams);
    $trigram_count = array();
    foreach ($unique_trigrams as $unique_trigram) {
        $count = count(array_keys($trigrams, $unique_trigram));
        if ($count > 2) { // Only count if found more than twice
            $trigram_count[$unique_trigram] = $count;
        }
    }

    // Sort trigrams by count in descending order
    arsort($trigram_count);

    // Return the top 10 trigrams
    $trigrams =  array_slice($trigram_count, 0, 10);
       
    // Display the top 10 trigrams and their counts
    $join = "";
    foreach ($trigrams as $trigram => $count) {
        $join .= "$trigram ($count), ";
    }
    return $join; 
}



function siteFourWordCombinationCount($html) {
    // Remove script and style tags and trim whitespace
    $text = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>|<style\b[^>]*>[\s\S]*?<\/style>|<[^>]*>/', ' ', $html);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // Split the text into words
    $words = str_word_count($text, 1);

    // Combine adjacent words into 4-word combinations
    $combinations = array();
    $word_count = count($words);
    for ($i = 0; $i < $word_count - 3; $i++) {
        $combination = ucwords(strtolower($words[$i])) . ' ' . ucwords(strtolower($words[$i + 1])) . ' ' . ucwords(strtolower($words[$i + 2])) . ' ' . ucwords(strtolower($words[$i + 3])); // Capitalize the first letter of each word
        $combinations[] = $combination;
    }

    // Count unique combinations
    $combination_count = array_count_values($combinations);

    // Filter combinations that appear more than twice
    $filtered_combinations = array_filter($combination_count, function($count) {
        return $count > 2;
    });

    // Sort combinations by count in descending order
    arsort($filtered_combinations);

    // Return the top 10 combinations
    $combinations =  array_slice($filtered_combinations, 0, 10);
    
    // Display the top 10 combinations and their counts
    $join = "";
    foreach ($combinations as $combination => $count) {
        $join .= "$combination ($count), ";
    }  
     return $join;  
}



function siteFiveWordCombinationCount($html) { 
    // Remove script and style tags and trim whitespace
    $text = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>|<style\b[^>]*>[\s\S]*?<\/style>|<[^>]*>/', ' ', $html);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // Split the text into words
    $words = str_word_count($text, 1);

    // Combine adjacent words into 5-word combinations
    $combinations = array();
    $word_count = count($words);
    for ($i = 0; $i < $word_count - 4; $i++) {
        $combination = ucwords(strtolower($words[$i])) . ' ' . ucwords(strtolower($words[$i + 1])) . ' ' . ucwords(strtolower($words[$i + 2])) . ' ' . ucwords(strtolower($words[$i + 3])) . ' ' . ucwords(strtolower($words[$i + 4])); // Capitalize the first letter of each word
        $combinations[] = $combination;
    }

    // Count unique combinations
    $combination_count = array_count_values($combinations);

    // Filter combinations that appear more than twice
    $filtered_combinations = array_filter($combination_count, function($count) {
        return $count > 2;
    });

    // Sort combinations by count in descending order
    arsort($filtered_combinations);

    // Return the top 10 combinations
    $combinations =  array_slice($filtered_combinations, 0, 10);

    // Display the top 10 combinations and their counts
    $join = "";
    foreach ($combinations as $combination => $count) {
        $join .= "$combination ($count), ";
    } 
    return $join; 
} 



function generateLinks($query)
{
    $html = googleSearchForPpcAdvertise($query);
    
    libxml_use_internal_errors(true); 

    // Create a new DOMDocument
    $dom = new DOMDocument;

    // Load the HTML content into the DOMDocument
    $dom->loadHTML($html);

     // Retrieve and clear any errors that occurred during parsing
     $errors = libxml_get_errors();
     libxml_clear_errors();   

    // Find all anchor (a) tags in the HTML
    $links = $dom->getElementsByTagName('a');

    // Loop through the links and extract their href attributes
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
         // Check if the href does not contain 'google.com'
        if (strpos($href, 'google.com') === false) {
            $explode = explode('&url=',$href);
            $url = @$explode[1]; 
            if($url) 
            {
                $data[] = trim($url);  
                unset($explode);  
                unset($url);  
            }
            
        }
    }
    return $data;   
}  


function linkCounts($links) 
{
   $totalLinks = 0;
        foreach ($links as $linkArray) {
            $totalLinks += count($linkArray);
        }
   return $totalLinks;
}


function isBase64Encoded($string) 
{ 
    return base64_encode(base64_decode($string)) === $string;
}



function auditScrap($url,$spreadSheetId,$queueId,$saveToSpreadSheet,$saveToDB,$saveToBothSpreadAndDB)   
{  
      //$links = 0; 
    if(isBase64Encoded($url) == true)
    {

      $link =  base64_decode($url);

    }else{

      $link =  $url;

    }     
    
    $start_time = microtime(true);
    $html = getCurlPageInfo($link);
    $end_time = microtime(true);
    $load_time = $end_time - $start_time;      
    $dom = new DOMDocument;
    @$dom->loadHTML($html['data']);  
    $links = array();  
    $counter = 1; 
    $arrayHref = $dom->getElementsByTagName('a');
     //echo "test1";
    if(isset($arrayHref))    
    {
          //echo "test2"; 
           foreach ($arrayHref as $a)
           {

                    $href = $a->getAttribute('href');  
                    if (!empty($href) && (strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0))
                    {   
                               $getCurlPageInfo = getCurlPageInfo($href);   
                               $getCurlPage1 = $getCurlPageInfo['info'];
                               $getCurlPage2 = $getCurlPageInfo['data'];     

                               $googleAnalyticsInfo = googleAnalyticsInfo($getCurlPage2); 

                               $gAnalyticStatus = $googleAnalyticsInfo['gAnalyticsFound'];

                               $gAnalyticsMatched = $googleAnalyticsInfo['gAnalyticsMatched'];

                               $pageScore = 0;

                               if($gAnalyticStatus == 0){ $pageScore += 1; };
                               if($gAnalyticsMatched == 0){ $pageScore += 1; };

                               $ssl = hasSSL($href);            


                                if (preg_match('/<h1[^>]*>.*?<\/h1>/i', $getCurlPage2)){
                                    $h1InTitle = 1;
                                } else {
                                    $h1InTitle = 0;;
                                } 
                                
                                $getMetaDescription = 0;
                                if($getCurlPage2)
                                {
                                    $getMetaDescription = getMetaDescription($getCurlPage2);
                                }
                                
                                $canonicalLink = 0;
                                if($getCurlPage2)
                                {
                                   $canonicalLink = getCanonicalURL($getCurlPage2); 
                                }
                                

                                if (!empty($canonicalLink)) {
                                    $canonicalUrlLength = strlen($canonicalLink);
                                } else {
                                    $canonicalLink = 0; 
                                }


                                $statusCode = $getCurlPage1['http_code'];
                                if($statusCode == 200)
                                {
                                   $siteStatus = "OK"; 
                                }else{
                                   $siteStatus = "FAIL";  
                                } 

                                // H1 Length

                                $h1Length = getHTagsLength($getCurlPage2,1);


                                // Load H1,H2.. Counts  
                                $h1 = getHTags($getCurlPage2,1);
                                 
                                 $h1_h = "";
                                if(is_array($h1))
                                {
                                    $h1Count = count($h1);
                                    
                                    foreach($h1 as $h1_headings){ $h1_h .= "H1-".$h1_headings."<br>"; };  
                                }else{
                                    $h1Count = 0 ;
                                }


                                $h2 = getHTags($getCurlPage2,2); 
                                $h2_h = "";  
                                if(is_array($h2))
                                {
                                    $h2Count = count($h2);
                                   
                                    foreach($h2 as $h2_headings){ $h2_h .= "H2-".$h2_headings."<br>"; };  
                                }else{
                                    $h2Count = 0 ;  
                                }

                                $h3 = getHTags($getCurlPage2,3); 
                                $h3_h = "";
                                if(is_array($h3))
                                {
                                    $h3Count = count($h3);
                                    
                                    foreach($h3 as $h3_headings){ $h3_h .= "H3-".$h3_headings."<br>"; };  
                                }else{
                                    $h3Count = 0 ;  
                                }

                                $h4 = getHTags($getCurlPage2,4); 
                                $h4_h = "";
                                if(is_array($h4))
                                {
                                    $h4Count = count($h4);
                                    
                                    foreach($h4 as $h4_headings){ $h4_h .= "H4-".$h4_headings."<br>"; };  
                                }else{
                                    $h4Count = 0 ;  
                                }

                                $h5 = getHTags($getCurlPage2,5); 
                                $h5_h = "";
                                if(is_array($h5))
                                {
                                    $h5Count = count($h5); 
                                    
                                    foreach($h5 as $h5_headings){ $h5_h .= "H1-".$h5_headings."<br>"; }; 
                                }else{
                                    $h5Count = 0 ;  
                                }


                                $h6 = getHTags($getCurlPage2,6); 
                                $h6_h = "";
                                if(is_array($h6))
                                {
                                    $h6Count = count($h6); 
                                    
                                    foreach($h6 as $h6_headings){ $h6_h .= "H1-".$h6_headings."<br>"; }; 
                                }else{
                                    $h6Count = 0 ;   
                                }  
                                
                                
                                $headings = $h1_h.$h2_h.$h3_h.$h4_h.$h5_h.$h6_h; 
                                
                               // Count the number of <img> elements in the HTML
                                $image_count = countImages($getCurlPage2);

                                if ($image_count > 0) {
                                    $imageCount = $image_count;
                                } else {
                                    $imageCount = 0;
                                }

                                $imgAltLink = 0;    
                                $imgWithAlt = findImagesWithAlt($getCurlPage2);
                                if(is_array($imgWithAlt) && count($imgWithAlt) > 0)
                                { 
                                    foreach($imgWithAlt as $imgalt)
                                    {
                                       $imgAltLink2[] =  $imgalt[0] . '['.$imgalt[1].']';
                                       $imgAltLink = implode(',',$imgAltLink2); 
                                    }
                                }else{
                                      $imgAltLink = 0;      
                                }
                                

                                $heading_count = countHeadings($getCurlPage2);

                                if ($heading_count > 0) {
                                    $headingCount =  $heading_count;
                                } else {
                                    $headingCount = 0;   
                                } 



                                // Count the number of <p> elements in the HTML
                                $paragraph_count = countParagraphs($getCurlPage2);

                                if ($paragraph_count > 0) {
                                    $paragraphCount =  $paragraph_count;
                                } else {
                                    $paragraphCount = 0;
                                }



                                // Check if the website has a favicon
                                $has_favicon = checkFaviconExists($getCurlPage2);

                                if ($has_favicon) {
                                    $favicon = 1;
                                } else {
                                    $favicon = 0; 
                                }



                                // Check the contents of the robots.txt file
                                $indexability = getRobotsMetaNamesAndIndexStatus($getCurlPage2);



                               
                               // Extract meta keywords from the HTML
                                $meta_keywords = getMetaKeywords($getCurlPage2); 

                                if (!empty($meta_keywords)) {
                                    $metaKeywords =  $meta_keywords;
                                } else {
                                    $metaKeywords = 0;
                                }   



                               // Check the X-Robots-Tag header in the HTTP response
                                $isAllowed = isXRobotsTagAllowed($href);
                              
                                if ($isAllowed) {
                                    $xRobotTag = 1; 
                                } else {
                                    $xRobotTag = 0;
                                } 

                                $socialLinks = getSocialLinks($getCurlPage2);

                                if(count($socialLinks) > 0) 
                                {
                                   $socialLinks = implode(',', $socialLinks);
                                }else{
                                   $socialLinks = 0;       
                                }      
                                  
                                
                                $getPhoneNumbers = getPhoneNumbers($getCurlPage2);
                                if(isset($getPhoneNumbers))  
                                {
                                    $phoneNumbers = implode(',',$getPhoneNumbers);
                                }else{
                                    $phoneNumbers = 0;     
                                }  

                                $h1InTitle = h1InTitle($getCurlPage2);    
                            

                                $pageSize = $getCurlPage1['size_download'];
                                $pageSize = round($pageSize/1024,2) . 'kb';  
                                 
                                 if($pageSize > 1024 * 3){ $pageScore += 1; }; 

                                $contactForm = hasContactForm($getCurlPage2);   

                                $siteTitle = getSiteTitle($getCurlPage2);   

                                $getAllOutboundLinks = getAllOutboundLinks($getCurlPage2,$href);    
                                $outBoundLinks = $getAllOutboundLinks['outBoundlinks']; 
                                $outBoundLinksCount = $getAllOutboundLinks['outBoundlinksCount']; 


                                // $getAllBrokenLinks = getAllBrokenLinks($getCurlPage2,$href);

                                // if ($getAllBrokenLinks === false) 
                                // { 
                                //     $brokenLinks = 0; 
                                // }else{

                                //     foreach ($getAllBrokenLinks as $link)
                                //     {
                                //         $brokenLinksArr[] = $link['url'] . '['.$link['status'] . ']';
                                //     } 
                                //     $brokenLinks = implode(',',$brokenLinksArr);
                                // }        


                                $sitemapXmlRobotsTxt = getXmlSitemapAndRobotsTxtLinks($href);
                                $sitemapUrl = 0;
                                $sitemapUrlStatus = 0;
                                if($sitemapXmlRobotsTxt['sitemap'])  
                                {
                                   $sitemapUrl = $sitemapXmlRobotsTxt['sitemap'];
                                   $sitemapUrlStatus = 1;
                                }
                                $robotsTxt = 0;
                                $robotsTxtStatus = 0;
                                if($sitemapXmlRobotsTxt['robotsTxt']) 
                                {
                                   //$robotsTxt = $sitemapXmlRobotsTxt['robotsTxt'];
                                   $robotsTxt = $href.'/robots.txt';
                                   $robotsTxtStatus = 1;      
                                } 


                                $twoWords = siteBigramCount($getCurlPage2);         
                                $threeWords = siteTrigramCount($getCurlPage2);          
                                $fourWords = siteFourWordCombinationCount($getCurlPage2);         
                                $fiveWords = siteFiveWordCombinationCount($getCurlPage2);

                                   $response_time = $getCurlPage1['total_time'];
                                   if($response_time > 3){ $pageScore += 1; };
                                   if($load_time > 3){ $pageScore += 1; };
                                   

                                   $pageScoreTotal = 100 * (44 - $pageScore)/44;

                                    $data = [
                                     'link'  => $link,      
                                     'url'   => $href, 
                                     'depth' => calculateUrlDepth($href),   
                                     'score' => $pageScoreTotal,  
                                     'gAnalyticStatus' => $googleAnalyticsInfo['gAnalyticsFound'],
                                     'gAnalyticMatch'  => $googleAnalyticsInfo['gAnalyticsMatched'],  
                                     'response_time'   => $getCurlPage1['total_time'],  
                                     'load_time'       => $load_time,
                                     'ssl' => $ssl ? "Yes" : "No",   
                                     'rankKeyword'     => "",   
                                     'metaDescription' => $getMetaDescription,  
                                     'metaDescriptionLength' => strlen($getMetaDescription),  
                                     'canonicalLink' => $canonicalLink,   
                                     'status' => $siteStatus,   
                                     'statusCode' => $getCurlPage1['http_code'],   
                                     'emails' => 0,  
                                     'email_privacy' => 0, 
                                     'phones' => $phoneNumbers,     
                                     'socials' => $socialLinks,        
                                     'contactForms' => $contactForm ? "Yes"  : "No",      
                                     'title' => $siteTitle,       
                                     'titleLength' => strlen(getSiteTitle($getCurlPage2)),         
                                     'wordCounts' => siteWordCount($getCurlPage2),
                                     'h1InTitle' => $h1InTitle ? "Yes" : "No",      
                                     'h1' => ($h1 == 0) ? 0 : implode('<br>',$h1),
                                     'h2' => ($h2 == 0) ? 0 : implode('<br>',$h2),            
                                     'h1Length' => $h1Length,             
                                     'h1Count' => $h1Count,          
                                     'h2Count' => $h2Count,          
                                     'h3Count' => $h3Count,          
                                     'h4Count' => $h4Count,           
                                     'h5Count' => $h5Count,          
                                     'h6Count' => $h6Count,
                                     'headings' => $headings,                 
                                     'imageCount' => $imageCount,     
                                     'imageWithAlt' => $imgAltLink,       
                                     'headingCount' => $headingCount,      
                                     'paragraphCount' => $paragraphCount,     
                                     'faviconStatus' => $favicon ? "Yes" : "No",         
                                     'contentType' => $getCurlPage1['content_type'],      
                                     'indexability'       => isset($indexability['status']) ? $indexability['status'] : 0,        
                                     'indexabilityStatus' => isset($indexability['content']) ? $indexability['content'] : 0,           
                                     'metaKeyword' => $metaKeywords,      
                                     'metaKeywordLength' => strlen($metaKeywords),       
                                     'xRobotTag' => $xRobotTag,  
                                     'pageSize' => $pageSize,  
                                     'outBoundLinks' => implode(',',$outBoundLinks),        
                                     'outBoundLinksCount' => $outBoundLinksCount,
                                     //'brokenLinks' => $brokenLinks,
                                     'sitemapUrl' => $sitemapUrl,   
                                     'robotsTxt' => $robotsTxt,
                                     'sitemapUrlStatus' => $sitemapUrlStatus,
                                     'robotsTxtStatus' => $robotsTxtStatus,
                                     '2words' => $twoWords,
                                     '3words' =>$threeWords,             
                                     '4words' =>$fourWords,             
                                     '5words' =>$fiveWords,
                                     'last_modified' => ''                
                                    ]; 
                                     
                                    if($data)  
                                    {   
                                       insertSpreadSheetsRecords($href,$spreadSheetId,$data,$queueId,$saveToSpreadSheet,$saveToDB,$saveToBothSpreadAndDB);  
                                    }
                                    
                                   $links[] = $data;  

                                    if($counter == 5)   
                                    {
                                        break;                       
                                    }     
                                    $counter++;             
                                       //echo '<pre>';print_r($links);echo '</pre>';        
                                       //print_r($getCurlPageInfo['info']);          
                                       //exit;                               
                    }     
    
    
           } // end-foreach 

     return $links;
    } // end-if       
}




 function safeJsonDecode($jsonString) { 
    // Trim leading and trailing whitespaces
    $jsonString = trim($jsonString);

    // Remove Byte Order Mark (BOM)
    $jsonString = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $jsonString);

    // Attempt to decode the JSON string
    $jsonData = json_decode($jsonString, true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Handle the JSON parsing error, or return an error message
        return array(
            'error' => 'JSON decode error: ' . json_last_error_msg()
        );
    }

    // JSON decoding was successful, return the decoded data
    return $jsonData;
}

function addBgColor($s)      
{
   if($s >= 1)   
   {
      $class =  "tdOk";
   }elseif($s == 0){  
     $class = "tdErr";
   }elseif($s == "OK"){
     $class = "tdOk"; 
   }elseif($s == "FAIL"){
     $class = "tdErr"; 
   }elseif($s == "index"){
     $class = "tdOk";  
   }elseif($s == "Yes"){
     $class = "tdOk";  
   }elseif($s == "No"){ 
     $class = "tdErr";  
   }elseif($s == "yes"){
     $class = "tdOk";  
   }elseif($s == "no"){ 
     $class = "tdErr";   
   }    
   return $class;  
}

function tdColorStatus($s,$name)  
{ 
   if($name == "H1") 
   {  
      if($s) 
      {
         $class =  "tdOk";
      }
   }

   if($name == "Title")  
   {  
      if($s) 
      {
         $class =  "tdOk";
      }else{
        $class =  "tdErr"; 
      }
   }

   if($name == "H1-Length") 
   {
      if($s >= 50 && $s <= 70)
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr"; 
      }
   }

   if($name == "Phones") 
   {
      if($s)
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr"; 
      }
   }

   if($name == "Socials") 
   {
      if($s != 0) 
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr";  
      }
   }      


   if($name == "Contact-Forms") 
   {
      if($s) 
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr";  
      }
   }


   if($name == "Meta-Description")
   {
      if($s)
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr"; 
      }
   }

    if($name == "Meta-Description-Length")
   {
      if($s >= 150 && $s <= 160)
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr"; 
      }
   }

    if($name == "Word-Counts")
   {
      if($s >= 0 && $s <= 600) 
      {
         $class =  "tdErr";
      }else{
         $class =  "tdOk";  
      }
   }

   if(in_array($name,["H1-Count","H2-Count","H3-Count","H4-Count","H5-Count","H6-Count"]))
   {
      if($s == 0)
      {
         $class =  "tdErr";
      }else{
         $class =  "tdOk";
      } 
   } 


   if($name == "Heading-Count")
   {
      if($s < 5)
      {
         $class =  "tdErr"; 
      }else{
         $class =  "tdOk"; 
      }
   } 

   if($name == "Image-Count")
   {
      if($s > 0 )
      {
         $class =  "tdOk";
      }else{
         $class =  "tdErr"; 
      }
   } 
   
   if($name == "Image-Alt-Tags")
   {
      if($s == 0 )
      {
         $class =  "tdErr";
      }else{
         $class = "tdOk";
      }
   } 

   if($name == "Broken-Links")
   {
      if($s == 0 )
      {
         $class =  "tdErr";
      }else{
         $class = "tdOk";
      }
   }

   if($name == "Favicon")
   {
      if($s == 0 )
      {
         $class =  "tdErr";
      }else{
         $class = "tdOk";
      }
   }

   if($name == "Indexability")
   {
      if($s)
      {
         $class =  "tdOk";
      }else{
         $class = "tdErr";
      }
   }

   if($name == "Indexability-Status") 
   {
      if($s)
      {
         $class =  "tdOk";
      }else{
         $class = "tdErr";
      }
   }

   if($name == "XML-Sitemap")
   {
      if($s == 1 )
      {
         $class =  "";
      }else{
         $class = "tdErr";
      }
   }  

    if($name == "Robots")
   {
      if($s == 1 )
      {
         $class =  "";
      }else{ 
         $class = "tdErr";
      }
   }               

   
   return $class;     
}       

function preTest($data) 
{
  echo '<pre>';
    print_r($data);
  echo '</pre>';
}


function removeQueryString($url)  
{
    // Find the last position of '/'
    $lastSlashPos = strrpos($url, '/');

    if ($lastSlashPos !== false) 
    {
        // Split the URL into two parts
        $beforeLastSlash = substr($url, 0, $lastSlashPos);
        $afterLastSlash = substr($url, $lastSlashPos + 1); 
    }
    return $beforeLastSlash; 
}



?> 