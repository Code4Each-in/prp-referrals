<?php 
require_once  'databseConnection/db_connection.php';
    $diagnosisName =$db->query('SELECT * FROM diagnosis_name')->fetchAll();

    $diagnosisAdultArray = [];
    $diagnosisMinorArray = [];

    foreach ($diagnosisName as $diagnosis) {
        if ($diagnosis['type'] == 'adult') {
            $diagnosisAdultArray[] = [
                'id' => $diagnosis['id'],
                'diagnosis_name' => $diagnosis['diagnosis_name']
            ];
        }elseif($diagnosis['type'] == 'minor'){
            $diagnosisMinorArray[] = [
                'id' => $diagnosis['id'],
                'diagnosis_name' => $diagnosis['diagnosis_name']
            ];
        }
    }
    
// print_r($diagnosisMinorArray);die;

    // $medicationDosage =$db->query('SELECT * FROM medication_dosage')->fetchAll();

    // $medicationFrequency =$db->query('SELECT * FROM medication_frequency')->fetchAll();

?>