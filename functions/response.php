<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

session_start();
// unset($_SESSION['access_token']);
$client = new Google_Client();
$client->setClientId('1043613198330-cf98b9hggt1of5a0tpf337eab2ph0o3l.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://prp-referrals.code4each.com/functions/response.php');
$client->addScope(Google_Service_Drive::DRIVE_FILE);

// Handle the OAuth2 callback
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
// print_r($token);die;
    // Store the access token in the session
    $_SESSION['access_token'] = $token;

    // Redirect back to the main script
    header('Location: https://prp-referrals.code4each.com/functions/new.php');
    exit;
}