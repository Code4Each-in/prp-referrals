<?php 
require_once  'databseConnection/db_connection.php';
    $medicationName =$db->query('SELECT * FROM medication_name')->fetchAll();

    $medicationDosage =$db->query('SELECT * FROM medication_dosage')->fetchAll();

    $medicationFrequency =$db->query('SELECT * FROM medication_frequency')->fetchAll();

    $clientIssue =$db->query('SELECT * FROM client_issue')->fetchAll();
    // $clientIssue =$db->query('SELECT * FROM client_issue')->fetchAll();
    // die;

    // $consumerInformation =$db->query('SELECT * FROM  consumer_medication  LEFT JOIN consumer_information ON consumer_medication.client_id =  consumer_information.id ')->fetchAll();
    $consumerInformation =$db->query('SELECT * FROM  consumer_information')->fetchAll();
    // print_r($consumerInformation);
    
?>