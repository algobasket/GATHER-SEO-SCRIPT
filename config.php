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

$AppScriptField  = "https://script.google.com/macros/s/AKfycbyfKaybGhHzjogxpFTGrLTkJVAE2SHFwMWnhWd7sPn1Nq8DqeA52Ev7wVcGhgFNDW3_Eg/exec";
$AppScriptRecord = "https://script.google.com/macros/s/AKfycbx9gqdkPZZAoU4GgBMnDLxeP7Ks-5zTKbc4HOm8JKMoY0lAWkdEvMOa94wI08H62oupBw/exec";

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



function queueListByStatus($status)  
{
  global $conn;
  $sql = "SELECT * FROM queue WHERE status = '$status'";
  $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;       
}




function getTotalDataScrappedByQueueId()
{


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




function getBlacklistLink()
{
    global $conn;
   $sql = "SELECT * FROM blacklisted"; 
   $query = mysqli_query($conn,$sql); 
  while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data; 
}




function checkBlacklistedLink($link)
{
    global $conn;
   $sql = "SELECT * FROM blacklisted WHERE link='$link' OR link LIKE '%$link%'"; 
   $query = mysqli_query($conn,$sql);  
   $row = mysqli_fetch_assoc($query);
   if(isset($row))
   {
       return true;  
   }
       
}




function blacklistLink($q,$url) 
{
    global $conn;
    $date = date('d-m-Y');
    if(isset($q)) 
    {
       $sql = "INSERT INTO blacklisted SET queue_id='$q',link='$url',created_at='$date',updated_at='$date',status=1";  
    }else{

       $sql = "INSERT INTO blacklisted SET queue_id=0,link='$url',created_at='$date',updated_at='$date',status=1";  
    }  
   
   $query = mysqli_query($conn,$sql); 
   return true; 
}





function whitelistLink($q,$link)
{
    global $conn;   
   $sql = "DELETE FROM blacklisted WHERE link='$link' OR link LIKE '%$link%'"; 
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





function addnewTemplate($subject,$email_template,$numbering)     
{
   global $conn;
   $sql = "INSERT INTO settings SET setting_name='email-templates',subject='$subject',email_template='$email_template',numbering='$numbering'";  
   $query = mysqli_query($conn,$sql);
   return true;   
}




 
function createSpreadSheetsFields($links,$spreadSheetName) 
{
    global $conn;
    global $AppScriptField;

    $folderId = '1mdsXQ79d44bWoAss-26DCfBHC4fufVu3'; // Replace with the actual folder ID
    $fields = 'URL,Depth,Score,GAnalyticStatus,GAnalyticMatch,ResponseTime,LoadTime,PageSize,SSL,H1inTitle,MetaDescription,'.  
              'MetaDescriptionLength,Status,StatusCode,Email,EmailPrivacy,Phones,SocialLinks,ContactForms,Title,WordCounts,2Words,'. 
              '3Words,4Words,5Words,LastModified,H1,H1Length,H1Count,H2Count,H3Count,H4Count,H5Count,H6Count,HeadingCount,Headings,ImageCount,'. 
              'ImageAltTags,OutboundLinks,TotalOutboundLinks,FaviconStatus,Indexability,IndexabilityStatus,XMLSitemap,Robots.txt';                
             
 
    $webApp = $AppScriptField; 

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





function insertSpreadSheetsRecords($links,$spreadsheetId,$data,$queueId,$saveToSpreadSheet,$saveToDB,$saveToBothSpreadAndDB) 
{
        global $conn;   
        global $AppScriptRecord;    
        
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
                'headings' => $data['headings'],
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


        $spreadData = [$data['url'],$data['depth'],$data['score'],$data['gAnalyticStatus'],$data['gAnalyticMatch'],$data['response_time'],$data['load_time'],$data['pageSize'],$data['ssl'],$data['h1InTitle'],$data['metaDescription'],$data['metaDescriptionLength'],$data['status'],$data['statusCode'],$data['emails'],$data['email_privacy'],$data['phones'],$data['socials'],$data['contactForms'],$data['title'],$data['wordCounts'],$data['2words'],$data['3words'],$data['4words'],$data['5words'],$data['last_modified'],$data['h1'],$data['h1Length'],$data['h1Count'],$data['h2Count'],$data['h3Count'],$data['h4Count'],$data['h5Count'],$data['h6Count'],$data['headingCount'],$data['headings'],$data['imageCount'],$data['imageWithAlt'],$data['outBoundLinks'],$data['outBoundLinksCount'],$data['faviconStatus'],$data['indexability'],$data['indexabilityStatus'],$data['sitemapUrl'],$data['robotsTxt']];       
           //print_r($spreadData);exit;    




        if($saveToBothSpreadAndDB == 1)  
        {   
            
            if(($spreadsheetId != 0) OR ($spreadsheetId != "") OR (($spreadsheetId != NULL)))
            {
                    $base_url = $AppScriptRecord;      
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
                       
                         // Generate the SQL INSERT statement
                            $fields = implode(', ', array_keys($array));
                            $values = "'" . implode("', '", $array) . "'";
                            $sql = "INSERT INTO scrap_data ($fields) VALUES ($values)";
                            $query = mysqli_query($conn,$sql);
                            return true;     
                    }
            }
               
       } //End-saveToBothSpreadAndDB 



         if($saveToDB == 1)
        {
            // Generate the SQL INSERT statement
            $fields = implode(', ', array_keys($array));
            $values = "'" . implode("', '", $array) . "'";
            $sql = "INSERT INTO scrap_data ($fields) VALUES ($values)";
            $query = mysqli_query($conn,$sql);
            return true;   
        }


         if($saveToSpreadSheet == 1) 
        { 
            if(($spreadsheetId != 0) OR ($spreadsheetId != "") OR (($spreadsheetId != NULL)))
            {
                    $base_url = $AppScriptRecord;     
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
                       return true;
                    } 
             }         
        }       
}    




function getAuditData($link,$queueId)
{ 
    global $conn;
    $data = [];  

    if($queueId)   
    {
        $sql = "SELECT * FROM scrap_data WHERE queue_id = '$queueId' AND  (link = '$link' OR link LIKE '%$link%')";
    }else{
        $sql = "SELECT * FROM scrap_data WHERE link = '$link' OR link LIKE '%$link%'";
    }
     
    $query = mysqli_query($conn,$sql); 
    while($row = mysqli_fetch_assoc($query))
        $data[] = $row;  

    return $data;     
} 



function getOwnerName($link)
{
   global $conn;
    $sql = "SELECT * FROM scrap_data WHERE link = '$link' OR link LIKE '%$link%'";
    $query = mysqli_query($conn,$sql); 
    $data = mysqli_fetch_assoc($query); 
    return isset($data['owner_ceo_name']) ? $data['owner_ceo_name'] : "";
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

    $owner_ceo_name = getOwnerName($link);
    
    $emailTemplates = getEmailTemplates();
    foreach($emailTemplates as $emailTemp){};    
    $emailTemplates = $emailTemp['email_template'];
    $subject = $emailTemp['subject'];

    $parsed_url = parse_url($link);  
    $domain = $parsed_url['host'];  


    $spreadsheetHref = 'https://docs.google.com/spreadsheets/d/'.$spreadsheetId;    
    $subject = str_replace('{domain}',$domain,$subject);       
    $emailTemplate = str_replace(['{domain}','{spreadsheetLink}'],[$domain,$spreadsheetHref],$emailTemplates);

    return [ 
        'email'   => $email,  
        'domain'  => $domain, 
        'subject' => $subject,
        'link' => $link,       
        'owner_ceo_name' => $owner_ceo_name,  
        'emailTemplate' => $emailTemplate, 
    ];       
} 





function getQueuedEmails($status)
{
    global $conn;
    $sql = "SELECT * FROM emails WHERE queue_instant = 'queue' AND status = '$status'";
    $query = mysqli_query($conn,$sql); 
    while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;   
}


function getQueuedEmailsForCron($status)      
{
    global $conn;
    $sql = "SELECT * FROM emails WHERE next_date AND status = 1";
    $query = mysqli_query($conn,$sql); 
    while($row = mysqli_fetch_assoc($query))
        $data[] = $row;

    return $data;   
}





function updateQueueEmails($email,$link,$status,$isSent)   
{
    $sql = "UPDATE emails SET email_to = '$email',link='$link',status = '$status',is_sent = '$isSent'";
    $query = mysqli_query($conn,$sql); 
}



function getEmailSent($isSent,$queueInstant)  
{
   global $conn;   

    if($isSent && $queueInstant)
    {
        $sql = "SELECT * FROM emails WHERE is_sent = '$isSent' AND queue_instant = '$queueInstant'";
    }else{
        $sql = "SELECT * FROM emails";
    }   
    //echo $sql;
    $query = mysqli_query($conn,$sql); 
    while($row = mysqli_fetch_assoc($query)) 
        $data[] = $row;

    return $data;   
}  



function getEmailSentCount($email,$link)     
{     
    global $conn;      

    $sql = "SELECT * FROM emails WHERE email_to = '$email' AND link = '$link'";    
    $query = mysqli_query($conn,$sql); 
    $row = mysqli_num_rows($query); 
    return $row;    
}  



function getNextEmailDate($email,$link)       
{      
    global $conn;      

    $sql = "SELECT next_date FROM emails WHERE email_to = '$email' AND link = '$link' AND status = 0 ORDER BY id DESC";          
    $query = mysqli_query($conn,$sql); 
    $row = mysqli_fetch_assoc($query); 
    return $row['next_date'];         
}


function replyStatus($isReply,$email,$link)    
{
    global $conn;

    if($isReply == 1)
    {
      $sql = "UPDATE emails SET status = 1,is_sent = 1 WHERE email_to = '$email' AND link='$link'"; 
    }
    if($isReply == 0) 
    {
      $sql = "UPDATE emails SET status = 0 WHERE email_to = '$email' AND link='$link'";    
    }
    
    $query = mysqli_query($conn,$sql); 
}  



function getScrappedLinks($status)  
{
     global $conn;     
 
    $sql = "SELECT link FROM scrap_data WHERE status = '$status' GROUP BY link ORDER BY id DESC";                        
   
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

function generateEmailPermutations($name, $domain) { 
    $name = strtolower($name);
    $domain = strtolower($domain);

    $permutations = [];

    // Generate common email patterns
    $patterns = [
        '{first}.{last}',
        '{first}{last}',
        '{first}_{last}',
        '{first}',
        '{first}{last}@{domain}',
        '{first}_{last}@{domain}',
        '{first}.{last}@{domain}',
        '{last}.{first}',
        '{last}{first}',
        '{last}_{first}',
        '{last}',
        '{last}.{first}@{domain}',
        '{last}_{first}@{domain}',
        '{last}{first}@{domain}',
        '{first}@{domain}',
        '{last}@{domain}',
        '{first}.{last}{last}',
        '{first}{last}{last}',
        '{first}_{last}{last}',
        '{first}{last}{last}@{domain}',
        '{first}_{last}{last}@{domain}',
        '{first}.{last}{last}@{domain}',
        '{first}.{middle}.{last}',
        '{first}{middle}{last}',
        '{first}_{middle}_{last}',
        '{first}.{middle}',
        '{first}{middle}@{domain}',
        '{first}_{middle}@{domain}',
        '{first}.{middle}@{domain}',
        '{last}.{first}.{middle}',
        '{last}{first}{middle}',
        '{last}_{first}_{middle}',
        '{last}.{middle}',
        '{last}{middle}@{domain}',
        '{last}_{middle}@{domain}',
        '{last}.{middle}@{domain}',
        '{first}.{last}{middle}',
        '{first}{last}{middle}@{domain}',
        '{first}_{last}_{middle}@{domain}',
        '{first}.{last}_{middle}@{domain}',
        '{last}.{first}{middle}',
        '{last}{first}{middle}@{domain}',
        '{last}_{first}_{middle}@{domain}',
        '{last}.{first}_{middle}@{domain}',
        '{first}{last}.{middle}',
        '{first}_{last}.{middle}@{domain}',
        '{first}{last}_{middle}@{domain}',
        '{last}{first}.{middle}',
        '{last}_{first}.{middle}@{domain}',
        '{last}{first}_{middle}@{domain}',
        '{first}.{middle}.{last}@{domain}',
        '{first}_{middle}_{last}@{domain}',
        '{first}.{middle}_{last}@{domain}',
        '{middle}.{first}.{last}@{domain}',
        '{middle}_{first}_{last}@{domain}',
        '{middle}.{first}_{last}@{domain}',
        '{last}.{middle}.{first}@{domain}',
        '{last}_{middle}_{first}@{domain}',
        '{last}.{middle}_{first}@{domain}',
        '{first}.{last}{middle}@{domain}',
        '{first}{last}{middle}@{domain}',
        '{first}_{last}_{middle}@{domain}',
        '{last}.{first}{middle}@{domain}',
        '{last}{first}{middle}@{domain}',
        '{last}_{first}_{middle}@{domain}',
        '{first}{middle}.{last}@{domain}',
        '{first}_{middle}.{last}@{domain}',
        '{first}{middle}_{last}@{domain}',
        '{last}{middle}.{first}@{domain}',
        '{last}_{middle}.{first}@{domain}',
        '{last}{middle}_{first}@{domain}',
        '{middle}{first}.{last}@{domain}',
        '{middle}.{first}_{last}@{domain}',
        '{middle}{last}.{first}@{domain}',
        '{middle}_{last}.{first}@{domain}',
        '{middle}.{last}{first}@{domain}',
        '{middle}_{last}{first}@{domain}',
        '{first}{middle}{last}@{domain}',
        '{first}_{middle}{last}@{domain}',
        '{first}{last}{middle}@{domain}',
        '{first}_{last}{middle}@{domain}',
        '{last}{middle}{first}@{domain}',
        '{last}_{middle}{first}@{domain}',
        '{last}{first}{middle}@{domain}',
        '{last}_{first}{middle}@{domain}',
        '{middle}{first}{last}@{domain}',
        '{middle}_{first}{last}@{domain}',
        '{middle}{last}{first}@{domain}',
        '{middle}_{last}{first}@{domain}',
        '{middle}{first}.{last}@{domain}',
        '{middle}.{first}{last}@{domain}',
        '{middle}{last}.{first}@{domain}',
        '{middle}.{last}{first}@{domain}',
    ];

    foreach ($patterns as $pattern) {
        $permutations[] = str_replace(['{first}', '{last}', '{middle}', '{domain}'], [$name, $name[0], substr($name, 1, 1), $domain], $pattern);
    }

    return $permutations;
}



                       




?> 