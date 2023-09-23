<?php
/*
  **************** Scrapper For Company Information ***********
  *********************** Coded By Algobasket *********************
  ****************** Contact : algobasket@gmail.com ***************
  *********************** Github : algobasket ********************* 
  1. Company Name
  2. Address
  3. Phone #
  4. E-mail Address
  5. Website URL    
*/ 
 require 'config.php';  


if(isset($_REQUEST['isEmailValidAjax']))  
{ 

    $email = urlencode($_POST['email']); // Replace with the email you want to verify
    $key = 'c2GhwlMQ5COEZLFXhyDq2Wml10dQDlTh'; // Replace with your Reoon API key
    $mode = 'power';

    $url = "https://emailverifier.reoon.com/api/v1/verify?email=$email&key=$key&mode=$mode";

    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        // Handle the response from Reoon
        $array =  json_decode($response);
    }
     curl_close($ch);

     $is_deliverable  = $array->is_deliverable;    
     $is_disposable   = $array->is_disposable; 
     $is_safe_to_send = $array->is_safe_to_send;  
     $status          = $array->status;   
     if($status == "safe")
     {
         echo '<button class="btn btn-success btn-sm" style="position:absolute"><b>VALID & SAFE TO SEND <i class="bi bi-check-circle-fill"></i></b></button>'; 
     }else{
         echo '<button class="btn btn-warning btn-sm">'.ucfirst($status).'</button>';     
     }   
       // All Status: "safe", "invalid", "disabled", "disposable", "inbox_full", "catch_all", "role_account", "spamtrap", "unknown"
} 




if(isset($_POST['emailSentAjax']))
{ 
    $email_to = $_POST['email_to'];  
    $name     = $_POST['name'];  
    $subject  = $_POST['subject'];   
    $template = $_POST['template'];      
    $website  = $_POST['website'];      

    $queue_instant = $_POST['queue_instant']; 
    $date = date('d-m-Y');

    $out = getEmailSentCount($email_to,$website);

    $getNextDate = getNextEmailDate($email_to,$website); 

    if($out == 0)
    {
       $next_date = date('d-m-Y', strtotime($date . ' +7 days'));
    }
    if($out == 1)
    {
       $next_date = date('d-m-Y', strtotime($date . ' +14 days'));
    } 
    if($out == 2)
    {
       $next_date = date('d-m-Y', strtotime($date . ' +30 days'));
    }
    if($out == 3)
    {
       $next_date = 0;
    }         


     if($out <= 3)
    {     
         if($queue_instant == "instant")      
        {  
             triggerEmailApi($email_to,$name,$subject,$template);
            $is_sent   = 1;  
            $sent_date = date('d-m-Y'); 
            $response  = "<span class='text-success'>Email Sent</span>";     
        }       

        if($queue_instant == "queue") 
        {  
            $is_sent   = 0;   
            $sent_date = "";        
            $response  = "<span class='text-success'>Email Queued</span>";             
        } 
         $sql = "INSERT INTO emails SET email_to='$email_to',email_data='$template',is_sent='$is_sent',owner = '$name',link='$website',subject='$subject'".      
                ",queue_instant='$queue_instant',next_date='$next_date',sent_date='$sent_date',created_at='$date',updated_at='$date',status=0";     

         mysqli_query($conn,$sql);          
         
    }else{
        $response  = "<span class='text-danger'>Already Sent 4 times</span>";   
    }    
    echo $response; 
                              
}


if(isset($_POST['emailPermutationAjax']))
{ 
    $name = $_POST['fullName'];
    
}      


?>