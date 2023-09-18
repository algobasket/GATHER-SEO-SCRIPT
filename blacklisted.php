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


$getBlacklistLink = getBlacklistLink(); 

 if(@$_GET['operation'] == 'delete') 
{
  $link = $_GET['link'];
  whitelistLink(0,$link);    
  header('location:blacklisted.php');  
  exit;       
}

if(isset($_POST['submitForBlacklist']))
{
   $link = $_POST['link'];
   $q = 0; 
  
   $domain = isLinkOrDomain($link);

   blacklistLink($q,$domain);    
   header('location:blacklisted.php');  
   exit;     
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
         input[type="text"]{      
            border:1px solid #000 !important;
            border-radius: 0 !important;
        }
        tr{
          border-bottom: 1px solid #fff !important; 
        }
        hr{
          border:1px solid #000 !important; 
        }
        .borderTable > tbody > tr {   
           border: 1px solid #000 !important; 
        } 
        .borderTable > tbody > tr > td{   
           border-left: 1px solid #000 !important; 
        } 
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
  </head>
  <body class="bg-light text-success">  
  
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

   <center>
        <br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts">Blacklisted</h1>   
        
         <hr>

        <form method="POST">   
          <table class="table"> 
            <tr>
              <td><input type="text" class="form-control" name="link" placeholder="Enter link or domain to blacklist.." required /></td>
              <td><input type="submit" class="btn btn-dark " name="submitForBlacklist" /></td>
            </tr>
          </table>
        </form>  

       <hr><br>
      
       <table class="table table-hover borderTable"> 
          <tr>
             <th>Website</th>
             <th>Blacklist Type</th>
             <th>Blacklisted Date</th>
             <th>Whitelist Date</th>
             <th></th>
           </tr>            
           <?php foreach($getBlacklistLink as $q) : ?> 
           
           <tr>   
               <td style="cursor: pointer;"><b><?= $q['link'];?></b></td>  
               <td style="cursor: pointer;"><?= ($q['queue_id'] == 0) ? "<b class='text-success'>Blacklisted Manually</b>" :"<b class='text-danger'>Blacklisted Through Links</b>"  ;?></td>  
               <td style="cursor: pointer;"><?= date('d M,Y',strtotime($q['created_at']));?></td>  
               <td style="cursor: pointer;"><?= date('d M,Y',strtotime($q['created_at']  . ' +1 year'));?></td>  
               <td style="width:50px;"><a href="javascript:void(0)" data-delete="blacklisted.php?id=<?= $q['id'];?>&operation=delete&link=<?= $q['link'];?>" data-title="Whitelist" data-msg="Do you want to whitelist this link/domain ?" class="btn btn-dark btn-sm openModal"><i class="bi bi-trash3-fill"></i></a></td>    
               <td style="width:50px;"><a href="view-audit.php?link=<?= $q['link'];?>" class="btn btn-success btn-sm"><i class="bi bi-search"></i></a></td>      
           </tr> 
           <?php endforeach ?> 
       </table>     
        
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html> 
<?php require('flush.php');?> 
