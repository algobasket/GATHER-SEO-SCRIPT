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

if(isset($_POST['startScrapping']))  
{   
    $title =  $_POST['title'];   
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
         $linkCounter = 0;
        foreach($keywordsArray as $k)
        {
           foreach($locationsArray as $l)
           {
              $query = $k." ". $l;
              $generateLinks = generateLinks($query);
              $data['keyword']  = trim($k);    
              $data['location'] = trim($l);       
              $data['links'] = $generateLinks;      
              $link_data[]   = $generateLinks;         
              //$linkCounter += count($generateLinks);   
              //$linkCounts = $linkCounter;    
              $queue[] = $data;        
           } 
        }  
       
        $links = $link_data;
        $singleArray = array_unique(call_user_func_array('array_merge', $links)); 
        $linkCounts = count($singleArray);      
    }           
    
    if($queue) 
    {  
       $created_at = date('d M,Y');
       $updated_at = date('d M,Y'); 
     
       $json  = trim(json_encode($queue));     
       $json2 = trim(json_encode($singleArray));         
       $sql = "INSERT INTO queue SET name='$title',data='$json',data2='$json2',link_counts='$linkCounts',created_at='$created_at',updated_at='$updated_at',status=0";   
       mysqli_query($conn,$sql);            
    }  
         
}

function removeDuplicates($array) 
{
    return array_values(array_unique($array));
}

 if(@$_GET['operation'] == 'delete') 
{
  deleteQueue($_GET['id']);  
  header('location:index.php');
  exit;       
}

 if(isset($_GET['id'])) 
{  
     
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous"> 
    <style type="text/css">
        input[type="text"],textarea[name="locations"],textarea[name="keywords"],tr{   
            border:1px solid #000;
            border-radius: 0;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
  </head>
  <body class="bg-light text-success">
  
   <div class="container">

    <?php include 'menubar.php';?>  

   <center>
        <br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts">SCRAPPING</h1> 
        
        <form method="POST"> 
        <div class="row">
            <div class="col-md-12">  
                <input type="text" name="title" placeholder="Enter Title ..." class="form-control" required />  
            </div> 
        </div><br>   
        <div class="row">
            <div class="col-md-6">
                <?php $defaultKeywords = isset($_POST['keywords']) ? $_POST['keywords'] : "Website Design\nWeb Design\nWeb Developer\nWebsite Developer";?> 
                <?php $defaultLocations = isset($_POST['locations']) ? $_POST['locations'] : "Los Angeles, CA\nLong Beach, CA\nWhittier, CA\nLa Habra, CA\nSanta Ana, CA\nRosemead, CA";?>     
                <textarea class="form-control" name="keywords" placeholder="Keywords separated by newline or ','...eg : Criminal Attorney,Restaurant,..." required style="height: 300px;"><?= $defaultKeywords;?></textarea>
            </div>
            <div class="col-md-6"> 
                <textarea class="form-control" name="locations" placeholder="Location separated by newline or ','...eg : Dallas Tx,Orange County California,..." required style="height: 300px;"><?= $defaultLocations;?></textarea>  
            </div>   
        </div><br> 

         <?php if(isset($_SESSION['username'])) : ?> 
             <div class="row"><input type="submit" name="startScrapping" value="ADD TO QUEUE" class="btn btn-dark" /></div> 
         <?php endif ?>  

        </form>

       <hr>
       <center><h3>QUEUE LISTS</h3></center>
       <?php $queueList = queueList();?> 
       <table class="table table-bordered">
           <?php foreach($queueList as $q) : ?>
           <tr>
               <td><?= strtoupper($q['name']);?></td> 
               <td><b><?= $q['link_counts'];?><b></td> 
               <?php if(isset($_SESSION['username'])) : ?>
               <td style="width:50px;">
                  <a href="javascript:void(0)" data-delete="index.php?id=<?= $q['id'];?>&operation=delete" data-title="Delete" data-msg="Do you want to delete it ?" class="btn btn-dark btn-sm openModal">
                    <i class="bi bi-trash3-fill"></i>  
                  </a> 
               </td>
               <td style="width:120px;">
                 <a href="audit-worker.php?id=<?= $q['id'];?>" class="btn btn-dark btn-sm" target="__SELF">Real Scrap<i class="bi bi-play-fill"></i></a>
              </td> 
              <td style="width:120px;">  
                <!-- <a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="runAudit(<?= $q['id'];?>)"><i class="bi bi-play-fill"></i></a> -->
                <a href="audit.php?id=<?= $q['id'];?>" class="btn btn-success btn-sm" target="__blank">Test Scrap<i class="bi bi-play-fill"></i></a> 
              </td> 
              <?php endif ?> 
           </tr>
       <?php endforeach ?> 
       </table>
        
       
        <!-- <?php if(isset($links)){ ?>
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
        <?php } ?>   -->
        
        <br><hr>
        <footer>
            <h5>Scrapping Script | Developed By Algobasket</h5>  
        </footer>  
    </center> 
   </div> 

     <!-- Modal -->
        <div class="modal fade modalPopup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> 
          <div class="modal-dialog modal-dialog-centered modal-lg"> 
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="audit.php" target="_blank">  
              <div class="modal-body"></div> 
              <div class="modal-footer"> 
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                 <a href="" class="btn btn-outline-dark confirmBtn"></a> 
              </div>
               </form>
            </div>
          </div>
        </div> 
    
    <script type="text/javascript"> 
         $(document).ready(function(){
        $('.openModal').click(function(){
           var link  = $(this).attr('data-delete');
           var title = $(this).attr('data-title');
           var msg   = $(this).attr('data-msg');
           $('.modal-title').html(title);
           $('.confirmBtn').html(title);
           $('.modal-body').html(msg);
           $('.confirmBtn').attr('href',link); 
           $('.modalPopup').modal('show');   
        });
    });   
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

        function runAudit(id)      
        {
            var data = { 
                id : id
            }  
            $.ajax({
                method:'POST',
                url:'audit.php',
                data:data,
                success:function(result){
                  console.log(result);
                }
            });
        } 
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>

<!-- Web Design - California Scrape

Website Design
Web Design
Web Developer
Website Developer

Los Angeles, CA
Long Beach, CA
Whittier, CA
La Habra, CA
Santa Ana, CA
Rosemead, CA -->
