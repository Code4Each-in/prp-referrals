<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';

if (isset($_POST['clientIssueId']) && isset($_POST['interventionId'])) {
   $clientIssueId = $_POST['clientIssueId'];
   $interventionId = $_POST['interventionId'];
   $specificallyIntervention = $db->query('SELECT * FROM specifically_intervention WHERE issue_id = ? AND intervention_id = ?', $clientIssueId, $interventionId)->fetchAll();
   if (count($specificallyIntervention) > 0) {
      echo json_encode($specificallyIntervention);
   }
}
