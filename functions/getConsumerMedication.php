<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';

if (isset($_POST['clientId']) && isset($_POST['clientId'])) {
   $clientId = $_POST['clientId'];
   $consumerMedication = $db->query('SELECT * FROM consumer_medication WHERE client_id = ? ', $clientId)->fetchAll();
   if (count($consumerMedication) > 0) {
      echo json_encode($consumerMedication);
   }
}
