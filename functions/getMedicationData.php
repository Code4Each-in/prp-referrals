<?php 
require_once  'databseConnection/db_connection.php';
    $medicationName =$db->query('SELECT * FROM medication_name')->fetchAll();

    $medicationDosage =$db->query('SELECT * FROM medication_dosage')->fetchAll();

    $medicationFrequency =$db->query('SELECT * FROM medication_frequency')->fetchAll();

    // print_r($medication_dosage );
    // die;
?>