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
 require 'scrapper.php';  

 //--------------- Auto Scrap Emails and Owners ------------------

    $getScrappedLinks = getScrappedLinks(0);                           
    //print_r($getScrappedLinks);exit; 
    if(is_array($getScrappedLinks))          
    { 
         foreach($getScrappedLinks as $r) 
         {
            $link = $r['link']; 
            $name = getBusinessNameFromLink($link);  
            
            
            //Get From Google 
            $query = "Contact Email of ".$name . " + CEO Email of ".$name;
            $emails = googleSearchEmail($query);

            $query2 = "Owner of ".$name. " + CEO of ".$name;
            $owner = googleSearchOwnersAndCEOs($query2);     

            
            echo '<pre>'.$name.' Info - <br>';
              //print_r($emails);echo '<br>';  
              print_r($owner);echo '<br>';         
            echo '</pre>';
            exit;  
         }
    }else{ 
       echo "No Email Found !!";  
    } 

            // $query = "CEO of Google";  
            // $url = "https://www.google.com/search?q=" . urlencode($query);
            // //$url = "http://spiceexportersdirectory.com/"; 
            // $result = getCurlContent($url);
            // print_r($result);   
    
?>