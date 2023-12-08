<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'functions/getSheetData.php';
require_once  'functions/config.php';
require_once  'functions/databseConnection/db_connection.php';

$googleSheetsReader = new GoogleSheetsReader();

// Get Clinicians Sheet data 
$getCliniciansSheetDetail = Secret::getCliniciansSheetDetail();
$datahere = $googleSheetsReader->readSheet('SECTION 2: MEDICATION  ', ['name', 'dose', 'frequency']);
array_shift($datahere);
// echo '<pre>' ; print_r($datahere); echo '</pre>' ; 



foreach ($datahere as $value) {
// echo $value['dose'];
echo  $valueToInsert = $value['frequency'];
if($valueToInsert != ''){
// $createCleaning = $db->query("INSERT INTO medication_frequency (frequency) VALUES (?)", $valueToInsert);
}
}


echo "updated successfully";
?>

