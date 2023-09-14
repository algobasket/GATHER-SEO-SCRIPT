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

if(isset($_POST['submitPpcAdvertiser'])) 
{   
    $keywords =  $_POST['keywords'];   
    $location =  $_POST['location'];    
    
    $query = $keywords." ". $location; 
    $html = googleSearchForPpcAdvertise($query);
    
    libxml_use_internal_errors(true); 

    // Create a new DOMDocument
    $dom = new DOMDocument;

    // Load the HTML content into the DOMDocument
    $dom->loadHTML($html);

     // Retrieve and clear any errors that occurred during parsing
     $errors = libxml_get_errors();
     libxml_clear_errors();   

    // Find all anchor (a) tags in the HTML
    $links = $dom->getElementsByTagName('a');

    // Loop through the links and extract their href attributes
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
         // Check if the href does not contain 'google.com'
        if (strpos($href, 'google.com') === false) {
            $explode = explode('&url=',$href);
            $url = @$explode[1]; 
            if($url) 
            {
                $data[] = $url;  
                unset($explode); 
                unset($url);  
            }
            
        }
    }   
  
} 



if(isset($_POST['submitWebsiteLinkAnalysis'])) 
{   
    $link =  $_REQUEST['link'];    
    
   

}
    
?>   
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HIRE A GEEK | SEO TOOLKIT </title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bungee Spice|Silkscreen"> 
  </head>
  <body class="bg-light text-dark">


   <div class="container">
   <center>
        <br><br><br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts">HIRE A GEEK | SEO SCRIPT</h1> 
        <h3>Select Your Option</h3>

        <a href="index.php?module=getPpcAdvertiser" class="btn btn-outline-dark btn-lg" target="_blank">GET PPC Advertisers</a>
        <a href="index.php?module=websiteLinkAnalysis" class="btn btn-outline-dark btn-lg" target="_blank">Website Link Analysis</a> 
        <br><br> 
        <?php if(@$_GET["module"] == "getPpcAdvertiser") : ?> 
        <?php $storeKeywords = isset($_POST['keywords']) ? $_POST['keywords'] : "";?>    
        <?php $storeLocation = isset($_POST['location']) ? $_POST['location'] : "";?>     
       <div class="col-md-6"> 
            <form method="POST" target="_blank"> 
                <div class="input-group input-group-lg">
                     <input type="text" name="keywords" class="form-control" placeholder="Enter keywords" value="<?= $storeKeywords;?>" required>
                </div><br>
                 <div class="input-group input-group-lg"> 
                     <input type="text" name="location" class="form-control" placeholder="Enter Location, eg : Los Angeles,California" value="<?= $storeLocation;?>" required><br>
                </div>
                <br><input type="submit" name="submitPpcAdvertiser" class="btn btn-outline-dark" /> 
            </form>
       </div>
       
       
        <?php endif ?>

        <?php if(@$_GET["module"] == "websiteLinkAnalysis") : ?> 
        <?php $storeUrl = isset($_POST['link']) ? $_POST['link'] : "";?>       
           <div class="col-md-6"> 
                <form method="POST" target="_blank">  
                     <div class="input-group input-group-lg"> 
                       <input type="text" name="link" class="form-control" placeholder="Enter link to analysis" value="<?= $storeUrl;?>" required/><br> 
                      </div> 
                      <br> <input type="submit" name="submitWebsiteLinkAnalysis" class="btn btn-outline-dark" /> 
                </form>
            </div> 
        <?php endif ?>

        <?php if(is_array($data)) : ?>
            <hr><br>
            <center><h5>Found <?= count($data);?> links</h5></center>
            <table class="table table-responsive table-sm table-success fs-6 fst-italic"> 
                <?php $i=1;foreach($data as $d) : ?> 
                <tr>
                    <th>&nbsp;<?= $i;?></th>
                    <td><?= substr($d,0,100);?>....</td> 
                    <td><a href="audit.php?module=websiteLinkAnalysis&link=<?= base64_encode($d);?>" class="btn btn-dark btn-sm" target="_blank">Analyse</a></td>    
                    <td><a href="javscript:void(0)" data-link="<?= $d;?>" class="btn btn-dark btn-sm copy-link" onclick="copyLink()">Copy</a></td>    
                </tr>
            <?php $i++;endforeach ?>
            </table>

        <?php endif ?>  
        
        <br><hr>
        <footer>
            <h5>Script Developed By Algobasket</h5> 
        </footer>  
    </center> 
   </div> 
    
    <script type="text/javascript"> 
        function copyLink()
        {
            var links = document.querySelectorAll('.copy-link');
            links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var dataLinkContent = link.getAttribute('data-link');
                var tempInput = document.createElement('input');
                tempInput.value = dataLinkContent;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput); 
            });
        });
        } 
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html> 
