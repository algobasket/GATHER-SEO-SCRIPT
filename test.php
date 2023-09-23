<?php
ob_start();
session_start();
error_reporting(0); 
set_time_limit(0);  

// Check if the application is running on localhost
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

if ($host === 'localhost') {
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'hireageek';
} else {  
    // Use live server database configuration
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'hireageek';   
}      

// Create connection
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword,$dbName);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}  



echo "TESTTTT";          
?>





