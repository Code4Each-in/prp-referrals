<?php
require_once  'databseConnection/db_connection.php';
$medicationName = $db->query('SELECT * FROM medication_name')->fetchAll();

$medicationDosage = $db->query('SELECT * FROM medication_dosage')->fetchAll();

$medicationFrequency = $db->query('SELECT * FROM medication_frequency')->fetchAll();

$clientIssue = $db->query('SELECT * FROM client_issue')->fetchAll();

$consumerInformation = $db->query('SELECT * FROM  consumer_information')->fetchAll();
$minorCheckBox = $db->query('SELECT * FROM  minor_checkbox')->fetchAll();

$minorQuestion = $db->query('SELECT * FROM  minor_question')->fetchAll();

$minorQuestion_1 = [];
$minorQuestion_2 = [];
$minorQuestion_3 = [];
if(count($minorQuestion)> 0 ){
foreach($minorQuestion as $each)

if($each['question_no'] == 'minor_1'){
    $minorQuestion_1[] = $each;
}elseif($each['question_no'] == 'minor_2'){
    $minorQuestion_2[] = $each;

}elseif($each['question_no'] == 'minor_3'){
    $minorQuestion_3[] = $each;
}
}
// echo '<pre>' ; print_r($minorQuestion); echo '</pre>' ;

// echo '<pre>' ; print_r($minorQuestion_1); echo '</pre>' ;