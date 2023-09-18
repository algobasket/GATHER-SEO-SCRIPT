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
$s = $_GET['s'];

if(isset($_POST['addNewApi']))
{
  $apiType   = $_POST['apiType'];
  $apiVendor = $_POST['apiVendor'];
  $apiKey    = $_POST['apiKey'];
  $clientId  = $_POST['clientId'];
  $clientSecret = $_POST['clientSecret'];
  $redirectUrl  = $_POST['redirectUrl']; 
  $result = addnewApi($apiType,$apiVendor,$apiKey,$clientId,$clientSecret,$redirectUrl);    
}

if(isset($_POST['addNewSmtp']))
{
  $smtpName     = $_POST['smtpName'];
  $smtpServer   = $_POST['smtpServer'];
  $smtpUsername = $_POST['smtpUsername'];
  $smtpPassword = $_POST['smtpPassword'];
  $smtpEmail = $_POST['smtpEmail'];
  $smtpPort     = $_POST['smtpPort'];
  $result = addnewSmtp($smtpName,$smtpServer,$smtpUsername,$smtpPassword,$smtpEmail,$smtpPort);     
} 


if(isset($_POST['addEmailTemplate']))
{
  $subject     = $_POST['subject'];
  $template   = $_POST['template'];
  $result = addnewTemplate($subject,$template);      
}   

 if(@$_GET['operation'] == 'delete') 
{
  $apiVendor = @$_POST['apiVendor'];
  $apiKey = @$_POST['apiKey'];
  deleteSetting($_GET['id']);     
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
         input[type="text"],textarea[name="locations"],textarea[name="template"],select[name="apiType"],.borderTable{    
            border:1px solid #000 !important;
            border-radius: 0 !important;
        }
       tr{
            border:1px solid #fff !important; 
        }
        hr{
          border:1px solid #000 !important; 
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
      
       <?php if($s == 'email-verify-api') : ?>
             <br><br><br><br><br><br> 
             <h1 class="text-center display-2 gfonts">EMAIL SETTINGS</h1>    
             <hr>
          <form method="POST"> 
            <table class="table table-bordered">
                <tr>
                    <td>
                        <select name="apiType" class="form-control" required> 
                           <option value="email_api">Email APIs</option>
                           <option value="email_verify_api">Email Verify APIs</option> 
                           <option value="googlesheet_api">GoogleSheet APIs</option>
                        </select>
                </td> 
                </tr>
                <tr>
                    <td><input type="text" class="form-control" name="apiVendor" placeholder="API Vendor" required></td>  
                </tr>
                 <tr>    
                    <td><input type="text" class="form-control" name="apiKey" placeholder="API KEY*"></td> 
                </tr>
                 <tr>    
                    <td><input type="text" class="form-control" name="clientId" placeholder="CLIENT ID"></td> 
                </tr>
                 <tr>    
                    <td><input type="text" class="form-control" name="clientSecret" placeholder="CLIENT SECRET"></td> 
                </tr>
                <tr>    
                    <td><input type="text" class="form-control" name="redirectUrl" placeholder="REDIRECT URL"></td>   
                </tr>
                 <tr>    
                    <td><input type="submit" name="addNewApi" class="btn btn-dark" value="Add" /></td>  
                </tr>
            </table>
          </form>  

        <hr>
        <br>
         <table class="table table-bordered table-sm"> 
            <thead>  
            <tr class="borderTable">
                <th>Type</th>   
                <th>Name</th>  
                <th>Keys</th>    
                <th>Client ID</th>    
                <th>Client Secret</th>    
                <th>Redirect</th>    
                <th></th>   
            </tr>
            </thead>  
            <?php $getApis = getApis(null);?>
            <?php foreach($getApis as $api) : ?>
            
            <tr class="borderTable">
                <td><small><?= $api['setting_type'];?></small></td>  
                <td><small><?= $api['api_vendor'];?></small></td>  
                <td><small><?= $api['api_key'];?></small></td>
                <td><small><?= $api['client_id'];?></small></td>   
                <td><small><?= $api['client_secret'];?></small></td>   
                <td><?= $api['redirect_url'];?></td>        
                <td><a href="javascript:void(0)" data-delete="settings.php?s=email-verify-api&id=<?= $api['id'];?>&operation=delete" data-title="Delete" data-msg="Do you want to delete it ?"  class="btn btn-dark btn-sm openModal"><i class="bi bi-trash3-fill"></i></a></td>  
            </tr>
           <?php endforeach ?>
        </table>

    <?php endif ?>

    <?php if($s == 'smtp') : ?>
           <br><br><br><br><br><br> 
           <h1 class="text-center display-2 gfonts">SMTP SETTINGS</h1>    
           <hr>
          <form method="POST"> 
            <table class="table table-bordered">
                <tr>
                    <td><input type="text" class="form-control" name="smtpName" placeholder="SMTP Vendor" required></td> 
                 </tr>
                 <tr>    
                    <td><input type="text" class="form-control" name="smtpServer" placeholder="SMTP Server" required></td>
                 </tr>
                  <tr>    
                    <td><input type="text" class="form-control" name="smtpEmail" placeholder="SMTP Email" required></td>
                 </tr>
                 <tr>     
                    <td><input type="text" class="form-control" name="smtpUsername" placeholder="SMTP Username" required></td> 
                 </tr>
                 <tr>    
                    <td><input type="text" class="form-control" name="smtpPassword" placeholder="SMTP password" required></td>
                 </tr>
                 
                 <tr>    
                    <td><input type="text" class="form-control" name="smtpPort" placeholder="SMTP port" required></td> 
                 </tr>
                 <tr>    
                    <td><input type="submit" name="addNewSmtp" class="btn btn-outline-dark" value="Add" /></td>   
                 </tr>  
            </table>
          </form>  

         <br><hr>
         <table class="table table-bordered table-sm">
               <tr class="borderTable">
                <th>SMTP Name</th> 
                <th>SMTP Server</th>    
                <th>SMTP Username</th>    
                <th>SMTP Email</th>    
                <th>SMTP Password</th>    
                <th>SMTP Port</th>      
                <th></th>  
            </tr>
            <?php $getSmtps = getSmtps();?>
            <?php foreach($getSmtps as $smtp) : ?>
            <tr class="borderTable">
                <td><?= $smtp['smtp_name'];?></td> 
                <td><?= $smtp['smtp_server'];?></td>    
                <td><?= $smtp['smtp_username'];?></td>    
                <td><?= $smtp['smtp_email'];?></td>    
                <td><?= $smtp['password'];?></td>    
                <td><?= $smtp['port_no'];?></td>      
                <td>
                    <a href="javascript:void(0)" data-delete="settings.php?s=smtp&id=<?= $smtp['id'];?>&operation=delete" data-title="Delete" data-msg="Do you want to delete it ?"  class="btn btn-dark btn-sm openModal">
                        <i class="bi bi-trash3-fill"></i>
                    </a> 
                </td>  
            </tr>
           <?php endforeach ?> 
        </table>

    <?php endif ?>

    <?php if($s == 'email-templates') : ?> 
          <br><br><br><br><br><br> 
          <h1 class="text-center display-2 gfonts">EMAIL TEMPLATE SETTINGS</h1>    
          <hr> 
          <form method="POST"> 
            <table class="table table-bordered">
                <tr>
                    <td><input type="text" class="form-control" name="subject" placeholder="Subject" required></td> 
                </tr>
                <tr>
                    <td><textarea class="form-control" name="template" placeholder="Template" style="height:200px"></textarea></td>  
                </tr>
                 <tr>
                    <td><input class="btn btn-dark" name="addEmailTemplate" value="Add" type="submit" /></td>    
                </tr>   
            </table>
          </form>  

         <br><hr>
         <table class="table table-bordered">
            <?php $getEmailTemplates = getEmailTemplates();?> 
            <?php foreach($getEmailTemplates as $eTemplate) : ?>
            <tr class="borderTable">
                <td>Subject : <?= $eTemplate['subject'];?></td> 
            </tr>
            <tr class="borderTable">    
                <td><?= $eTemplate['email_template'];?></td> 
            </tr>
            <tr class="borderTable">          
                <td><a href="javascript:void(0)" data-delete="settings.php?s=email-templates&id=<?= $eTemplate['id'];?>&operation=delete" data-title="Delete" data-msg="Do you want to delete it ?"  class="btn btn-outline-dark openModal">Delete</a></td>  
            </tr>
           <?php endforeach ?>
        </table>

    <?php endif ?>    
        
       
    

     
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
