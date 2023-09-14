<?php 
/*
  ************************ SCRAPPING SCRIPT **********************
  *********************** Coded By Algobasket *********************
  ****************** Contact : algobasket@gmail.com ***************
  *********************** Github : algobasket *********************    
*/

// Database Config  
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scrap_data";   

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}  

?> 