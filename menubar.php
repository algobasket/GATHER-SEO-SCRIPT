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
            <a class="nav-link h1" href="audit-ready-to-process.php">Audit</a>
            <a class="nav-link h1" href="settings.php?s=email-verify-api">API</a>
            <a class="nav-link h1" href="settings.php?s=smtp">SMTP</a> 
            <a class="nav-link h1" href="settings.php?s=email-templates">Template</a>  
            <a class="nav-link h1" href="blacklisted.php">Blacklisted</a>   
            <a class="nav-link h1" href="waitinglist.php">Waiting List</a>   
       
             <a class="nav-link h1" href="auth.php?logout=1">Logout</a> 
        <?php }else{ ?>
             <a class="nav-link h1 btn-success" href="auth.php" style="float: right;">Login</a>       
        <?php } ?> 
            
          </div>  
        </div>
      </div>
    </nav>