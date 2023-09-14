<?php
require_once 'vendor/autoload.php';
 
define('GOOGLE_CLIENT_ID', '526688169778-6go35i343bdtl36iks5f62kmd8pj9bqr.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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'); 
 
$config = [
    'callback' => 'http://localhost/Scrapping/business-scrapper/spreadsheets/callback.php',
    'keys'     => [
                    'id' => GOOGLE_CLIENT_ID,
                    'secret' => GOOGLE_CLIENT_SECRET
                ],
    'scope'    => 'https://www.googleapis.com/auth/spreadsheets',
    'authorize_url_parameters' => [
            'approval_prompt' => 'force', // to pass only when you need to acquire a new refresh token.
            'access_type' => 'offline'
    ]
];  
 
$adapter = new Hybridauth\Provider\Google( $config );
?>