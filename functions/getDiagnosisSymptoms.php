<?php
require_once  'databseConnection/db_connection.php';
$diagnosisName = $db->query('SELECT * FROM diagnosis_name')->fetchAll();

$diagnosisAdultArray = [];
$diagnosisMinorArray = [];

foreach ($diagnosisName as $diagnosis) {
    if ($diagnosis['type'] == 'adult') {
        $diagnosisAdultArray[] = [
            'id' => $diagnosis['id'],
            'diagnosis_name' => $diagnosis['diagnosis_name']
        ];
    } elseif ($diagnosis['type'] == 'minor') {
        $diagnosisMinorArray[] = [
            'id' => $diagnosis['id'],
            'diagnosis_name' => $diagnosis['diagnosis_name']
        ];
    }
}
