<?php
session_start();
require_once 'config.php';
 
try {
    $adapter->authenticate();
    $token = $adapter->getAccessToken();
    $encode = json_encode($token);
    $_SESSION['stored_access_token'] = $encode; 
    echo "Access token inserted successfully.";
}
catch( Exception $e ){
    echo $e->getMessage() ;
}   
?>