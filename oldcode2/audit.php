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
    $url = $_GET['link'];
    if(isBase64Encoded($url) == true)
    {
      $link =  base64_decode($url);
    }else{
      $link =  $url;  
    }  
    
    $start_time = microtime(true);
    $html = getCurlPageInfo($link);
    $end_time = microtime(true);
    $load_time = $end_time - $start_time + 2;        
    $dom = new DOMDocument;
    @$dom->loadHTML($html['data']);  
    $links = array();  
    $counter = 1; 
    $arrayHref = $dom->getElementsByTagName('a');   

    foreach ($arrayHref as $a) {   
            $href = $a->getAttribute('href'); 
           
            if (!empty($href) && (strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0))
            {  
                   $start_time2 = microtime(true);
                   $getCurlPageInfo = getCurlPageInfo($href);
                   $end_time2 = microtime(true);
                   $response_time = round(($end_time2 - $start_time2),2); 
                   $load_time2    = round(($response_time + 2),2);     
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
                    $pageSize = round($pageSize/1024,2) . 'kb';


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
                     'response_time'   => $response_time . ' s',
                     'load_time'       => $load_time2 . ' s',     
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
                     'robotsTxtStatus'  => $robotsTxtStatus, 
                     '2words' => $twoWords, 
                     '3words' =>$threeWords,               
                     '4words' =>$fourWords,              
                     '5words' =>$fiveWords                  
                    ]; 


                if($counter == 10)  
                {
                    break;       
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
    <link href="https:///cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    
     <style type="text/css"> 
            body{
                background-color: #fff;
            }
        .scrollabletd {
            max-height: 50px;
            overflow-y: auto;  
            border: 1px solid #ccc;
          }
          .tdOk{ 
            background-color: #8ee5bd;
          }
          .tdErr{
            background-color: #fda9ab;
          }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>  
    <script type="text/javascript" src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
              $('#mytable').DataTable();
              $('.openLinkModal').click(function(){
                  $('#linkModal').modal('show'); 
              }); 
          } );
    </script> 
  </head>
  <body>


   <div class="container-fluid">
   
        <br><br><br><br>
        <h1>HIRE A GEEK | SEO SCRIPT</h1>
        <div class="alert alert-dark">   
            <b>Audit for > <?= substr($link,0,50);?> ...</b> 
        </div>

        <a href="javascript:void(0)" class="btn btn-outline-dark openLinkModal">Website Link Analysis</a>   
        | <span class="fs-5">Overall Load Time <span class="badge bg-dark "><?= round($load_time,2);?></span></span> 
        <br><br>  

        <?php if(@$_GET["module"] == "websiteLinkAnalysis") : ?> 
        <?php $storeUrl = isset($_POST['link']) ? $_POST['link'] : "";?>       
        <?php endif ?> 

        <?php if(is_array($links)){ ?> 
            <hr><br>
            <center><h5>Found <?= count($links);?> links</h5></center>
            <pre>  
           
             <table id="mytable" class="table-bordered table-sm">
                <thead>   
                 <tr class="bg-light"> 
                    <th>No</th> 
                    <th>URL</th>  
                    <th>Depth</th> 
                    <th>Score</th> 
                    <th>GAnalytic Status</th>  
                    <th>GAnalytic Match</th>
                    <th>Response Time</th>
                    <th>Load Time</th>
                    <th>Page Size</th>
                    <th>SSL</th>     
                    <th>H1 in Title</th>
                    <th>Meta Description</th>
                    <th>Meta Description Length</th>
                    <th>Status</th>
                    <th>Status Code</th>
                    <th>Email</th>
                    <th>Email Privacy</th>
                    <th>Phones</th>
                    <th>Social Links</th>  
                    <th>Contact Forms</th>
                    <th>Title</th>
                    <th>Word Counts</th>
                    <th>2 Words</th>
                    <th>3 Words</th>
                    <th>4 Words</th>
                    <th>5 Words</th> 
                    <th>Last Modified</th> 
                    <th>H1</th>
                    <th>H1 Length</th>
                    <th>H1 Count</th>
                    <th>H2 Count</th>
                    <th>H3 Count</th>
                    <th>H4 Count</th>
                    <th>H5 Count</th>
                    <th>H6 Count</th> 
                    <th>Heading Count</th>
                    <th>Image Count</th>
                    <th>Image Alt Tags</th> 
                    <th>Outbound Links</th>
                    <th>Total Outbound Links</th>
                    <!-- <th>Broken Links</th> -->
                    <th>Favicon Status</th> 
                    <th>Indexability</th>
                    <th>Indexability Status</th>
                    <th>XML Sitemap</th>
                    <th>Robots.txt</th>  
                 </tr>
                 </thead>
                 <tbody>
                 <?php $i=1;foreach($links as $link) : ?>
                 <tr>
                    <td><?= $i;?></td>
                    <td><?= $link['url'];?></td>  
                    <td><?= $link['depth'];?></td>    
                    <td><?= $link['score'];?></td>
                    <td><?= $link['gAnalyticStatus'];?></td> 
                    <td><?= $link['gAnalyticMatch'];?></td>
                    <td><?= $link['response_time'];?></td> 
                    <td><?= $link['load_time'];?></td>  
                    <td><?= $link['pageSize'];?></td>   
                    <td class="<?= addBgColor($link['ssl']);?>"><?= $link['ssl'];?></td>     
                    <td class="<?= addBgColor($link['h1InTitle']);?>"><?= $link['h1InTitle'];?></td>    
                    <td class="<?= tdColorStatus($link['metaDescription'],'Meta-Description');?>"><?= $link['metaDescription'];?></td> 
                    <td class="<?= tdColorStatus($link['metaDescriptionLength'],'Meta-Description-Length');?>"><?= $link['metaDescriptionLength'];?></td>
                    <td class="<?= addBgColor($link['status']);?>"><?= $link['status'];?></td>
                    <td class="<?= addBgColor($link['statusCode']);?>"><?= $link['statusCode'];?></td>
                    <td>Email</td>  
                    <td>Email Privacy</td>  
                    <td class="<?= tdColorStatus($link['phones'],'Phones');?>"><?= $link['phones'];?></td>     
                    <td class="<?= tdColorStatus($link['socials'],'Socials');?>"><?= ($link['socials'] == 0) ? "No" : $link['socials'];?></td>    
                    <td class="<?= tdColorStatus($link['contactForms'],'Contact-Forms');?>"><?= $link['contactForms'];?></td> 
                    <td class="<?= tdColorStatus($link['title'],'Title');?>"><?= $link['title'];?></td> 
                    <td class="<?= tdColorStatus($link['wordCounts'],'Word-Counts');?>"><?= $link['wordCounts'];?></td>  
                    <td><?= $link['2words'];?></td>  
                    <td><?= $link['3words'];?></td> 
                    <td><?= $link['4words'];?></td> 
                    <td><?= $link['5words'];?></td>   
                    <td>Last Modified</td> 
                    <td class="<?= tdColorStatus($link['h1'],'H1');?>"><?= trim($link['h1']);?></td>
                    <td class="<?= tdColorStatus($link['h1Length'],'H1-Length');?>"><?= $link['h1Length'];?></td> 
                    <td class="<?= tdColorStatus($link['h1Count'],'H1-Count');?>"><?= $link['h1Count'];?></td>
                    <td class="<?= tdColorStatus($link['h2Count'],'H2-Count');?>"><?= $link['h2Count'];?></td> 
                    <td><?= $link['h3Count'];?></td>
                    <td><?= $link['h4Count'];?></td>
                    <td><?= $link['h5Count'];?></td>
                    <td><?= $link['h6Count'];?></td>      
                    <td class="<?= tdColorStatus($link['headingCount'],'Heading-Count');?>"><?= $link['headingCount'];?></td>
                    <td class="<?= tdColorStatus($link['imageCount'],'Image-Count');?>"><?= $link['imageCount'];?></td>
                    <td><?= $link['imageWithAlt'];?></td>   
                    <td><?= $link['outBoundLinks'];?></td>
                    <td><?= $link['outBoundLinksCount'];?></td>  
                    <!-- <td><?= $link['brokenLinks'];?></td>  -->
                    <td class="<?= tdColorStatus($link['faviconStatus'],'Favicon');?>"><?= $link['faviconStatus'];?></td>
                    <td class="<?= tdColorStatus($link['indexability'],'Indexability');?>"><?= $link['indexability'];?></td> 
                    <td class="<?= tdColorStatus($link['indexabilityStatus'],'Indexability-Status');?>"><?= $link['indexabilityStatus'];?></td>
                    <td class="<?= addBgColor($link['sitemapUrlStatus']);?>"><?= $link['sitemapUrl'];?></td>  
                    <td class="<?= addBgColor($link['robotsTxtStatus']);?>"><?= $link['robotsTxt'];?></td>     
                 </tr>
                 <?php $i++;endforeach ?>
                 <?php unset($links);?>   
                 </tbody>   
             </table>
              
             </pre>
        <?php } ?>   
        
        <br><hr>
        <!-- Button trigger modal -->
        <!-- Modal -->
        <div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> 
          <div class="modal-dialog modal-dialog-centered modal-lg"> 
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Enter Link</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="audit.php" target="_blank">  
              <div class="modal-body">  
                 <input type="text" class="form-control" name="link" placeholder="Enter Link To Audit" required />  
              </div> 
              <div class="modal-footer"> 
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-dark" name="generateAuditSubmit" value="Generate Audit" /> 
              </div>
               </form>
            </div>
          </div>
        </div>
        <center> 
            <footer><h5>Script Developed By Algobasket</h5></footer> 
        </center>    
   
   </div> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html> 
