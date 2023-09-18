<?php
ob_start();
session_start();
error_reporting(0); 
set_time_limit(0);  

// Check if the application is running on localhost
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

if ($host === 'localhost') {
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'hireageek';
} else {  
    // Use live server database configuration
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'hireageek';   
}      

// Create connection
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword,$dbName);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}     
//require 'scrapper.php';  
// Enable error reporting for debugging purposes
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // Function to fetch content using cURL
// function getCurlContent($url)
// {
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
    
//     $data = curl_exec($ch);

//     if (curl_errno($ch)) {
//         echo 'Error: ' . curl_error($ch);
//     }

//     curl_close($ch);
//     return $data; 
// }

// // Function to scrape CEO information from Google search
// function googleSearchCEO($query)
// {
//     $url = "https://www.google.com/search?q=" . urlencode($query);
//     $response = getCurlContent($url);

//     if ($response === false) {
//         return "Error: Unable to fetch Google search results.";
//     }

//     $matches = [];
//     $res = preg_match_all("/(CEO|Chief Executive Officer|Owner):?\s*([^<]+)/i", $response, $matches);

//     if ($res) {
//         $data = [];
//         foreach ($matches[2] as $name) {
//             $data[] = trim($name);
//         }
//         return $data;
//     } else {
//         return "CEO information not found in search results.";
//     }
// }

// // Example usage
// $link = 'https://www.tripadvisor.com/';
// $parsed_url = parse_url($link);
// $domain = $parsed_url['host'];
// $businessName = $domain;
// $query = "CEO of " . $businessName;
// $googleSearchCEO = googleSearchCEO($query);

// print_r($googleSearchCEO);

function checkBlacklistedLink($link)
{
    global $conn;
   echo $sql = "SELECT id FROM blacklisted WHERE link='$link' OR link LIKE '%$link%'"; 
   $query = mysqli_query($conn,$sql);  
   $row = mysqli_fetch_assoc($query);
   if($row['id'])
   {
       echo "Yes";    
   }else{
       echo "No";    
   }
       
}

$link = "https://www.yelp.com/search%3Fcflt%3Dweb_design%26find_loc%3DLong%2BBeach%252C%2BCA&ved=2ahUKEwjJ5fr-7LKBAxUrbmwGHd3KAOYQFnoECAAQAg&usg=AOvVaw2uAvn1PlpvR3Z8vm5HYclq";
 
$parsed_url = parse_url($link);  
$host = @$parsed_url['host'];
$host = str_replace('www.','',$host); 
checkBlacklistedLink($host); 
?>



