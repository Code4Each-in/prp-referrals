<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once('tcpdf/tcpdf.php');
require_once  'databseConnection/db_connection.php';
require_once  'config.php';

session_start();
// unset($_SESSION['access_token']);die;

// Function to create a simple PDF
function createPDF($filePath, $submit_form_data, $submitted_impairment_questionnaire, $getMedications)
{
    $organizationName = "Changing Lives at Home, Inc.";
    $programName = "Psychiatric Rehabilitation Program";
    $address = "4805 Garrison Blvd, Baltimore, MD 21015";
    $text = "Mental Health Provider's Referral to the Psychiatric Rehabilitation Program (PRP)";

    //data 
    $refDate = isset($submit_form_data['ref_date']) ? $submit_form_data['ref_date'] : '';
    $referral_type = isset($submit_form_data['referral_type']) ? $submit_form_data['referral_type'] : '';
    $ref_first_name = isset($submit_form_data['ref_first_name']) ? $submit_form_data['ref_first_name'] : '';
    $ref_last_name = isset($submit_form_data['ref_last_name']) ? $submit_form_data['ref_last_name'] : '';
    $credentials = isset($submit_form_data['credentials']) ? $submit_form_data['credentials'] : '';
    $affiliated_ref_organization = isset($submit_form_data['affiliated_ref_organization']) ? $submit_form_data['affiliated_ref_organization'] : '';

    $ref_clinician_phone = isset($submit_form_data['ref_clinician_phone']) ? $submit_form_data['ref_clinician_phone'] : '';
    $ref_clinician_email = isset($submit_form_data['ref_clinician_email']) ? $submit_form_data['ref_clinician_email'] : '';
    $ref_npi = isset($submit_form_data['ref_npi']) ? $submit_form_data['ref_npi'] : '';

    $services1 = isset($submit_form_data['services1']) ? $submit_form_data['services1'].',' : '';
    $services2 = isset($submit_form_data['services2']) ? $submit_form_data['services2'].',' : '';
    $services3 = isset($submit_form_data['services3']) ? $submit_form_data['services3'].',' : '';
    $services4 = isset($submit_form_data['services4']) ? $submit_form_data['services4'].',' : '';
    $services5 = isset($submit_form_data['services5']) ? $submit_form_data['services5'].',' : '';
    $services6 = isset($submit_form_data['services6']) ? $submit_form_data['services6'].',' : '';

    $reason_for_insufficient_treatment = isset($submit_form_data['reason_for_insufficient_treatment']) ? $submit_form_data['reason_for_insufficient_treatment'] : '';
    $referral_source = isset($submit_form_data['referral_source']) ? $submit_form_data['referral_source'] : '';
    $referral_source_paid = isset($submit_form_data['referral_source_paid']) ? $submit_form_data['referral_source_paid'] : '';
    $lacks_capacity_for_prp = isset($submit_form_data['lacks_capacity_for_prp']) ? $submit_form_data['lacks_capacity_for_prp'] : '';
    $behavioral_control = isset($submit_form_data['behavioral_control']) ? $submit_form_data['behavioral_control'] : '';
    $organic_process_or_syndrome = isset($submit_form_data['organic_process_or_syndrome']) ? $submit_form_data['organic_process_or_syndrome'] : '';
    $eligible_disable_admin_service = isset($submit_form_data['eligible_disable_admin_service']) ? $submit_form_data['eligible_disable_admin_service'] : '';
    $support_considered = isset($submit_form_data['support_considered']) ? $submit_form_data['support_considered'] : '';
    $discharged = isset($submit_form_data['discharged']) ? $submit_form_data['discharged'] : '';
   
    $clientFirstName = isset($submit_form_data['client_first_name']) ? $submit_form_data['client_first_name'] : '';
    $clientLastName = isset($submit_form_data['client_last_name']) ? $submit_form_data['client_last_name'] : '';
    $clientBirthDate = isset($submit_form_data['client_birth_date']) ? $submit_form_data['client_birth_date'] : '';
    $clientSecurityNumber = isset($submit_form_data['clientSecurityNumber']) ? $submit_form_data['clientSecurityNumber'] : '';
    $diagnosis = isset($submit_form_data['diagnosis']) ? $submit_form_data['diagnosis'] : '';
    $minorAge = isset($submit_form_data['minor_age']) ? $submit_form_data['minor_age'] : '';
    $clientHomeless = isset($submit_form_data['client_homeless']) ? $submit_form_data['client_homeless'] : '';
    $disorder = isset($submit_form_data['disorder']) ? $submit_form_data['disorder'] : '';
    $communicableDiseases = isset($submit_form_data['communicable_diseases']) ? $submit_form_data['communicable_diseases'] : '';
    $medications = isset($submit_form_data['medications']) ? $submit_form_data['medications'] : '';
    $arrested = isset($submit_form_data['arrested']) ? $submit_form_data['arrested'] : '';
    $clientGender = isset($submit_form_data['client_gender']) ? $submit_form_data['client_gender'] : '';
    $clientAddress = isset($submit_form_data['client_address']) ? $submit_form_data['client_address'] : '';
    $clientGrade = isset($submit_form_data['client_grade']) ? $submit_form_data['client_grade'] : '';
    $employed = isset($submit_form_data['employed']) ? $submit_form_data['employed'] : '';
    $receivingTreatment = isset($submit_form_data['receiving_treatment']) ? $submit_form_data['receiving_treatment'] : '';
    $currentlyEnrolled = isset($submit_form_data['currently_enrolled']) ? $submit_form_data['currently_enrolled'] : '';
    $individualNature = isset($submit_form_data['individual_nature']) ? $submit_form_data['individual_nature'] : '';
    $individualIntensiveCare = isset($submit_form_data['individual_intensive_care']) ? $submit_form_data['individual_intensive_care'] : '';
    $individual_intensivelevel = isset($submit_form_data['individual_intensivelevel']) ? $submit_form_data['individual_intensivelevel'] : '';

    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Referral Form PDF');
    $pdf->SetMargins(10, 10, 10);
    
    $pdf->AddPage();
    // Add data as headings to PDF
    $pdf->SetFont('helvetica', 'B', 14); 
    
    $pdf->Cell(0, 10, $organizationName, 0, 1, 'C');
    $pdf->Cell(0, 10, $programName, 0, 1, 'C');
    $pdf->Cell(0, 10, $address, 0, 1, 'C');
    $pdf->Ln(10);
    
    $pdf->SetFont('helvetica', '', 10);
    
    // form one
    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'Referring Professional Information: ', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Referral date: ' . $refDate, 0, 1);
    $pdf->MultiCell(0, 10, "Is this is an initial referral or a concurrent referral? " . $referral_type, 0, 1); 
    $pdf->MultiCell(0, 10, "Referring Clinician First Name: " . $ref_first_name, 0, 'L');

    $pdf->MultiCell(0, 10, "Referring Clinician Last Name: " . $ref_last_name, 0, 'L');
    $pdf->MultiCell(0, 10, "Credentials: " . $credentials, 0, 'L');
    $pdf->MultiCell(0, 10, "Affiliated Referring Organization: " . $affiliated_ref_organization, 0, 'L');
    $pdf->MultiCell(0, 10, "Referring Clinician Phone No: " . $ref_clinician_phone, 0, 'L');
    $pdf->MultiCell(0, 10, "Referring Clinician Email Address: " . $ref_clinician_email, 0, 'L');
    $pdf->MultiCell(0, 10, "Referring Agency or Clinician NPI: " . $ref_npi, 0, 'L');
    $pdf->MultiCell(0, 10, "In addition to psychiatric rehabilitation program (PRP) and behavioral health home (BHH) services, what other services would the client need? (check all that apply): : " . $services1. $services2. $services3. $services4. $services5. $services6, 0, 'L');

    $pdf->Ln(1);

    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'CLIENT DEMOGRAPHICS: ', 0, 1);
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, "Client's name: " . $clientFirstName. ' '.$clientLastName, 0, 1);
    $pdf->Cell(0, 10, "Client's date of birth: " . $clientBirthDate, 0, 1);
    $pdf->Cell(0, 10, "Client's gender: " . $clientGender, 0, 1);
    // $pdf->Cell(0, 10, "Client's social security number:" . $clientSecurityNumber, 0, 1);
    $pdf->Cell(0, 10, "Client's address: " . $clientAddress, 0, 1);
    $pdf->MultiCell(0, 10, "Priority population diagnosis: " . $diagnosis, 0, 'L');
    $pdf->MultiCell(0, 10, "Is the client considered a minor (under 18 years of age)? " . strtoupper($minorAge), 0, 'L');

    ////
    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'MEDICAL NECESSITY CRITERIA HISTORY QUESTIONNAIRE:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, "1. Is the client homeless? " . strtoupper($clientHomeless), 0, 1);
    $pdf->MultiCell(0, 10, "2. Is the Primary reason for the individual' impairment due to an organic process or syndrome intellectual disability, a
    neurodevelopment disorder or neurocoginitive disorder? " . strtoupper($disorder), 0, 'L');
    $pdf->MultiCell(0, 10, "3. To the best of your knowledge does the client suffer from any communicable diseases (HIV, Hepatitis, TB, MMRV, etc.)? " . strtoupper($communicableDiseases), 0, 'L');
    $pdf->Cell(0, 10, "4. Is the client on any medications to include psychotropic and somatic medications? " . strtoupper($medications), 0, 1);

    // show medication if active 

    if(count($getMedications)> 0){
        foreach($getMedications as $key => $eachMedication){
            $pdf->MultiCell(0, 10,'Medications-'.($key+1).': '. $eachMedication['name'].', '.$eachMedication['dosage'].', '.$eachMedication['frequency'], 0, 'L');

        }
    }
    $pdf->Cell(0, 10, "5. Has the client within the past year been discharged from an inpatient psychiatric facility or hospital? " . strtoupper($discharged), 0, 1);
    $pdf->Cell(0, 10, "6. Has the client been arrested in the past six months? " . strtoupper($arrested), 0, 1);
    $pdf->Cell(0, 10, "7. What is the highest grade of school completed by the client? " . $diagnosis, 0, 1);
    $pdf->Cell(0, 10, "8. Is the client currently employed? " . strtoupper($employed), 0, 1);
    $pdf->Cell(0, 10, "9. Is the client currently receiving mental health treatment or psychotherapy from a therapist or psychiatrist? " . strtoupper($receivingTreatment), 0, 1);
    $pdf->Cell(0, 10, "10. Is the individual currently enrolled in SSI or SSDI? " . strtoupper($currentlyEnrolled), 0, 1);
    $pdf->MultiCell(0, 10, "11. Does the nature of the individual’s functional impairments and/or skill deficits can be effectively remediated through specific, focused skills-training activities designed to develop and restore (and maintain) independent living skills to support the individual’s
    recovery? " . strtoupper($individualNature), 0, 'L');
    $pdf->Cell(0, 10, "12. Does the Individual require a more intensive level of care? " . strtoupper($individualIntensiveCare), 0, 'L');
    $pdf->MultiCell(0, 10, "13. Have all less intensive levels of treatment have been determined to be unsafe or unsuccessful? " . strtoupper($individual_intensivelevel), 0, 'L');
    $pdf->MultiCell(0, 10, "14. Have peer or natural support alternatives been considered or attempted, and/or are insufficient to meet the need for specific, focused skills training to function effectively? " . strtoupper($support_considered), 0, 'L');
    $pdf->MultiCell(0, 10, "15. Participant is fully eligible for Developmental Disabilities Administration funded services? " . strtoupper($eligible_disable_admin_service), 0, 'L');
    $pdf->MultiCell(0, 10, "16. Primary reason for the participant’s impairment is due to an organic process or syndrome, intellectual disability, a neurodevelopmental disorder, or neurocognitive disorder? " . strtoupper($organic_process_or_syndrome), 0, 'L');
    $pdf->MultiCell(0, 10, "17. The participant has been judged not to be in sufficient behavioral control to be safely or effectively served in PRP? " . strtoupper($behavioral_control), 0, 'L');
    $pdf->MultiCell(0, 10, "18. The participant lacks capacity to benefit from PRP as a result of the level of cognitive impairment, current mental status or developmental level which cannot be reasonably accommodated within the PRP?  " . strtoupper($lacks_capacity_for_prp), 0, 'L');
    $pdf->MultiCell(0, 10, "19. The referral source is in some way is paid by the PRP program or receives other benefit from PRP program?  " . strtoupper($referral_source_paid), 0, 'L');
    $pdf->MultiCell(0, 10, "20. Is the participant being referred from? " . $referral_source, 0, 'L');
    $pdf->MultiCell(0, 10, "21. Why is ongoing outpatient treatment not sufficient to address concerns? " . $reason_for_insufficient_treatment, 0, 'L');

    // Functional impairment questionnaire
    foreach($submitted_impairment_questionnaire as $key => $eachData){
        $saticText = Secret::saticText();
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 10, 'FUNCTIONAL IMPARIMENT DOMAIN #'.($key + 1).' ', 0, 1);
        $pdf->SetFont('helvetica', '', 10);

        $impairment_questionnaire_name = isset($eachData['impairment_questionnaire_name']) ? $eachData['impairment_questionnaire_name'] : '';
        $mental_health_diagnosis = isset($eachData['mental_health_diagnosis']) ? $eachData['mental_health_diagnosis'] : '';

        $symptom = isset($eachData['symptom']) ? $eachData['symptom'] : '';

        $Symptom_experience_date = isset($eachData['Symptom_experience_date']) ? $eachData['Symptom_experience_date'] : '';
        $client_issue = isset($eachData['client_issue']) ? $eachData['client_issue'] : '';
        $namely = isset($eachData['namely']) ? $eachData['namely'] : '';
        $specifically = isset($eachData['specifically']) ? $eachData['specifically'] : '';
        $client_need = isset($eachData['client_need']) ? $eachData['client_need'] : '';
        $intervention = isset($eachData['intervention']) ? $eachData['intervention'] : '';
        $intervention_specifically = isset($eachData['intervention_specifically']) ? $eachData['intervention_specifically'] : '';
        $specific_need_area = isset($eachData['specific_need_area']) ? $eachData['specific_need_area'] : '';
        $long_term_goal = isset($eachData['long_term_goal']) ? $eachData['long_term_goal'] : '';
        $short_term_goal = isset($eachData['short_term_goal']) ? $eachData['short_term_goal'] : '';

        $checkbox = '';
        $allCheckBox = array_merge($saticText['adultCheckobox']);
     
        if (isset($allCheckBox[$impairment_questionnaire_name])) {
            $checkbox = $allCheckBox[$impairment_questionnaire_name];
        } 

        $pdf->MultiCell(0, 10, $checkbox, 0, 'L');
        $pdf->MultiCell(0, 10, "Client's mental health diagnosis is: " . $mental_health_diagnosis, 0, 'L');
        $pdf->MultiCell(0, 10, "Which symptom of the above diagnosis impairs the client's functioning in this domain? " . $symptom, 0, 'L');
        $pdf->MultiCell(0, 10, "Client, has experienced Symptom of diagnosis since:" . $Symptom_experience_date, 0, 'L');
        $pdf->MultiCell(0, 10, "Client presents with issues regarding: " . $client_issue, 0, 'L');
        $pdf->MultiCell(0, 10, "Namely, the client's: " . $namely, 0, 'L');
        $pdf->MultiCell(0, 10, "Specifically: " . $specifically, 0, 'L');
        $pdf->MultiCell(0, 10, "Additional information on the client's need in this area: " . $client_need, 0, 'L');
        $pdf->MultiCell(0, 10, "The following intervention was implemented: " . $intervention, 0, 'L');
        $pdf->MultiCell(0, 10, "Specifically, an example for a planned intervention is to: " . $intervention_specifically, 0, 'L');
        $pdf->MultiCell(0, 10, "Additional information on services to address this specific need area: " . $specific_need_area, 0, 'L');
        $pdf->MultiCell(0, 10, "Client's long term goal to address this impairment is: " . $long_term_goal, 0, 'L');
        $pdf->MultiCell(0, 10, "Client's short term goal to address this impairment is: " . $short_term_goal, 0, 'L');
    }
    $pdf->Output($filePath, 'F');

  
}

// Function to authenticate with Google Drive and upload the PDF
function uploadToDrive($filePath, $pdfFileName)
{
// unset($_SESSION['access_token']);

    $client = new Google_Client();
    // $client->setClientId('1043613198330-cf98b9hggt1of5a0tpf337eab2ph0o3l.apps.googleusercontent.com');
    // $client->setClientSecret('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');
    // $client->setRedirectUri('http://localhost/prp-referrals/functions/response.php');
    $client->setClientId('1043613198330-cf98b9hggt1of5a0tpf337eab2ph0o3l.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('https://prp-referrals.code4each.com/functions/response.php');
    $client->addScope(Google\Service\Drive::DRIVE_FILE);

   // If the access token is not set, obtain it by redirecting the user to the authorization URL
    if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] === null) {
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    }
    // $client->revokeToken();
    // Set the access token and create a Google Drive service instance
    $client->setAccessToken($_SESSION['access_token']);


// Check if the access token is expired and refresh if necessary
if ($client->isAccessTokenExpired()) {
    // Check if the refresh token is available
    $refreshToken = $client->getRefreshToken();
    // $refreshToken =  $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

    if (!$refreshToken) {
        throw new Exception("Refresh token is not available. Make sure you requested offline access during authorization.");
    }

    // Set the refresh token and fetch a new access token
    $client->setAccessToken($client->fetchAccessTokenWithRefreshToken($refreshToken));

}
    $driveService = new Google\Service\Drive($client);
    $client->setDefer(TRUE);
   // File metadata

    $fileMetadata = new Google\Service\Drive\DriveFile([
        'name' => $pdfFileName,
    ]);

$createdFile = $driveService->files->create($fileMetadata);
// Create a media file upload with the file's content
$media = new Google\Http\MediaFileUpload(
    $client,
    $createdFile,
    'application/pdf',
    null,
    true,
    filesize($filePath)
);
$media->setFileSize(filesize($filePath));

    // Upload the file in chunks
    $status = false;
    $handle = fopen($filePath, 'rb');
    while (!$status && !feof($handle)) {
        $chunk = fread($handle, 1024 * 1024); // 1MB chunk size
        $status = $media->nextChunk($chunk);
    }

    // Close the file handle
    fclose($handle);

    // Redirect to a success page
    // header('Location: http://localhost/prp-referrals/success.php');
}

// Create a temporary file path for the PDF
// $filePath = sys_get_temp_dir() . '/example.pdf';

// // if(isset($submit_form_data['clientIssueId'])){
//     // $submit_form_Id = $submit_form_data['submit_form_Id'];
//     $submit_form_Id = 21;

// // get data with id
// $submit_form_data =$db->query('SELECT * FROM submit_form_data WHERE id = ?' , $submit_form_Id )->fetchArray();

// $submitted_impairment_questionnaire =$db->query('SELECT *  FROM submitted_impairment_questionnaire LEFT JOIN submitted_impairment_questionnaire_data ON submitted_impairment_questionnaire_data.impairment_questionnaire_id = submitted_impairment_questionnaire.id WHERE submitted_impairment_questionnaire.submit_form_id = ?' , $submit_form_Id)->fetchAll();


// $getMedications =$db->query('SELECT * FROM medication_form_data WHERE submit_form_id = ?' , $submit_form_Id )->fetchAll();

// echo "<pre>"; print_r( $submit_form_data);   echo "<pre>";

//    // Create the PDF
//    createPDF($filePath, $submit_form_data, $submitted_impairment_questionnaire, $getMedications);

// // Upload the PDF to Google Drive
// $firstName = isset($submit_form_data['client_first_name']) ? $submit_form_data['client_first_name'] : '';
// $lastName = isset($submit_form_data['client_last_name']) ? $submit_form_data['client_last_name'] : '';
// $pdfFileName = 'PRP_referral_'.$firstName.'_'.$lastName.'_'.time().'.pdf';
// uploadToDrive($filePath, $pdfFileName);

// }

