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

if(isset($_POST['submitLogin']))  
{   
    $username =  $_POST['username'];   
    $password =  $_POST['password'];   

    if($username && $password) 
    {
       $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";   
       $query = mysqli_query($conn,$sql);  
       $row   = mysqli_fetch_assoc($query);
       if($row){
       	 $_SESSION['uid'] = $row['id'] ;
       	 $_SESSION['username'] = $username;
       	 header('location:index.php');
       	 exit; 
       }else{
       	 $error = "<div class='alert alert-danger'>Wrong Credentials</div>";
       }
    }   
         
}

if(@$_GET['logout'] == 1)   
{    
   	 unset($_SESSION['uid']);
   	 unset($_SESSION['username']);
   	 header('location:index.php'); 
   	 exit;          
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

    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-success">
      <div class="container-fluid">
        <a class="navbar-brand h1" href="index.php">HIRE A GEEK</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active h1" aria-current="page" href="index.php">Home</a>
            <?php if(isset($_SESSION['username'])) : ?>
            <a class="nav-link h1" href="audit-ready-to-process.php">Audit Ready To Process</a>
            <a class="nav-link h1" href="settings.php?s=email-verify-api">Email Verify API</a>
            <a class="nav-link h1" href="settings.php?s=smtp">SMTP Settings</a> 
            <a class="nav-link h1" href="settings.php?s=email-templates">Email Template Settings</a>   
            <?php endif ?>
              
          </div>  
        </div>
      </div>
    </nav>

   <center>
        <br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts">LOGIN</h1> 
        <?php if(@$error){ echo $error;unset($error); };?> 
        <form method="POST"> 
        <div class="row">
            <div class="col-md-12">  
                <input type="text" name="username" placeholder="Enter Username ..." class="form-control" required />  
            </div> 
        </div><br>   
        <div class="row">
            <div class="col-md-12">  
                <input type="text" name="password" placeholder="Enter Password ..." class="form-control" required />  
            </div> 
        </div><br>  
        <div class="row">
            <input type="submit" name="submitLogin" value="Login" class="btn btn-dark" /> 
        </div> 
        </form>

       <hr>
        
        <br><hr>
        <footer>
            <h5>Scrapping Script | Developed By Algobasket</h5>  
        </footer>  
    </center> 
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

<?php
if(isset($_SESSION['username']))
{
   header('location:index.php');   
   exit;  
} 
ob_flush(); 
?>

