<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';
if (isset($_POST['diagnosisid'])) {
    $diagnosisid = $_POST['diagnosisid'];
    $symptomIds = $db->query('SELECT * FROM diagnosis_symptoms_mapping WHERE diagnosis_id = ?', $diagnosisid)->fetchAll();

    $symptomIdsArry = [];
    if (count($symptomIds) > 0) {
        foreach ($symptomIds as $symptomId) {
            $symptomIdsArry[] = $symptomId['symptom_id'];
        }

        $inClause = implode(',', $symptomIdsArry);
        $query = "SELECT * FROM symptoms WHERE id IN ($inClause)";
        $result = $db->query($query)->fetchAll();
        if (count($result) > 0) {
            echo json_encode($result);
        }else{
            echo json_encode([]);
        }
    }
}