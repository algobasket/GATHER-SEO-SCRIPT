<?php
require 'vendor/autoload.php'; // Include the Google API Client Library

$serviceAccountJson = 'service-account-credentials.json'; // Replace with your service account JSON key file
$spreadsheetId = '12Ypi_hZXEpBkruqhOI-IouaoWMwnJ2yNftMyYQbtfAo'; // Replace with the ID of your Google Sheet

$client = new Google_Client();
$client->setAuthConfig($serviceAccountJson);
$client->addScope(Google_Service_Drive::DRIVE);

$driveService = new Google_Service_Drive($client);

// Define the permissions for sharing (Viewers)
$permissions = new Google_Service_Drive_Permission();
$permissions->setType('anyone');
$permissions->setRole('reader');  

try {
    // Share the Google Sheet with anyone with the link
    $driveService->permissions->create($spreadsheetId, $permissions, array('fields' => 'id'));
    
    echo 'Google Sheet is now accessible to anyone with the link.';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?> 