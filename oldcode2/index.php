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
require 'scrapper.php';    

if(isset($_POST['startScrapping']))  
{   
    $keywords =  $_POST['keywords'];   
    $locations =  $_POST['locations'];


    if($keywords && $locations)
    {
        //$keywordsArray = explode(',',$keywords);
        //$locationsArray = explode(',',$locations); 


        // Check if input is separated by newlines
        if ((strpos($keywords, "\n") !== false) && (strpos($locations, "\n") !== false)) {
            $keywordsArray  = explode("\n", $keywords);
            $locationsArray = explode("\n",$locations);   
        } 
        // Check if input is separated by commas
        elseif ((strpos($keywords, ",") !== false) && (strpos($locations, ",") !== false)) {
            $keywordsArray  = explode(",", $keywords);
            $locationsArray = explode(",", $locations);
        }  
        else {
            echo "Input is not separated by newlines or commas.";
        }  

        foreach($keywordsArray as $k)
        {
           foreach($locationsArray as $l)
           {
              $query = $k." ". $l;
              $links[] = generateLinks($query);
                    
           }
        }
    }    
}  


function generateLinks($query)
{
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
    return $data;   
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
  <body class="bg-dark text-success">


   <div class="container">
   <center>
        <br><br><br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts">HIRE A GEEK | SEO SCRIPT</h1> 
        <h3>Enter Keywords and Locations</h3> 
         

        <form method="POST">  
        <div class="row">
            <div class="col-md-6">
                <?php $defaultKeywords = isset($_POST['keywords']) ? $_POST['keywords'] : "Criminal Attorney\nRestaurant";?> 
                <?php $defaultLocations = isset($_POST['locations']) ? $_POST['locations'] : "Dallas TX\nLos Angeles California";?>  
                <textarea class="form-control" name="keywords" placeholder="Keywords separated by newline or ','...eg : Criminal Attorney,Restaurant,..." required style="height: 300px;"><?= $defaultKeywords;?></textarea>
            </div>
            <div class="col-md-6"> 
                <textarea class="form-control" name="locations" placeholder="Location separated by newline or ','...eg : Dallas Tx,Orange County California,..." required style="height: 300px;"><?= $defaultLocations;?></textarea>  
            </div>   
        </div><br>  
        <div class="row">
            <input type="submit" name="startScrapping" value="Generate Links" class="btn btn-dark" /> 
        </div> 
        </form>

       
       
       
       
        <?php if(isset($links)){ ?>
            <hr><br>
            <center><h5>Found <?= linkCounts($links);?> links</h5></center>     
            <table class="table table-sm fs-6 fst-italic"> 

                <?php $i = 1;foreach($links as $link){ ?>


                    <?php foreach($link as $url) { ?>  
                           <tr> 
                             <td><?= $i;?></td>   
                             <td><small><?= substr($url,0,100);?>...</small></td>      
                             <td><small><a href="javascript:void(0)" class="btn btn-outline-dark btn-sm copy-link" data-link="<?= base64_encode($url);?>">Copy Link</a></small></td>    
                             <td><small><a href="audit.php?link=<?= base64_encode($url);?>" target="_blank">Generate Audit</a></small></td>    
                           </tr>

                    <?php $i++;} ?>   
                  
                <?php } ?> 

            </table>
        <?php } ?>  
        
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
