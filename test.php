<?php
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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;  
require 'vendor/autoload.php'; // Adjust the path if needed


    // Create a new PHPMailer instance
    $mail = new PHPMailer(true); 

    try { 
        // SMTP server settings 
        $mail->isSMTP(); 
        $mail->Host       = "server.hireageekmail.com"; // Your SMTP server
        $mail->SMTPAuth   = true;       
        $mail->Username   = "admin"; 
        $mail->Password   = "o3s3E8ReItpk50W6qtUsNk49uit3y5";    
        $mail->SMTPSecure = ''; // Enable TLS encryption, or use 'ssl' if needed
        $mail->Port       = 2525; // SMTP port (587 for TLS, 465 for SSL)

        // Sender and recipient
        $mail->setFrom("ken@hireageekmail.com", "hireageekmail"); 
        $mail->addAddress("algobasket@gmail.com", "Algobasket");

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "test";
        $mail->Body    = "test";

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        //return false;
    }
?>



