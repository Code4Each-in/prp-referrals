<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';

if (isset($_POST['clientIssueId'])) {
    $clientIssueId = $_POST['clientIssueId'];
    $clientNamely = $db->query('SELECT * FROM client_namely WHERE issue_id = ?', $clientIssueId)->fetchAll();

    $intervention = $db->query('SELECT * FROM intervention WHERE issue_id = ?', $clientIssueId)->fetchAll();


    $clientLongTermGoal = $db->query('SELECT * FROM client_long_term_goal WHERE issue_id = ?', $clientIssueId)->fetchAll();

    $clientShortTermGoal = $db->query('SELECT * FROM client_short_term_goal WHERE issue_id = ?', $clientIssueId)->fetchAll();

    $additionalInformationService = $db->query('SELECT * FROM additional_information_service WHERE issue_id = ?', $clientIssueId)->fetchAll();

    $additionalInformationClient = $db->query('SELECT * FROM additional_information_client WHERE issue_id = ?', $clientIssueId)->fetchAll();

    $clientNamelyData = [];
    $interventionData = [];
    $clientLongTermGoalData = [];
    $clientShortTermGoalData = [];
    $additionalInformationServiceData = [];
    $additionalInformationClientData = [];


    if (count($clientNamely) > 0) {
        $clientNamelyData = $clientNamely;
    }
    if (count($intervention) > 0) {
        $interventionData = $intervention;
    }
    if (count($clientLongTermGoal) > 0) {
        $clientLongTermGoalData = $clientLongTermGoal;
    }
    if (count($clientShortTermGoal) > 0) {
        $clientShortTermGoalData = $clientShortTermGoal;
    }

    if (count($additionalInformationService) > 0) {
        $additionalInformationServiceData = $additionalInformationService;
    }
    if (count($additionalInformationClient) > 0) {
        $additionalInformationClientData = $additionalInformationClient;
    }
    echo json_encode(['clientNamelyData' => $clientNamelyData, 'interventionData' => $interventionData, 'clientLongTermGoalData' => $clientLongTermGoalData, 'clientShortTermGoalData' => $clientShortTermGoalData, 'additionalInformationServiceData' => $additionalInformationServiceData, 'additionalInformationClientData' => $additionalInformationClientData]);
}
