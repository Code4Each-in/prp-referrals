<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1043613198330-cf98b9hggt1of5a0tpf337eab2ph0o3l.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://prp-referrals.code4each.com/functions/response.php');
// $client->setRedirectUri('http://localhost/prp-referrals/functions/response.php');

$client->addScope(Google_Service_Drive::DRIVE_FILE);

// Handle the OAuth2 callback
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    saveUserCredentials($token);
    $client->setAccessToken($token);

    header('Location: https://prp-referrals.code4each.com/functions/new.php');
    // header('Location: http://localhost/prp-referrals/functions/new.php');
    exit;
}

//save first google user Credentials to save pdfs in that perticular account
function saveUserCredentials($credentials)
{
    $storedCredentialsPath = "credentials.json";

    // Convert credentials to JSON
    $jsonCredentials = json_encode($credentials);

    // Create the file if it doesn't exist
    if (!file_exists($storedCredentialsPath)) {
        if (!touch($storedCredentialsPath)) {
            die("Failed to create the file.");
        }
    }

    // Set read and write permissions
    if (!chmod($storedCredentialsPath, 0666)) {
        die("Failed to set file permissions.");
    }

    // Open the file for writing (create if it doesn't exist)
    $fileHandle = fopen($storedCredentialsPath, 'w');

    if ($fileHandle === false) {
        // Handle the error, e.g., log it or display a message
        die("Error opening the file for writing.");
    }

    // Write the JSON data to the file
    $bytesWritten = fwrite($fileHandle, $jsonCredentials);

    if ($bytesWritten === false) {
        // Handle the error, e.g., log it or display a message
        die("Error writing data to the file.");
    }

    // Close the file handle
    fclose($fileHandle);

    // Optionally, you can return true or perform additional actions upon success
    return true;
}
