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

 //--------------- Sending Emails ------------------

    $getQueuedEmails = getQueuedEmails(0);                  
   
    if(is_array($getQueuedEmails))          
    { 
         foreach($getQueuedEmails as $r)
         {
            $email     = $r['email_to']; 
            $name      = $r['owner']; 
            $subject   = $r['subject']; 
            $template  = $r['email_data']; 
            $link      = $r['link'];     
            
            $next_date = $r['next_date'];                               
            $current   = date('d-m-Y');                          

            if(strtotime($next_date) <= strtotime($current))   
            {             
                  $response = triggerEmailApi($email,$name,$subject,$template);

                  if($response == true)             
                  {   
                       updateQueueEmails($email,$link,$status = 1,$isSent = 1);  
                  }         
            }      
         }
    }else{ 
       echo "No Email To Send !!";
    }
    
?>