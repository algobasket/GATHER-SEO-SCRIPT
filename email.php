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
if(isset($_GET['q']))
{
    $id = $_GET['q'];     
    $queue = getQueueInfoById($id);   
    $data = $queue['data'];    
    $decode = safeJsonDecode($data);        
    foreach($decode as $r) 
    { 
        $linksArray = $r['links'];
        $firstLink =  $linksArray[0];
        $getEmailQueueData = getEmailQueueData($firstLink);     

        foreach($linksArray as $lk)
        {   
            $parsed_url = parse_url($lk); 
            $domain = $parsed_url['host'];      
        }            
    }   
}    
?>   
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AUDIT | HIRE A GEEK | SEO TOOLKIT </title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bungee Spice|Silkscreen">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <style type="text/css">
         input[type="text"],textarea[name="locations"],textarea[name="template"]{    
            border:1px solid #000;
            border-radius: 0;
        }
    </style> 
  </head>
  <body class="bg-light text-success">  
  
   <div class="container">

   <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand h1" href="index.php">HIRE A GEEK</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active h1" aria-current="page" href="index.php">Home</a>
            <a class="nav-link h1" href="audit-ready-to-process.php">Audit Ready To Process</a>
            <a class="nav-link h1" href="settings.php?s=email-verify-api">Email Verify API</a>
            <a class="nav-link h1" href="settings.php?s=smtp">SMTP Settings</a> 
            <a class="nav-link h1" href="settings.php?s=email-templates">Email Template Settings</a>  
            <a class="nav-link h1" href="logout.php">Logout</a>  
            
          </div> 
        </div>
      </div>
    </nav>
   <?php  $qInfoById = getQueueInfoById($_GET['q']);?>   
   <center>
        <br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts"><?= $qInfoById['name'];?></h1>     
        <hr>       
      
        <table class="table table-bordered">    
            <tr>
                <td><input type="text" class="form-control" name="url" placeholder="URL" value="<?= $firstLink;?>" required></td>
                <td style="width: 20px;"><a href="" class="btn btn-success"><i class="bi bi-x-octagon-fill"></i></a></td> 
                <td><a href="" class="btn btn-primary"><i class="bi bi-search"></i></a></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" name="fullname" placeholder="Full Name" required></td>
                <td style="width: 20px;"></td>
                <td><a href=""></a></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" name="email" placeholder="Email" value="<?= $getEmailQueueData['email'];?>" required></td> 
                <td style="width: 20px;"><a href="" class="btn btn-dark"><i class="bi bi-check-circle-fill"></i></a></td> 
                <td></td>  
            </tr>
            <tr>
                <td colspan="3">
                    <textarea class="form-control" placeholder="Email Templates" name="template" style="height:300px"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="" class="btn btn-outline-dark" name="emailq">Email Queue</a>
                    <a href="" class="btn btn-outline-dark" name="emailnow">Email Now</a>
                </td> 
                <td></td> 
                <td><a href="" class="btn btn-outline-dark copyEmailTemp">Copy</a></td>  
            </tr>
        </table>
        
        <br><hr>
         
         <table class="table table-bordered">
            <tr>
                <td>AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA</td>
                <td style="width: 20px;"><a href="">Delete</a></td>
                <td>Search</td>
            </tr>
        
            <tr>
                <td colspan="3">
                    1 . 2 . 3 . 4 . 5 . 6 ......
                </td> 
            </tr>
        </table>



        <footer>
            <h5>Scrapping Script | Developed By Algobasket</h5>  
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
