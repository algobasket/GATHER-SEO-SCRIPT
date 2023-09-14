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

//error_reporting(0);   
set_time_limit(0);
require 'vendor/autoload.php';

use React\EventLoop\Factory;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use React\Socket\Connector;
use React\Http\Browser;  
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
    $arrayHref = $dom->getElementsByTagName('a');   

    foreach ($arrayHref as $a) {   
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

                    // H1 Length

                    $h1Length = getHTagsLength($getCurlPage2,1);


                    // Load H1,H2.. Counts  
                    $h1 = getHTags($getCurlPage2,1);

                    if(is_array($h1))
                    {
                        $h1Count = count($h1);  
                    }else{
                        $h1Count = 0 ;
                    }


                    $h2 = getHTags($getCurlPage2,2); 

                    if(is_array($h2))
                    {
                        $h2Count = count($h2);  
                    }else{
                        $h2Count = 0 ;  
                    }

                    $h3 = getHTags($getCurlPage2,3); 

                    if(is_array($h3))
                    {
                        $h3Count = count($h3);  
                    }else{
                        $h3Count = 0 ;  
                    }

                    $h4 = getHTags($getCurlPage2,4); 

                    if(is_array($h4))
                    {
                        $h4Count = count($h4);  
                    }else{
                        $h4Count = 0 ;  
                    }

                    $h5 = getHTags($getCurlPage2,5); 

                    if(is_array($h5))
                    {
                        $h5Count = count($h5);  
                    }else{
                        $h5Count = 0 ;  
                    }


                    $h6 = getHTags($getCurlPage2,6); 

                    if(is_array($h6))
                    {
                        $h6Count = count($h6);  
                    }else{
                        $h6Count = 0 ;   
                    }  
                    

                    
                   // Count the number of <img> elements in the HTML
                    $image_count = countImages($getCurlPage2);

                    if ($image_count > 0) {
                        $imageCount = $image_count;
                    } else {
                        $imageCount = 0;
                    }


                    $imgWithAlt = findImagesWithAlt($getCurlPage2);
                    foreach($imgWithAlt as $imgalt){
                       $imgAltLink[] =  $imgalt[0] . '['.$imgalt[1].']'; 
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
                      
                    $phoneNumbers = getPhoneNumbers($getCurlPage2);  

                    $h1InTitle = h1InTitle($getCurlPage2);    
                

                    $pageSize = $getCurlPage1['size_download'];  

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
                    

                    $links[] = [   
                     'url'   => $href, 
                     'depth' => calculateUrlDepth($href),  
                     'score' => $getCurlPage1['connect_time'], 
                     'gAnalyticStatus' => $googleAnalyticsInfo['gAnalyticsFound'],
                     'gAnalyticMatch'  => $googleAnalyticsInfo['gAnalyticsMatched'],  
                     'response_time'   => $getCurlPage1['total_time'],  
                     //'load_time'     => $getCurlPage1['total_time'], 
                     'load_time'       => $load_time,
                     'ssl' => $ssl ? "Yes" : "No",   
                     'rankKeyword'     => "",   
                     'metaDescription' => $getMetaDescription,  
                     'metaDescriptionLength' => strlen($getMetaDescription),  
                     'canonicalLink' => $canonicalLink,   
                     'status' => $siteStatus,   
                     'statusCode' => $getCurlPage1['http_code'],   
                     'emails' => '',   
                     'phones' => implode(',',$phoneNumbers),    
                     'socials' => $socialLinks,        
                     'contactForms' => $contactForm ? "Yes"  : "No",      
                     'title' => $siteTitle,       
                     'titleLength' => strlen(getSiteTitle($getCurlPage2)),         
                     'wordCounts' => siteWordCount($getCurlPage2),
                     'h1InTitle' => $h1InTitle ? "Yes" : "No",      
                     'h1' => ($h1 == 0) ? 0 : implode('<br>',$h1),
                     'h2' => ($h2 == 0) ? 0 : implode('<br>',$h2),            
                     'h1Length' => $h1Length,       
                     //'h2Length' => $h2length,           
                     'h1Count' => $h1Count,          
                     'h2Count' => $h2Count,          
                     'h3Count' => $h3Count,          
                     'h4Count' => $h4Count,           
                     'h5Count' => $h5Count,          
                     'h6Count' => $h6Count,                
                     'imageCount' => $imageCount,     
                     'imageWithAlt' => implode(',',$imgAltLink),      
                     'headingCount' => $headingCount,      
                     'paragraphCount' => $paragraphCount,     
                     'faviconStatus' => $favicon ? "Yes" : "No",        
                     'contentType' => $getCurlPage1['content_type'],      
                     'indexability' => $indexability['status'],      
                     'indexabilityStatus' => $indexability['content'],        
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
                     '5words' =>$fiveWords                 
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

