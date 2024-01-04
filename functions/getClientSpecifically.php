<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';

if (isset($_POST['clientIssueId']) && isset($_POST['clientNameleyId'])) {
   $clientIssueId = $_POST['clientIssueId'];
   $clientNameleyId = $_POST['clientNameleyId'];
   $clientSpecifically = $db->query('SELECT * FROM client_specifically WHERE issue_id = ? AND client_namely_id = ?', $clientIssueId, $clientNameleyId)->fetchAll();
   if (count($clientSpecifically) > 0) {
      echo json_encode($clientSpecifically);
   }
}
