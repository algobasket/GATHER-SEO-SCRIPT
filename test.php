<?php 
require 'vendor/autoload.php'; 

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets\SpreadSheet;

// $client = new Google_Client(); 
// $client->setAuthConfig('./service-account-credentials.json');
// $client->addScope(Google_Service_Sheets::SPREADSHEETS);


// $service = new Google_Service_Sheets($client);


// $spreadsheet = new Google_Service_Sheets_Spreadsheet([
//     'properties' => ['title' => 'Gaming']  
// ]);

// $createdSpreadsheet = $service->spreadsheets->create($spreadsheet);
// $newSpreadsheetId = $createdSpreadsheet->spreadsheetId;


// $fieldNames = ['Apple', 'Mango', 'Orange']; 
// $data = [
//     ['A', 'B', 'C'],
//     ['A', 'B', 'C'],
// ];

// $values = array_merge([$fieldNames], $data); 

// $range = 'A1'; 
// $body = new Google_Service_Sheets_ValueRange(['values' => $values]);


// $result = $service->spreadsheets_values->update($newSpreadsheetId, $range, $body, ['valueInputOption' => 'RAW']);

// if ($result) {
//     echo 'Data uploaded successfully to Spreadsheet ID: ' . $newSpreadsheetId . '<br>';

//     // Make the spreadsheet public
//     $driveService = new Google_Service_Drive($client);
//     $driveService->getClient()->setAccessToken($client->getAccessToken());

//     $driveService->files->update($newSpreadsheetId, null, [
//         'permissions' => [
//             [
//                 'type' => 'anyone',
//                 'role' => 'reader', 
//             ],
//         ],
//     ]);
//     echo 'Spreadsheet is now public.<br>'; 

//     // Get the public link
//     $publicLink = 'https://docs.google.com/spreadsheets/d/' . $newSpreadsheetId;
//     echo 'Public Link: <a href="' . $publicLink . '" target="_blank">' . $publicLink . '</a>';
// } else {
//     echo 'Error uploading data to Spreadsheet ID: ' . $newSpreadsheetId . '<br>';
// }





/**
* create an empty spreadsheet
* 
*/

 function create($title)
    {   
        /* Load pre-authorized user credentials from the environment.
           TODO(developer) - See https://developers.google.com/identity for
            guides on implementing OAuth2 for your application. */
        $client = new Google\Client();
        //$client->useApplicationDefaultCredentials();
        $client->setAuthConfig('./service-account-credentials.json');
        $client->addScope(Google\Service\Drive::DRIVE);
        $service = new Google_Service_Sheets($client);
        try{

            $spreadsheet = new Google_Service_Sheets_Spreadsheet([
                'properties' => [
                    'title' => $title
                    ]
                ]);
                $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                    'fields' => 'spreadsheetId'
                ]);
                printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
                return $spreadsheet->spreadsheetId; 
        }
        catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
          }
    }

    create("Maths");

?>



