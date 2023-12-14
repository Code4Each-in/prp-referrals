<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';
require_once  'insertData.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="assets/css/form.css">
    <title>Psychiatric Rehabilitation Program (PRP)</title>
    <style>
        .alert-box
  {
    /* max-width : 300px;
    min-height: 300px;
     */
    .alert-icon
    {
        padding-bottom: 20px;
    }
  }
        </style>
</head>

<body>
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedData = [];
    foreach ($_POST as $key => $value) {
        if($value == ''){
            $value = null;
        }
        // if($value != ''){
            if(is_array($value)){
                $submittedData[addUnderscoreBeforeCapital($key)]= implode(', ', $value);
            }else{
                $submittedData[addUnderscoreBeforeCapital($key)] = $value;
            }
        // }
       
    }

    $fieldsToInsert = ['referral_type', 'ref_date', 'ref_first_name', 'ref_last_name', 'credentials','affiliated_ref_organization','ref_clinician_phone','ref_clinician_email','ref_npi','services1','client_first_name','client_last_name','client_birth_date','client_gender','client_address','minor_age','services2','services3','services4', 'services5', 'services6','client_homeless','disorder','communicable_diseases','medications','discharged','arrested','client_grade','employed','receiving_treatment','currently_enrolled','individual_nature','individual_intensive_care','individual_intensivelevel','support_considered','eligible_disable_admin_service','organic_process_or_syndrome','behavioral_control','lacks_capacity_for_prp','referral_source_paid','referral_source','reason_for_insufficient_treatment' ];

    $keyMapping = [
        'services1' => 'housing - assisted living services - 24/7 supervision',
        'services2' => 'housing - supportive housing services - day time supervision',
        'services3' => 'psychiatry',
        'services4' => 'medication management',
        'services5' => 'mental health counseling',
        'services6' => 'primary care',
    ];
    
            $newArray = [];
        foreach ($fieldsToInsert as $key) {
            if (isset($submittedData[$key])) {
                $newKey = $key;
                $value = $submittedData[$key];
                if(isset($keyMapping[$key])){
                    $newKey = $keyMapping[$key];
                    $value = 'yes';
                }
                $newKey = str_replace('_', ' ',$newKey);
                $newArray[$newKey] = $value;

            } else {
                if(isset($keyMapping[$key])){
                    $newKey = $keyMapping[$key];
                }
                $newKey = str_replace('_', ' ',$newKey);
                $newArray[$newKey] = 'NA'; 
            }
        }
    $filteredArray = array_filter(
        $submittedData,
        function ($key) use ($fieldsToInsert) {
            return in_array($key, $fieldsToInsert);
        },
        ARRAY_FILTER_USE_KEY
    );

    // echo '<pre>' ; print_r($newArray); echo '</pre>' ;

 $colName = implode(', ', array_keys($filteredArray));
 $colValues = array_values($filteredArray);

 $limit = count($filteredArray);
$dynamicStrings = [];
for ($i = 1; $i <= $limit; $i++) {
    $dynamicStrings[] = '?';
}

$questionMark = implode(', ', $dynamicStrings);
$insertData =  $db->query("INSERT INTO submit_form_data ($colName ) VALUES ( $questionMark )" , $colValues);

// insert in sheet 
$googleSheetsHandler = new GoogleSheetsHandler();
$result = $googleSheetsHandler->insertData($newArray);
$insertData = true;
    if($insertData){
        ?>
<div class="container mt-4">
  <div class='row'>
    <div class="alert-box" style="float: none; margin: 0 auto;">
    <div class="alert alert-success">
      <div class="alert-icon text-center">
        <i class="fa fa-check-square-o  fa-3x" aria-hidden="true"></i>
      </div>
      <div class="alert-message text-center">
        <strong>Success!</strong> Your Data is submited successfully .
      </div>
    </div>
  </div>
    </div>
</div>
<?php 
    }

}
function addUnderscoreBeforeCapital($str) {
    $str = preg_replace('/([a-z])([A-Z])/', '$1_$2', $str);

    // Convert the entire string to lowercase
    return strtolower($str);
}
?>
</body>
</html>
