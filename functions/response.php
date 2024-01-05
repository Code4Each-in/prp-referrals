<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once  'config.php';

$googelData = Secret::getDriveKey();
$client = new Google_Client();
$client->setClientId($googelData['clientId']);
$client->setClientSecret($googelData['clientSecret']);
$client->setRedirectUri($googelData['redirectUri']);
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
    $jsonCredentials = json_encode($credentials);
    if (!file_exists($storedCredentialsPath)) {
        if (!touch($storedCredentialsPath)) {
            die("Failed to create the file.");
        }
    }

    if (!chmod($storedCredentialsPath, 0666)) {
        die("Failed to set file permissions.");
    }

    $fileHandle = fopen($storedCredentialsPath, 'w');

    if ($fileHandle === false) {
        die("Error opening the file for writing.");
    }

    $bytesWritten = fwrite($fileHandle, $jsonCredentials);

    if ($bytesWritten === false) {
        die("Error writing data to the file.");
    }
    fclose($fileHandle);
    return true;
}
