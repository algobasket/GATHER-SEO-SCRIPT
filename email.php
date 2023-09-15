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
        $email = $getEmailQueueData['email'];
        $domain = $getEmailQueueData['domain'];
        $subject = $getEmailQueueData['subject'];
        $emailTemplate = $getEmailQueueData['emailTemplate'];
        foreach($linksArray as $lk)
        {   
            $parsed_url = parse_url($lk); 
            $host = $parsed_url['host'];
            $links[] = ['domain' => $host,'url' => $lk];       
        }            
    }   
} 
 if(@$_GET['operation'] == 'delete') 
{
  deleteQueue($_GET['q']);  
  header('location:email.php?q='.$_GET['q']);
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
    <link href="https:///cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style type="text/css">
         input[type="text"],textarea[name="locations"],textarea[name="template"]{    
            border:1px solid #000;
            border-radius: 0;
        }
        .green-border{
           border:2px solid #146c43 !important;  
        }
        .red-border{
            border:2px solid #bd6b7b !important; 
        }
    </style> 
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
     <script type="text/javascript" src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function () {
              $('.tablePaging').DataTable();
              // $('.openLinkModal').click(function(){
              //     $('#linkModal').modal('show'); 
              // }); 
          } );
    </script>     
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
                <td style="width: 20px;"><a href="" class="btn btn-dark"> <i class="bi bi-slash-circle"></i></a></td> 
                <td><a href="./oldcode2/audit.php?link=<?= base64_encode($firstLink);?>" class="btn btn-primary" target="__blank"><i class="bi bi-search"></i></a></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control fullname" name="fullname" placeholder="Full Name" value="<?= $email;?>" required></td>
                <td style="width: 20px;"></td>
                <td><a href=""></a></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control isEmailReal" name="email" placeholder="Email" value="<?= $email;?>" required></td> 
                <td style="width: 20px;">
                    <a href="javascript:void(0)" class="btn btn-success verify-email"><i class="bi bi-envelope"></i></a>
                </td>  
                <td><span class="isEmailValid"></span></td>  
            </tr>
            <tr>
                <td><input type="text" class="form-control subject" placeholder="Subject" value="<?= $subject;?>" required></td>
                <td style="width: 20px;"></td>
                <td><a href=""></a></td>
            </tr>
            <tr>
                <td colspan="3">
                    <textarea class="form-control copy-link" placeholder="Email Templates" name="template" style="height:300px"><?= $emailTemplate;?></textarea>
                </td>  
            </tr>
            <tr>
                <td>
                    <a href="javascript:void(0)" class="btn btn-outline-dark emailqueue" name="emailqueue">Email Queue</a>
                    <a href="javascript:void(0)" class="btn btn-outline-dark emailnow" name="emailnow">Email Now</a> 
                    <span class="emailSentStatus"></span> 
                </td> 
                <td></td> 
                <td><a href="" class="btn btn-outline-dark copyEmailTemp">Copy</a></td>    
            </tr>
        </table>
        
        <br>
         
         <table class="table table-bordered tablePaging"> 
            <thead>
                <tr>
                    <td>URL</td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
               <?php foreach($links as $link) : ?> 
                    <tr>
                        <td><?= removeQueryString($link['url']);?></td>  
                        <td style="width: 20px;"> <a href="javascript:void(0)" data-delete="email.php?q=<?= $_GET['q'];?>&operation=delete" data-title="Delete" data-msg="Do you want to delete it ?" class="btn btn-dark btn-sm openModal">
                            <i class="bi bi-slash-circle"></i>  
                          </a> 
                        </td>
                        <td>
                            <a href="./oldcode2/audit.php?link=<?= base64_encode($link['url']);?>" class="btn btn-primary btn-sm" target="__blank">
                                <i class="bi bi-search"></i>
                            </a>
                        </td>   
                    </tr> 
               <?php endforeach ?>
             </tbody>
        </table>



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
        $('.verify-email').click(function(){
            
           var email  = $('.isEmailReal').val();
           $('.isEmailReal').addClass('red-border'); 
           if(email)
           {    
               $('.isEmailValid').html('<img src="./icons/loading.gif" style="width:70px;height:50px;position:absolute"/>');
               var data   = {
                 email : email, 
                 isEmailValidAjax : 1 
               }; 
               $.ajax({
                 method:'POST',
                 url:'ajax.php',
                 data:data,
                 success:function(response){
                    $('.isEmailValid').html(response); 
                    $('.isEmailReal').removeClass('red-border').addClass('green-border');
                    console.log(response); 
                 }
               });
           }
        });
         $('.emailqueue').click(function(){
            
           var email  = $('.isEmailReal').val();
           var name   = $('.fullname').val();
           var subject   = $('.subject').val();
           var template   = $('.copy-link').val();  
           if(email && name && template)
           {    
               $('.emailSentStatus').html('<img src="./icons/loading.gif" style="width:70px;height:50px;position:absolute"/>');
               var data   = {
                 email_to : email,
                 name : name,
                 template : template, 
                 subject : subject,
                 queue_instant : "queue", 
                 emailSentAjax : 1 
               }; 
               $.ajax({
                 method:'POST',
                 url:'ajax.php',
                 data:data,
                 success:function(response){
                    $('.emailSentStatus').html(response).show(); 
                    console.log(response);  
                 }
               });
           }
        });
         $('.emailnow').click(function(){ 
            
           var email  = $('.isEmailReal').val();
           var name   = $('.fullname').val();
           var subject   = $('.subject').val();
           var template   = $('.copy-link').val();  
           if(email && name && template)
           {    
               $('.emailSentStatus').html('<img src="./icons/loading.gif" style="width:70px;height:50px;position:absolute"/>');
               var data   = {
                 email_to : email,
                 name : name,
                 template : template,
                 subject : subject, 
                 queue_instant : "instant", 
                 emailSentAjax : 1  
               }; 
               $.ajax({
                 method:'POST',
                 url:'ajax.php', 
                 data:data,
                 success:function(response){ 
                    $('.emailSentStatus').html(response).show(); 
                    console.log(response);  
                 }
               });
           }
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
