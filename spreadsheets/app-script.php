<?php
// Deployment ID - AKfycbzdr4hfDKZuyC_5JlkeRBKz3Ha4luC7ncGx8NVugt8apbjwxsi9HkNLF2UXJ9bzqlnI
// Web App - https://script.google.com/macros/s/AKfycbzdr4hfDKZuyC_5JlkeRBKz3Ha4luC7ncGx8NVugt8apbjwxsi9HkNLF2UXJ9bzqlnI/exec
set_time_limit(0);

if($_REQUEST['operation'] == "addFields")
{
   
    $folderId = '1mdsXQ79d44bWoAss-26DCfBHC4fufVu3'; // Replace with the actual folder ID
	$spreadSheetName = "ScrappingAlgo";
	$fields = 'URL,Depth,Score,GAnalyticStatus,GAnalyticMatch,ResponseTime,LoadTime,PageSize,SSL,H1inTitle,MetaDescription,'.  
	          'MetaDescriptionLength,Status,StatusCode,Email,EmailPrivacy,Phones,SocialLinks,ContactForms,Title,WordCounts,2Words,'. 
	          '3Words,4Words,5Words,LastModified,H1,H1Length,H1Count,H2Count,H3Count,H4Count,H5Count,H6Count,HeadingCount,ImageCount,'. 
	          'ImageAltTags,OutboundLinks,TotalOutboundLinks,FaviconStatus,Indexability,IndexabilityStatus,XMLSitemap,Robots.txt';               
	         

	$webApp = 'https://script.google.com/macros/s/AKfycbzK0KQAwtbV-nxSueJFC_CB-aarX_rCGEKXi8mgrBW7m5utFqTdvtWtp0NHRA_bMlGy/exec';          
	$script_url = $webApp . '?folderId=' . $folderId . '&spreadsheetName=' . $spreadSheetName .'&headers=' . trim($fields); 

	$response = getCurlContent($script_url);        

	if ($response === false) {
	    echo 'Error accessing the script URL.';
	} else { 
	    // Extract the spreadsheet ID from the response
	     $spreadsheetId = trim(substr($response, strpos($response, 'Spreadsheet ID:') + 15)); 
	     $link = 'https://docs.google.com/spreadsheets/d/'.$spreadsheetId;
	    echo 'Spreadsheet created successfully with ID: <a href="'.$link.'" target="__blank">' . $spreadsheetId.'</a>';  
	}
}   

if($_REQUEST['operation'] == "addRecords")
{
   
    $spreadsheetId = "YOUR_SPREADSHEET_ID"; // Replace with your spreadsheet ID
	$data = [
	    ["Data1", "Data2", "Data3"],
	    ["Value1", "Value2", "Value3"]
	];     

	$base_url = "https://script.google.com/macros/s/AKfycbwTG9nfThBGrZLcGeZ9YWEQ5iRt6HGVrhb3yqvvSSshPj6Kwun_hmlZYlGZmgXgFaVS/exec";    
	$params = [
	    'spreadsheetId' => $spreadsheetId,
	    'data' => json_encode($data), // Convert data to JSON
	];  

	// Send a POST request to your Google Apps Script web app
	$ch = curl_init($base_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	$response = curl_exec($ch);
	curl_close($ch);

	// Handle the response from the Google Apps Script
	if ($response === false) {
	    echo "Error: " . curl_error($ch);
	} else {
	    echo $response;
	}
}  


function getCurlContent($url) 
{ 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  //curl_setopt($ch, CURLOPT_HEADER, 1);
  //curl_setopt($ch, CURLOPT_NOBODY, 1);  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
  $data = curl_exec($ch);

  curl_close($ch);
  return $data;
} 
?>  