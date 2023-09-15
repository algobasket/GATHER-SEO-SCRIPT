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
require 'vendor/autoload.php';

if(isset($_REQUEST))      
{ 

    //$url = $_REQUEST['link'];  
    //$id  = 18;     
    $id  = $_REQUEST['id'];     
    
    $queue = getQueueInfoById($id); 
    $data = $queue['data'];
    $decode = safeJsonDecode($data);  
    //preTest($decode);exit;           
    foreach($decode as $r)    
    {
        $linksArray = $r['links']; 
        foreach($linksArray as $lk)
        {   
            $parsed_url = parse_url($lk); 
            $domain = $parsed_url['host'];
            $spreadSheetName = preg_replace('/^www\./', '', $domain);
               
            $links = auditScrap($lk,$spreadSheetId,$id);     
                                          
        }            
    }   
};
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
                    <td><?= $link['response_time'];?></td> 
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
<?php require('flush.php');?> 
