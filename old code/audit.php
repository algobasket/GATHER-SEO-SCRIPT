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


if(isset($_GET['link']))    
{   
    $link =  base64_decode($_REQUEST['link']);
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
}   
?>     
 
 
 

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HIRE A GEEK | SEO TOOLKIT </title>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style type="text/css">
        body{
            background-color: #fff;
        }
    </style>
  </head>
  <body>


   <div class="container-fluid">
   
        <br><br><br><br>
        <h1>HIRE A GEEK | SEO SCRIPT</h1>
        <div class="alert alert-dark">   
            <h5>Audit for > <?= substr(base64_decode($_GET['link']),0,50);?> ...</h5>
        </div>
            

        <a href="index.php?module=getPpcAdvertiser" class="btn btn-outline-dark" target="_blank">GET PPC Advertisers</a>
        <a href="index.php?module=websiteLinkAnalysis" class="btn btn-outline-dark" target="_blank">Website Link Analysis</a>
        | <span class="fs-5">Overall Load Time <span class="badge bg-dark "><?= round($load_time,2);?></span></span> 
        <br><br>  

        <?php if(@$_GET["module"] == "websiteLinkAnalysis") : ?> 
        <?php $storeUrl = isset($_POST['link']) ? $_POST['link'] : "";?>       
        <?php endif ?> 

        <?php if(is_array($links)){ ?> 
            <hr><br>
            <center><h5>Found <?= count($links);?> links</h5></center>
            <pre>
            <div class="table-responsive">  
             <table class="table table-bordered border-dark table-sm fst-italic" style="width:1000px;max-height:500px;overflow-x: scroll;overflow-y: scroll;">
                <thead>   
                 <tr class="bg-dark">
                    <th>No</th> 
                    <th>URL</th> 
                    <th>Depth</th>
                    <th>Score</th>
                    <th>GAnalytic Status</th> 
                    <th>GAnalytic Match</th>
                    <th>Response Time</th>
                    <th>SSL</th>     
                    <th>Ranked Keyword</th>
                    <th>H1 in Title</th>
                    <th>Meta Details</th>
                    <th>Meta Details Length</th>
                    <th>Canonical Link</th>
                    <th>Status</th>
                    <th>Status Code</th>
                    <th>Email</th>
                    <th>Phones</th>
                    <th>Socials</th>  
                    <th>Contact Forms</th>
                    <th>Title</th>
                    <th>Word Counts</th>
                    <th>H1</th>
                    <th>H1 Length</th>
                    <th>H2</th>
                    <th>H2 Length</th>
                    <th>Image Count</th>
                    <th>Heading Count</th>
                    <th>Paragraph Count</th>
                    <th>Favicon Status</th> 
                    <th>Content Type</th>
                    <th>Indexability</th>
                    <th>Indexability Status</th>
                    <th>Meta Keywords</th>
                    <th>Meta Keywords Length</th>
                    <th>X-Robots-Tag</th>  
                 </tr>
                 </thead>
                 <?php $i=1;foreach($links as $link) : ?>
                 <tr>
                    <td><?= $i;?></td>
                    <td><?= $link['url'];?></td>  
                    <td><?= $link['depth'];?></td>    
                    <td><?= $link['score'];?></td>
                    <td><?= $link['gAnalyticStatus'];?></td> 
                    <td><?= $link['gAnalyticMatch'];?></td>
                    <td><?= $link['response_time'];?></td> 
                    <td class="<?= addBgColor($link['ssl']);?>"><?= $link['ssl'];?></td>  
                    <td>Ranked Keyword</td>     
                    <td class="<?= addBgColor($link['h1InTitle']);?>"><?= $link['h1InTitle'];?></td>    
                    <td><?= $link['metaDescription'];?></td>
                    <td><?= $link['metaDescriptionLength'];?></td>
                    <td><?= $link['canonicalLink'];?></td>
                    <td class="<?= addBgColor($link['status']);?>"><?= $link['status'];?></td>
                    <td class="<?= addBgColor($link['statusCode']);?>"><?= $link['statusCode'];?></td>
                    <td>Null</td>  
                    <td><?= $link['phones'];?></td> 
                    <td><?= $link['socials'];?></td> 
                    <td class="<?= addBgColor($link['contactForms']);?>"><?= $link['contactForms'];?></td>
                    <td><?= $link['title'];?></td>
                    <td><?= $link['wordCounts'];?></td>
                    <td><?= trim($link['h1']);?></td>
                    <td><?= $link['h1Length'];?></td>
                    <td><?= $link['h2'];?></td>
                    <td><?= $link['h2Length'];?></td>
                    <td><?= $link['imageCount'];?></td>
                    <td><?= $link['headingCount'];?></td>
                    <td><?= $link['paragraphCount'];?></td>
                    <td><?= $link['faviconStatus'];?></td>
                    <td><?= $link['contentType'];?></td> 
                    <td class="<?= addBgColor($link['indexability']);?>"><?= $link['indexability'];?></td> 
                    <td><?= $link['indexabilityStatus'];?></td>
                    <td><?= $link['metaKeyword'];?></td>
                    <td><?= $link['metaKeywordLength'];?></td>
                    <td><?= $link['xRobotTag'];?></td>   
                 </tr>
                 <?php $i++;endforeach ?>  
             </table>
               </div>
             </pre>
        <?php } ?>   
        
        <br><hr>

        <center> 
            <footer><h5>Script Developed By Algobasket</h5></footer>
        </center>    
   
   </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html> 
