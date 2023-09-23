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


$getEmailSent = getEmailSent($isSent = null,$queueInstant = null);    

 if(@$_GET['operation'] == 'delete') 
{
  $link = $_GET['link'];
  whitelistLink(0,$link);    
  header('location:blacklisted.php');  
  exit;       
}

 if(isset($_GET['reply'])) 
{
  $isReply = $_GET['reply']; 
  $email = $_GET['email'];
  $link  = $_GET['link'];   
  replyStatus($isReply,$email,$link);      
  header('location:waitinglist.php');   
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

    <?php include 'menubar.php';?> 

   <center>
        <br><br><br><br><br><br>
        <h1 class="text-center display-2 gfonts">Waiting List</h1>   

       <hr><br>
      
       <table class="table table-hover borderTable"> 
          <tr>
             <th>Website</th>
             <th>Owner</th>
             <th>Email ID</th>
             <th>Sent Date</th>
             <th>Next Schedule</th>
             <th>Got Response</th>
             <th>Action</th>
           </tr>            
           <?php if($getEmailSent) : ?>         
           <?php foreach($getEmailSent as $q) : ?>         
           
           <tr>   
               <td style="cursor: pointer;"><b><?= $q['link'];?></b></td>  
               <td style="cursor: pointer;"><?= $q['owner'];?></td>  
               <td style="cursor: pointer;"><?= $q['email_to'];?></td>   
               <td style="cursor: pointer;"><?= ($q['sent_date']) ? date('d M,Y',strtotime($q['sent_date'])) : "Not Yet";?></td>  
               <td style="cursor: pointer;"><?= ($q['next_date'] != 0) ? date('d M,Y',strtotime($q['next_date'])) : "-end-";?></td>    
               <td style="cursor: pointer;"><?= bS($q['status']);?></td>              
               <td style="cursor: pointer;">
                <?php if($q['status'] == 0){ ?>
                  <a href="waitinglist.php?reply=1&link=<?= $q['link'];?>&email=<?= $q['email_to'];?>" class="btn btn-outline-dark btn-sm">Mark As Replied</a> 
                <?php }else{ ?>
                  <a href="waitinglist.php?reply=0&link=<?= $q['link'];?>&email=<?= $q['email_to'];?>" class="btn btn-outline-dark btn-sm">Mark As Not Replied</a> 
                <?php } ?>
                
                  
            </td>               
           </tr> 
           <?php endforeach ?> 
           <?php endif ?> 
       </table>     
        <pre>Note* - If Got Response is YES, no further automated email will be send.</pre>
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
