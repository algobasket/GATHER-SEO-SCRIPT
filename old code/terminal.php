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

error_reporting(0);  
set_time_limit(0);  
require 'scrapper.php';    


    $link =  "https://www.dunhamlaw.com/"; 
    
    $start_time = microtime(true);
    $html = getCurlPageInfo($link);
    $end_time = microtime(true);
    $load_time = $end_time - $start_time;      
    $dom = new DOMDocument;
    @$dom->loadHTML($html['data']);  
    $links = array(); 
     $counter = 1;   
    foreach ($dom->getElementsByTagName('a') as $a) {  
            $href = $a->getAttribute('href'); 
           
            if (!empty($href) && (strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0))
            {  
                   $getCurlPageInfo = getCurlPageInfo($href); 
                   $getCurlPage1 = $getCurlPageInfo['info'];
                   $getCurlPage2 = $getCurlPageInfo['data'];    

                   $googleAnalyticsInfo = googleAnalyticsInfo($getCurlPage2); 

    
                    $ssl = hasSSL($href);            


                    if (preg_match('/<h1[^>]*>.*?<\/h1>/i', $getCurlPage2)) {
                        $h1InTitle = 1;
                    } else {
                        $h1InTitle = 0;;
                    } 


        
                    $getMetaDescription = getMetaDescription($getCurlPage2);

                    $canonicalLink = getCanonicalURL($getCurlPage2); 

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

    

                    // Load h1 & h2 content  
                    $h1 = getHTags($getCurlPage2,1);

                    if(is_array($h1))
                    {
                        $h1length = count($h1);  
                    }else{
                        $h1length = 0 ;
                    }


                    $h2 = getHTags($getCurlPage2,2); 

                    if(is_array($h2))
                    {
                        $h2length = count($h2);  
                    }else{
                        $h2length = 0 ;  
                    }
                    

                    
                   // Count the number of <img> elements in the HTML
                    $image_count = countImages($getCurlPage2);

                    if ($image_count > 0) {
                        $imageCount = $image_count;
                    } else {
                        $imageCount = 0;
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

                    $phoneNumbers = getPhoneNumbers($getCurlPage2);

                    $h1InTitle = h1InTitle($getCurlPage2);  

                $links[] = [   
                 'url'   => $href,
                 'depth' => calculateUrlDepth($href),
                 'score' => $getCurlPage1['connect_time'], 
                 'gAnalyticStatus' => $googleAnalyticsInfo['gAnalyticsFound'],
                 'gAnalyticMatch'  => $googleAnalyticsInfo['gAnalyticsMatched'],
                 'response_time'   => $getCurlPage1['total_time'],
                 'ssl' => $ssl,  
                 'rankKeyword' => "",  
                 'metaDescription' => $getMetaDescription,  
                 'metaDescriptionLength' => strlen($getMetaDescription),  
                 'canonicalLink' => $canonicalLink,   
                 'status' => $siteStatus,   
                 'statusCode' => $getCurlPage1['http_code'],   
                 'emails' => '',   
                 'phones' => implode('<br>',$phoneNumbers),   
                 'socials' => implode('<br>',$socialLinks),    
                 'contactForms' => hasContactForm($getCurlPage2),     
                 'title' => getSiteTitle($getCurlPage2),      
                 'titleLength' => strlen(getSiteTitle($getCurlPage2)),       
                 'wordCounts' => siteWordCount($getCurlPage2),
                 'h1InTitle' => $h1InTitle,      
                 'h1' => ($h1 == 0) ? 0 : implode('<br>',$h1),         
                 'h1Length' => $h1length,  
                 'h2' => ($h2 == 0) ? 0 : implode('<br>',$h2),            
                 'h2Length' => $h2length,        
                 'imageCount' => $imageCount,     
                 'headingCount' => $headingCount,      
                 'paragraphCount' => $paragraphCount,     
                 'faviconStatus' => $favicon,     
                 'contentType' => $getCurlPage1['content_type'],      
                 'indexability' => $indexability['status'],      
                 'indexabilityStatus' => $indexability['content'],        
                 'metaKeyword' => $metaKeywords,     
                 'metaKeywordLength' => strlen($metaKeywords),      
                 'xRobotTag' => $xRobotTag      
                ];
                if($counter == 10)
                {
                    //break;   
                }  
                $counter++;   
                   //echo '<pre>';print_r($links);echo '</pre>';        
                   //print_r($getCurlPageInfo['info']);          
                   //exit;           
            }   
    
    
    } 

?>   

