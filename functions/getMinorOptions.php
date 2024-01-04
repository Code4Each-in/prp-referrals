<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';

if (isset($_POST['id']) && isset($_POST['id'])) {
   $id = $_POST['id'];
   $data = $db->query('SELECT * FROM minor_options WHERE minor_checkbox_id = ?', $id)->fetchAll();
   if (count($data) > 0) {
      echo json_encode($data);
   }
}
