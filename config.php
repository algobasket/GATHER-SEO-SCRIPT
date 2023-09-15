<?php 
/*
  ************************ SCRAPPING SCRIPT **********************
  *********************** Coded By Algobasket *********************
  ****************** Contact : algobasket@gmail.com ***************
  *********************** Github : algobasket *********************    
*/
ob_start();
session_start();
//error_reporting(0); 
set_time_limit(0); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;  

// Database Config   
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "hireageek";    

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


// ********************* DB QUERY ********************* 

function queueList() 
{
  global $conn;
  $sql = "SELECT * FROM queue";
  $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;       
} 

function getQueueInfoById($id)   
{
  global $conn;
  $sql = "SELECT * FROM queue WHERE id = '$id'";
  $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data = $row;

    return $data;        
} 


function deleteQueue($id)
{
   global $conn;
   $sql = "DELETE FROM queue WHERE id='$id'";
   $query = mysqli_query($conn,$sql);
   return true;    
} 

function addnewApi($apiType,$apiVendor,$apiKey,$clientId,$clientSecret,$redirectUrl)
{
   global $conn;
   $sql = "INSERT INTO settings SET setting_name='apis',setting_type='$apiType',api_vendor='$apiVendor',api_key='$apiKey',client_id='$clientId',client_secret='$clientSecret',redirect_url='$redirectUrl'";  
   $query = mysqli_query($conn,$sql);
   return true;   
}

function getApis($type)   
{
   global $conn;
   if($type)
   {
     $sql = "SELECT * FROM settings WHERE setting_name = 'apis' AND setting_type='$type'"; 
   }else{
     $sql = "SELECT * FROM settings WHERE setting_name = 'apis'";  
   }
     
  $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;    
}

function deleteSetting($id)
{
   global $conn;
   $sql = "DELETE FROM settings WHERE id='$id'";
   $query = mysqli_query($conn,$sql);
   return true;   
} 



function getSmtps()
{
   global $conn;
  $sql = "SELECT * FROM settings WHERE setting_name = 'smtp'";
  $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;   
}

function addnewSmtp($name,$server,$username,$pass,$email,$port) 
{
   global $conn;
   $sql = "INSERT INTO settings SET setting_name='smtp',smtp_name='$name',smtp_server='$server',smtp_username='$username',password='$pass',smtp_email='$email',port_no='$port'"; 
   $query = mysqli_query($conn,$sql); 
   return true;   
}

function getEmailTemplates()
{
   global $conn;
  $sql = "SELECT * FROM settings WHERE setting_name = 'email-templates'";
  $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;   
}

function addnewTemplate($subject,$email_template)    
{
   global $conn;
   $sql = "INSERT INTO settings SET setting_name='email-templates',subject='$subject',email_template='$email_template'"; 
   $query = mysqli_query($conn,$sql);
   return true;  
}

 
function createSpreadSheetsFields($links,$spreadSheetName) 
{
    global $conn;
    $folderId = '1mdsXQ79d44bWoAss-26DCfBHC4fufVu3'; // Replace with the actual folder ID
    $fields = 'URL,Depth,Score,GAnalyticStatus,GAnalyticMatch,ResponseTime,LoadTime,PageSize,SSL,H1inTitle,MetaDescription,'.  
              'MetaDescriptionLength,Status,StatusCode,Email,EmailPrivacy,Phones,SocialLinks,ContactForms,Title,WordCounts,2Words,'. 
              '3Words,4Words,5Words,LastModified,H1,H1Length,H1Count,H2Count,H3Count,H4Count,H5Count,H6Count,HeadingCount,ImageCount,'. 
              'ImageAltTags,OutboundLinks,TotalOutboundLinks,FaviconStatus,Indexability,IndexabilityStatus,XMLSitemap,Robots.txt';               
             
 
    $webApp = 'https://script.google.com/macros/s/AKfycbzK0KQAwtbV-nxSueJFC_CB-aarX_rCGEKXi8mgrBW7m5utFqTdvtWtp0NHRA_bMlGy/exec';                  
    $script_url = $webApp . '?folderId=' . $folderId . '&spreadsheetName=' . $spreadSheetName .'&headers=' . trim($fields);                        

    $response = getCurlContent($script_url);         

    if ($response === false) {
        return 0;
    } else { 
       // Extract the spreadsheet ID from the response
       $spreadsheetId = trim(substr($response, strpos($response, 'Spreadsheet ID:') + 15)); 
       $created_at = date('d M,Y');  
       $updated_at = date('d M,Y');  
       $sql = "INSERT INTO spreadsheets SET links='$links',spreadsheet_code='$spreadsheetId',created_at='$created_at',updated_at='$created_at',status=0"; 
       $query = mysqli_query($conn,$sql);
       return $spreadsheetId;   
    }
}   

function insertSpreadSheetsRecords($links,$spreadsheetId,$data,$queueId)
{
     global $conn; 
        //$spreadsheetId = "YOUR_SPREADSHEET_ID"; // Replace with your spreadsheet ID 

        $array = array(
                'queue_id' => $queueId,
                'link'  => $data['link'],
                'url'   => $data['url'],
                'depth' => $data['depth'],
                'score' => $data['score'],
                'ganalytics_status' => $data['gAnalyticStatus'],
                'ganalytics_match'  => $data['gAnalyticMatch'],
                'response_time' => $data['response_time'],
                'load_time'     => $data['load_time'],
                'ssl_status'    => $data['ssl'],
                'meta_description' => $data['metaDescription'],
                'meta_description_l' => $data['metaDescriptionLength'],
                'status_word' => $data['status'],
                'status_code' => $data['statusCode'],
                'emails'  => $data['emails'],
                'phones'  => $data['phones'],
                'socials' => $data['socials'],
                'contact_forms' => $data['contactForms'],
                'title'   => $data['title'],
                'title_l' => $data['titleLength'], 
                'word_counts' => $data['wordCounts'],
                'h1_in_title' => $data['h1InTitle'],
                'h1'   => $data['h1'],
                'h1_l' => $data['h1Length'], 
                'h1_c' => $data['h1Count'],
                'h2_c' => $data['h2Count'],
                'h3_c' => $data['h3Count'],
                'h4_c' => $data['h4Count'],
                'h5_c' => $data['h5Count'], 
                'h6_c' => $data['h6Count'],
                'heading_c' => $data['headingCount'],
                'img_c' => $data['imageCount'],
                'img_alt_tags'   => $data['imageWithAlt'],
                'outbound_links' => $data['outBoundLinks'],
                'total_outbound_links' => $data['outBoundLinksCount'],
                'favicon_status' => $data['faviconStatus'],
                'indexability'   => $data['indexability'],
                'indexability_status' => $data['indexabilityStatus'],
                'xml_sitemap' => $data['sitemapUrl'],
                'robots_txt'  => $data['robotsTxt'],
                'page_size'   => $data['pageSize'],
                'email_privacy' => $data['email_privacy'],
                'two_words'   => $data['2words'],
                'three_words' => $data['3words'],
                'four_words' => $data['4words'],
                'five_words' => $data['5words'],
                'last_modified' => $data['last_modified'],
                'status' => 0 
            );
         //print_r($data);    
        $spreadData = [$data['url'],$data['depth'],$data['score'],$data['gAnalyticStatus'],$data['gAnalyticMatch'],$data['response_time'],$data['load_time'],$data['pageSize'],$data['ssl'],$data['h1InTitle'],$data['metaDescription'],$data['metaDescriptionLength'],$data['status'],$data['statusCode'],$data['emails'],$data['email_privacy'],$data['phones'],$data['socials'],$data['contactForms'],$data['title'],$data['wordCounts'],$data['2words'],$data['3words'],$data['4words'],$data['5words'],$data['last_modified'],$data['h1'],$data['h1Length'],$data['h1Count'],$data['h2Count'],$data['h3Count'],$data['h4Count'],$data['h5Count'],$data['h6Count'],$data['headingCount'],$data['imageCount'],$data['imageWithAlt'],$data['outBoundLinks'],$data['outBoundLinksCount'],$data['faviconStatus'],$data['indexability'],$data['indexabilityStatus'],$data['sitemapUrl'],$data['robotsTxt']];       
           //print_r($spreadData);exit;     
        $base_url = "https://script.google.com/macros/s/AKfycbwTG9nfThBGrZLcGeZ9YWEQ5iRt6HGVrhb3yqvvSSshPj6Kwun_hmlZYlGZmgXgFaVS/exec";    
        $params = [
            'spreadsheetId' => $spreadsheetId,
            'data' => json_encode($spreadData), // Convert data to JSON
        ];   

        // Send a POST request to your Google Apps Script web app
        $ch = curl_init($base_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response from the Google Apps Script
        if ($response === false) {
            return false;
        } else {     
            echo "New Row Added";
            

            // Generate the SQL INSERT statement
            $fields = implode(', ', array_keys($array));
            $values = "'" . implode("', '", $array) . "'";
            $sql = "INSERT INTO scrap_data ($fields) VALUES ($values)";
            $query = mysqli_query($conn,$sql);
            return true;   
        }
}

function getOwnerName($link)
{
   global $conn;
    $sql = "SELECT * FROM scrap_data WHERE link = '$link' OR link LIKE '%$link%'";
    $query = mysqli_query($conn,$sql); 
    $data = mysqli_fetch_assoc($query);
    return isset($data['email_privacy']) ? $data['email_privacy'] : "";
}


function getRealEmail($link)
{
   global $conn;
    $sql = "SELECT * FROM scrap_data WHERE link = '$link' OR link LIKE '%$link%'";
    $query = mysqli_query($conn,$sql); 
    $data = mysqli_fetch_assoc($query);
    return isset($data['email_privacy']) ? $data['email_privacy'] : "";
}


function getEmailQueueData($link)
{
    global $conn;
    $sql = "SELECT * FROM spreadsheets WHERE links = '$link' OR links LIKE '%$link%'";
    $query = mysqli_query($conn,$sql); 
    $data = mysqli_fetch_assoc($query); 
    
    $spreadsheetId =  isset($data['spreadsheet_code']) ? $data['spreadsheet_code'] : "";   
    $email = getRealEmail($link);     
    
    $emailTemplates = getEmailTemplates();
    foreach($emailTemplates as $emailTemp){};    
    $emailTemplates = $emailTemp['email_template'];
    $subject = $emailTemp['subject'];

    $parsed_url = parse_url($link); 
    $domain = $parsed_url['host'];  
    $spreadsheetHref = '<a href="https://docs.google.com/spreadsheets/d/'.
                       $spreadsheetId.'" target="__blank">https://docs.google.com/spreadsheets/d/'.$spreadsheetId.'</a>';
    $subject = str_replace('{domain}',$domain,$subject);       
    $emailTemplate = str_replace(['{domain}','{spreadsheetLink}'],[$domain,$spreadsheetHref],$emailTemplates);

    return [ 
        'email'   => $email,  
        'domain'  => $domain, 
        'subject' => $subject, 
        'emailTemplate' => $emailTemplate, 
    ];       
} 

function getQueuedEmails($status)
{
    global $conn;
    $sql = "SELECT * FROM emails WHERE queue_instant = 'queue' OR status = '$status'";
    $query = mysqli_query($conn,$sql); 
    while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;  
}

function triggerEmailApi($to,$toName,$subject,$body) 
{
   

   require 'vendor/autoload.php'; // Adjust the path if needed

    global $conn; 

    $sql = "SELECT * FROM settings WHERE setting_name = 'smtp' AND status = 1"; 
    $query = mysqli_query($conn,$sql); 
    $data = mysqli_fetch_assoc($query);

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true); 

    try { 
        // SMTP server settings 
        $mail->isSMTP(); 
        $mail->Host       = $data['smtp_server']; // Your SMTP server
        $mail->SMTPAuth   = true; 
        $mail->Username   = $data['smtp_username'];
        $mail->Password   = $data['password'];     
        $mail->SMTPSecure = ''; // Enable TLS encryption, or use 'ssl' if needed
        $mail->Port       = $data['port_no']; // SMTP port (587 for TLS, 465 for SSL)

        // Sender and recipient
        $mail->setFrom($data['smtp_email'], $data['smtp_name']); 
        $mail->addAddress($to, $toName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        //return false;
    }
}


?> 