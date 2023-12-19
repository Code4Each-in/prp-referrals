<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'databseConnection/db_connection.php';
require_once  'insertData.php';
require_once  'config.php';
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
    // echo '<pre>' ; print_r($_POST); echo '</pre>' ;

    $submittedData = [];
    $questionnaireData = [];
    $medicationFormData = [];
    $submittedData['medications_description'] = null;
    $submittedData['intenseServices'] = null;
    $submittedData['diagnosis'] = null;
    $submittedData['refDate'] = null;
    $submittedData['refDate'] = null;
    foreach ($_POST as $key => $value) {
        if($value == ''){
            $value = null;
        }
        // if($value != ''){

            if($key === 'questionnaire'){
                $questionnaireData[] = $value;
            }
            elseif(is_array($value)){
                if(isset($submittedData['medications']) && $submittedData['medications'] === 'yes' && $key === 'medication'){

                    $medicationFormData[] = $value ;
                }

                if(isset($submittedData['individual_intensivelevel']) && $submittedData['individual_intensivelevel'] === 'yes' && $key === 'intenseServices'){
                    $submittedData[addUnderscoreBeforeCapital($key)]= implode(', ', $value);

                }

            }else{
                if (isset($submittedData['medications'])){
                    if ($submittedData['medications'] === 'no' && $key === 'medicationsNoRadio') {
                        $submittedData['medications_description'] = $value;
                    }elseif($submittedData['medications'] === 'yes' && $key === 'medicationTreatment'){
                        $submittedData['medications_description'] = $value;
                    }
                }

             
        // }
        $submittedData[addUnderscoreBeforeCapital($key)] = $value;

            }
    }

    $fieldsToInsert = ['referral_type', 'ref_date', 'ref_first_name', 'ref_last_name', 'credentials','affiliated_ref_organization','ref_clinician_phone','ref_clinician_email','ref_npi','services1','client_first_name','client_last_name','client_birth_date','client_gender','client_address','minor_age','services2','services3','services4', 'services5', 'services6','client_homeless','disorder','communicable_diseases','medications','discharged','arrested','client_grade','employed','receiving_treatment','currently_enrolled','individual_nature','individual_intensive_care','individual_intensivelevel','support_considered','eligible_disable_admin_service','organic_process_or_syndrome','behavioral_control','lacks_capacity_for_prp','referral_source_paid','referral_source','reason_for_insufficient_treatment' , 'diagnosis', 'medications_description', 'intense_services'];

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

    // echo '<pre>' ; print_r($filteredArray); echo '</pre>' ;

 $colName = implode(', ', array_keys($filteredArray));
 $colValues = array_values($filteredArray);

 $limit = count($filteredArray);
$dynamicStrings = [];
for ($i = 1; $i <= $limit; $i++) {
    $dynamicStrings[] = '?';
}

$questionMark = implode(', ', $dynamicStrings);
$insertData =  $db->query("INSERT INTO submit_form_data ($colName ) VALUES ( $questionMark )" , $colValues);
$submit_fromId = $db->lastInsertID();

// insert questionnaire Data 

// Get client Sheet data 
$saticText = Secret::saticText();
$questionnaireValue = [];
$questionnaireDataSheet = [];
foreach ($questionnaireData as $key => $value) {
$ageCheckbox = '';
    foreach ($value as $impairment_questionnaire_name => $impairment_questionnaire_data) {
        $ageCheckbox = $impairment_questionnaire_name;
        // echo '<pre>' ; print_r($value); echo '</pre>' ;
        $questionnaireDataSheet[] = $impairment_questionnaire_data;
       $insertData =  $db->query("INSERT INTO submitted_impairment_questionnaire (submit_form_id,  impairment_questionnaire_name ) VALUES ( ?, ? )" , $submit_fromId, $impairment_questionnaire_name);
        $impairment_questionnaire_id = $db->lastInsertID();

        $insertData =  $db->query("INSERT INTO submitted_impairment_questionnaire_data (impairment_questionnaire_id, mental_health_diagnosis,  symptom, Symptom_experience_date, client_issue, namely, specifically, client_need, intervention, intervention_specifically, specific_need_area, long_term_goal, short_term_goal ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )" , $impairment_questionnaire_id, $impairment_questionnaire_data['mentalDiagnosis'], $impairment_questionnaire_data['symptom'], $impairment_questionnaire_data['experienced'], $impairment_questionnaire_data['clientIssue'], $impairment_questionnaire_data['namely'], $impairment_questionnaire_data['specifically'], $impairment_questionnaire_data['clientAdditionalInformation'], $impairment_questionnaire_data['intervention'], $impairment_questionnaire_data['specificallyIntervention'], $impairment_questionnaire_data['serviceAdditionalInformation'], $impairment_questionnaire_data['clientLongTermGoal'], $impairment_questionnaire_data['clientShortTermGoal']);

    ///for sheet 
    $checkbox = [];
    if (strpos($ageCheckbox, "adult") !== false) {
        $checkbox = $saticText['adultCheckobox'];
       
    } elseif (strpos($ageCheckbox, "minor") !== false) {
        $checkbox = $saticText['minorCheckobox'];
    }


    if (isset($checkbox[$ageCheckbox])) {
      $questionnaireValue[] = $checkbox[$ageCheckbox];
  } 
    }
}

$medicationFormDataSheet = [];
if(count($medicationFormData) > 0){
foreach ($medicationFormData as $key => $medicationFormValue) {
    $ageCheckbox = '';
        foreach ($medicationFormValue as $eachcol => $eachValue) {
           $insertData =  $db->query("INSERT INTO medication_form_data (submit_form_id,  name, dosage, frequency ) VALUES ( ?, ? , ?, ?)" , $submit_fromId, $eachValue['name'], $eachValue['dosage'], $eachValue['frequency']);

   // insert insheet 

   $medicationFormDataSheet[]= $eachValue;
        }
    }
}

$newArray['medication Detail'] = json_encode($medicationFormDataSheet, JSON_PRETTY_PRINT);

$questionnaireValue = json_encode($questionnaireValue, JSON_PRETTY_PRINT);
$newArray['Functional impairment questionnaire'] = $questionnaireValue;

foreach ($questionnaireDataSheet as $key => $sheetValue) {
    $newArray[] = json_encode($sheetValue, JSON_PRETTY_PRINT);

}

// insert in sheet 
$googleSheetsHandler = new GoogleSheetsHandler();
$result = $googleSheetsHandler->insertData($newArray);
$delay_seconds = 5;
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

        <p>Redirecting in <span id="timer"><?php echo $delay_seconds; ?> seconds</span></p>
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
  <script>
    setTimeout(function() {
      window.location.href = "http://prp-referrals.code4each.com";
    }, <?php echo $delay_seconds * 1000; ?>); 
  </script>
</body>
</html>
