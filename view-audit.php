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
        $queueId  = isset($_REQUEST['q']) ? $_REQUEST['q'] : 0;  
        if(@$_REQUEST['q'] == "")
        {
           $queueId = 0;
        } 
        $lk = $_REQUEST['link'];   
       
        if(isBase64Encoded($lk) == true)
        {
          $lk = base64_decode($lk);    
        }   

        $parsed_url = parse_url($lk); 
        $host = @$parsed_url['host'];          
     
        // Define a regular expression pattern to match URLs 
        $urlPattern = '/^(https?:\/\/)?([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,}$/i';

        if (preg_match($urlPattern, $lk)) 
        { 
           
            if (strpos($lk, 'http://') === 0 || strpos($lk, 'https://') === 0) 
            {
                $parsed_url = parse_url($lk); 
                $host = @$parsed_url['host'];
                
            } else {
                $host = $lk; 
            }
        }    
    
     $auditData = getAuditData($host,$queueId); 
                                                     
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
        input[type="text"],alert,textarea[name="locations"],textarea[name="keywords"]{   
            border:1px solid #000;
            border-radius: 0;
        }
        table{
            border-top:1px solid;
            margin-top: 20px !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>  
    <script type="text/javascript" src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Include SheetJS library via CDN -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script> 

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


   <div class="container">
         <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-success">
              <div class="container-fluid">
                <a class="navbar-brand h1" href="index.php">HIRE A GEEK</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                  <div class="navbar-nav">
                    <a class="nav-link active h1" aria-current="page" href="index.php">Home</a>
                     <?php if(@$_SESSION['username']){ ?> 
                    <a class="nav-link h1" href="audit-ready-to-process.php">Audit Ready To Process</a>
                    <a class="nav-link h1" href="settings.php?s=email-verify-api">Email Verify API</a>
                    <a class="nav-link h1" href="settings.php?s=smtp">SMTP Settings</a> 
                    <a class="nav-link h1" href="settings.php?s=email-templates">Email Template Settings</a>  
                    <a class="nav-link h1" href="blacklisted.php">Blacklisted</a>  
               
                     <a class="nav-link h1" href="auth.php?logout=1">Logout</a> 
                     <?php }else{ ?> 
                     <a class="nav-link h1 btn-success" href="auth.php" style="float: right;">Login</a>       
                     <?php } ?> 
                    
                  </div>  
                </div>
              </div>
         </nav>
        <br><br><br><br>
        <h1 class="text-center display-2 gfonts text-success">Audit Detail</h1>
        <div class="alert alert-dark">   
            <b>Audit for > <?= $host;?> ...</b>  
        </div>

        <a href="javascript:void(0)" class="btn btn-outline-dark openLinkModal">Website Link Analysis</a>
        <a href="javascript:void(0)" class="btn btn-outline-dark" id="exportSpreadSheet">Export</a>

        <script>
             document.getElementById("exportSpreadSheet").addEventListener('click', function() {
                /* Create worksheet from HTML DOM TABLE */
                var wb = XLSX.utils.table_to_book(document.getElementById("mytable"));

                /* Get the first sheet from the workbook */
                var ws = wb.Sheets[wb.SheetNames[0]];

                /* Define cell styles with background color */
                var cellStyles = {
                    A1: { fill: { fgColor: { rgb: "FFFF00" } } }, // Example: Yellow background for cell A1
                    C2: { fill: { fgColor: { rgb: "00FF00" } } }   // Example: Green background for cell C2
                    // Add more cell styles as needed
                };

                /* Apply cell styles to the worksheet */
                for (var cellAddress in cellStyles) {
                    if (cellStyles.hasOwnProperty(cellAddress)) {
                        ws[cellAddress].s = cellStyles[cellAddress];
                    }
                }

                /* Export to file (start a download) */
                XLSX.writeFile(wb, "SheetJSTable.xlsx");
            });
        </script> 

        <br>

        <?php if(@$_GET["module"] == "websiteLinkAnalysis") : ?> 
        <?php $storeUrl = isset($_POST['link']) ? $_POST['link'] : "";?>           
        <?php endif ?> 

        <?php if(is_array($auditData)){ ?> 
            <hr>
            <pre style="margin-top:-60px !important;">   
           
             <table id="mytable" class="table-bordered table-sm table-hover">
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
                 <?php if(is_array($auditData)) : ?>   
                 <?php $i=1;foreach($auditData as $link) : ?>  
                 <tr> 
                    <td><?= $i;?></td>
                    <td><?= substr($link['url'],0,60);?>..</td>  
                    <td><?= $link['depth'];?></td>    
                    <td><?= $link['score'];?></td>
                    <td><?= $link['ganalytics_status'];?></td> 
                    <td><?= $link['ganalytics_match'];?></td>
                    <td><?= round($link['response_time'],2);?> S</td> 
                    <td><?= round(($link['response_time']+2),2);?> S</td> 
                    <td><?= round($link['page_size']/1024,2) . 'kb';?></td>    
                    <td class="<?= addBgColor($link['ssl_status']);?>"><?= $link['ssl_status'];?></td>     
                    <td class="<?= addBgColor($link['h1_in_title']);?>"><?= $link['h1_in_title'];?></td>    
                    <td class="<?= tdColorStatus($link['meta_description'],'Meta-Description');?>"><?= $link['meta_description'] ? $link['meta_description'] : "Empty";?></td> 
                    <td class="<?= tdColorStatus($link['meta_description_l'],'Meta-Description-Length');?>"><?= $link['meta_description_l'];?></td>
                    <td class="<?= addBgColor($link['status']);?>"><?= $link['status'];?></td>
                    <td class="<?= addBgColor($link['status_code']);?>"><?= $link['status_code'];?></td>
                    <td>Email</td>  
                    <td>Email Privacy</td>  
                    <td class="<?= tdColorStatus($link['phones'],'Phones');?>"><?= $link['phones'] ? $link['phones'] : "Empty";?></td>     
                    <td class="<?= tdColorStatus($link['socials'],'Socials');?>"><?= ($link['socials'] == 0) ? "No" : $link['socials'];?></td>    
                    <td class="<?= tdColorStatus($link['contact_forms'],'Contact-Forms');?>"><?= $link['contact_forms'];?></td> 
                    <td class="<?= tdColorStatus($link['title'],'Title');?>"><?= $link['title'] ? $link['title'] : "Emtpy";?></td> 
                    <td class="<?= tdColorStatus($link['word_counts'],'Word-Counts');?>"><?= $link['word_counts'];?></td>   
                    <td><?= $link['two_words'];?></td>  
                    <td><?= $link['three_words'];?></td> 
                    <td><?= $link['four_words'];?></td> 
                    <td><?= $link['five_words'];?></td>   
                    <td>Last Modified</td> 
                    <td class="<?= tdColorStatus($link['h1'],'H1');?>"><?= trim($link['h1']);?></td>
                    <td class="<?= tdColorStatus($link['h1_l'],'H1-Length');?>"><?= $link['h1_l'];?></td> 
                    <td class="<?= tdColorStatus($link['h1_c'],'H1-Count');?>"><?= $link['h1_c'];?></td>
                    <td class="<?= tdColorStatus($link['h2_c'],'H2-Count');?>"><?= $link['h2_c'];?></td> 
                    <td><?= $link['h3_c'];?></td>
                    <td><?= $link['h4_c'];?></td>
                    <td><?= $link['h5_c'];?></td> 
                    <td><?= $link['h6_c'];?></td>      
                    <td class="<?= tdColorStatus($link['heading_c'],'Heading-Count');?>"><?= $link['heading_c'];?></td>
                    <td class="<?= tdColorStatus($link['img_c'],'Image-Count');?>"><?= $link['img_c'];?></td>
                    <td><?= $link['img_alt_tags'];?></td>   
                    <td><?= $link['outbound_links'];?></td>
                    <td><?= $link['total_outbound_links'];?></td>  
                    <!-- <td><?= $link['brokenLinks'];?></td>  -->
                    <td class="<?= tdColorStatus($link['favicon_status'],'Favicon');?>"><?= $link['favicon_status'];?></td>
                    <td class="<?= tdColorStatus($link['indexability'],'Indexability');?>"><?= $link['indexability'];?></td> 
                    <td class="<?= tdColorStatus($link['indexability_status'],'Indexability-Status');?>"><?= $link['indexability_status'];?></td>
                    <td class="<?= addBgColor($link['xml_sitemap']);?>"><?= $link['xml_sitemap'] ? $link['xml_sitemap'] : "Empty";?></td>  
                    <td class="<?= addBgColor($link['robots_txt']);?>"><?= $link['robots_txt'] ? $link['robots_txt'] : "Empty";?></td>      
                 </tr>
                 <?php $i++;endforeach ?> 
                 <?php endif ?> 
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
              <form action="view-audit.php" target="_blank">      
                  <div class="modal-body">    
                     <input type="text" class="form-control" name="link" placeholder="Enter Link To Audit" required />  
                     <input type="hidden" class="form-control" name="q" value="<?= @$_GET['q'];?>" required />  
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
