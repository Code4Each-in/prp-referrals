<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering at the very beginning of the script
ob_start();
require_once('../tcpdf/tcpdf.php');
require_once __DIR__ . '/../vendor/autoload.php';
require_once  'databseConnection/db_connection.php';
require_once  'config.php';

//save first google user Credentials to save pdfs in that perticular account
function saveUserCredentials($credentials)
{
    $storedCredentialsPath = "credentials.json";
    $jsonCredentials = json_encode($credentials);

    if (!file_exists($storedCredentialsPath)) {
        if (!touch($storedCredentialsPath)) {
            die("Failed to create the file.");
        }
    }
    if (!chmod($storedCredentialsPath, 0666)) {
        die("Failed to set file permissions.");
    }
    $fileHandle = fopen($storedCredentialsPath, 'w');

    if ($fileHandle === false) {
        die("Error opening the file for writing.");
    }
    $bytesWritten = fwrite($fileHandle, $jsonCredentials);

    if ($bytesWritten === false) {
        die("Error writing data to the file.");
    }

    fclose($fileHandle);
    return true;
}


// Load user credentials from a file
function getUserCredentials()
{
    $storedCredentialsPath = "credentials.json";
    if (file_exists($storedCredentialsPath)) {
        return json_decode(file_get_contents($storedCredentialsPath), true);
    }
    return null;
}

// Function to create a simple PDF
function createPDF($filePath, $submit_form_data, $submitted_impairment_questionnaire, $getMedications)
{
    $saticText = Secret::saticText();
    $checkboxMinor = $saticText['minorCheckobox'];
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

    $services1 = isset($submit_form_data['services1']) ? $submit_form_data['services1'] . ', ' : '';
    $services2 = isset($submit_form_data['services2']) ? $submit_form_data['services2'] . ', ' : '';
    $services3 = isset($submit_form_data['services3']) ? $submit_form_data['services3'] . ', ' : '';
    $services4 = isset($submit_form_data['services4']) ? $submit_form_data['services4'] . ', ' : '';
    $services5 = isset($submit_form_data['services5']) ? $submit_form_data['services5'] . ', ' : '';
    $services6 = isset($submit_form_data['services6']) ? $submit_form_data['services6'] . ', ' : '';

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

    // $pdf = new TCPDF();
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
    $pdf->Cell(0, 5, 'Referral date: ', 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $refDate, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Is this is an initial referral or a concurrent referral? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    // $pdf->SetLeftMargin(12);
    $pdf->MultiCell(0, 10, $referral_type, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Referring Clinician First Name: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $ref_first_name, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);

    $pdf->MultiCell(0, 5, "Referring Clinician Last Name: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $ref_last_name, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Credentials: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $credentials, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Affiliated Referring Organization: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $affiliated_ref_organization, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Referring Clinician Phone No: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $ref_clinician_phone, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Referring Clinician Email Address: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $ref_clinician_email, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Referring Agency or Clinician NPI: ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $ref_npi, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "In addition to psychiatric rehabilitation program (PRP) and behavioral health home (BHH) services, what other services would the client need? (check all that apply): ", 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $services1 . $services2 . $services3 . $services4 . $services5 . $services6, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);

    $pdf->Ln(1);

    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 5, 'CLIENT DEMOGRAPHICS: ', 0, 1);
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "Client's name: ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $clientFirstName . ' ' . $clientLastName, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "Client's date of birth: ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $clientBirthDate, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "Client's gender: ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $clientGender, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "Client's address: ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $clientAddress, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Priority population diagnosis: ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $diagnosis, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "Is the client considered a minor (under 18 years of age)? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($minorAge), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    if ($minorAge === 'no') {
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 10, 'PRESUMPTIVE ELIGIBILITY SUMMARY:', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 10, $clientFirstName . ' ' . $clientLastName . ' presumptively eligible for PRP services, as a result of being diagnosed with a category A diagnosis (' . $diagnosis . ') and demonstrates impaired role functioning on a continued or intermittent basis in at least 3 of the 7 function categories.', 0, 'L');
        $pdf->Cell(0, 10, 'AND', 0, 1);
        $pdf->MultiCell(0, 20, "The nature of the " . $clientFirstName . " " . $clientLastName . "'s functional impairments and/or skill deficits can be effectively remediated through specific, focused skills-training activities designed to develop and restore (and maintain) independent living skills to support the individual's recovery.", 0, 'L');
        $pdf->MultiCell(0, 10, $clientFirstName . ' ' . $clientLastName . ' is concurrently engaged in outpatient mental health treatment.', 0, 'L');
        $pdf->MultiCell(0, 10, $clientFirstName . ' ' . $clientLastName . ' not require a more intensive level of care.', 0, 'L');
        $pdf->MultiCell(0, 10, 'All less intensive levels of treatment have been determined to be unsafe or unsuccessful for ' . $clientFirstName . ' ' . $clientLastName, 0, 'L');
        $pdf->MultiCell(0, 10, 'Peer or natural support alternatives have been considered or attempted, and/or are insufficient to meet the need for specific, focused skills training to function effectively for ' . $clientFirstName . ' ' . $clientLastName, 0, 'L');
    }
    //
    $pdf->SetFont('helvetica', 'B', 13);
    $pdf->Cell(0, 10, 'MEDICAL NECESSITY CRITERIA HISTORY QUESTIONNAIRE:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "1. Is the client homeless? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($clientHomeless), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "2. Is the Primary reason for the individual' impairment due to an organic process or syndrome intellectual disability, a
    neurodevelopment disorder or neurocoginitive disorder? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($disorder), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "3. To the best of your knowledge does the client suffer from any communicable diseases (HIV, Hepatitis, TB, MMRV, etc.)? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($communicableDiseases), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "4. Is the client on any medications to include psychotropic and somatic medications? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($medications), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);

    // show medication if active 

    if (count($getMedications) > 0) {
        foreach ($getMedications as $key => $eachMedication) {
            $pdf->MultiCell(0, 5, 'Medications-' . ($key + 1) . ': ', 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->MultiCell(0, 10, $eachMedication['name'] . ', ' . $eachMedication['dosage'] . ', ' . $eachMedication['frequency'], 0, 'L');
            $pdf->SetFont('helvetica', '', 10);
        }
    }
    $pdf->Cell(0, 5, "5. Has the client within the past year been discharged from an inpatient psychiatric facility or hospital? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($discharged), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "6. Has the client been arrested in the past six months? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($arrested), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "7. What is the highest grade of school completed by the client? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, $diagnosis, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "8. Is the client currently employed? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($employed), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "9. Is the client currently receiving mental health treatment or psychotherapy from a therapist or psychiatrist? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($receivingTreatment), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "10. Is the individual currently enrolled in SSI or SSDI? ", 0, 1);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($currentlyEnrolled), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "11. Does the nature of the individual’s functional impairments and/or skill deficits can be effectively remediated through specific, focused skills-training activities designed to develop and restore (and maintain) independent living skills to support the individual’s
    recovery? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($individualNature), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, "12. Does the Individual require a more intensive level of care? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($individualIntensiveCare), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "13. Have all less intensive levels of treatment have been determined to be unsafe or unsuccessful? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($individual_intensivelevel), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "14. Have peer or natural support alternatives been considered or attempted, and/or are insufficient to meet the need for specific, focused skills training to function effectively? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($support_considered), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "15. Participant is fully eligible for Developmental Disabilities Administration funded services? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($eligible_disable_admin_service), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "16. Primary reason for the participant’s impairment is due to an organic process or syndrome, intellectual disability, a neurodevelopmental disorder, or neurocognitive disorder? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($organic_process_or_syndrome), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "17. The participant has been judged not to be in sufficient behavioral control to be safely or effectively served in PRP? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($behavioral_control), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "18. The participant lacks capacity to benefit from PRP as a result of the level of cognitive impairment, current mental status or developmental level which cannot be reasonably accommodated within the PRP?  ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($lacks_capacity_for_prp), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "19. The referral source is in some way is paid by the PRP program or receives other benefit from PRP program?  ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($referral_source_paid), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "20. Is the participant being referred from? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($referral_source), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "21. Why is ongoing outpatient treatment not sufficient to address concerns? ", 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 10, strtoupper($reason_for_insufficient_treatment), 0, 'L');
    $pdf->SetFont('helvetica', '', 10);

    // Functional impairment questionnaire
    if ($minorAge === 'no') {
        foreach ($submitted_impairment_questionnaire as $key => $eachData) {
            $pdf->SetFont('helvetica', 'B', 13);
            $pdf->Cell(0, 10, 'FUNCTIONAL IMPARIMENT DOMAIN #' . ($key + 1) . ' ', 0, 1);
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
            $pdf->MultiCell(0, 20, $clientFirstName . " " . $clientLastName . " has experienced " . $symptom . ". which are symptoms of " . $mental_health_diagnosis . " since " . $Symptom_experience_date . ".", 0, 'L');

            $pdf->MultiCell(0, 20, $clientFirstName . " " . $clientLastName . " presents with issues regarding " . $client_issue . " Namely, the client's " . $namely . ". " . $specifically . ".." . $client_need, 0, 'L');

            $pdf->MultiCell(0, 30, "To help " . $clientFirstName . " with this " . $client_issue . " problem, the PRP would assist " . $clientFirstName . " to " . $intervention . " An example of a planned intervention is " . $intervention_specifically . ".. " . $specific_need_area . "..", 0, 'L');

            $pdf->MultiCell(0, 20, $clientFirstName . "'s long term goal to address this impairment is  " . $long_term_goal . ".", 0, 'L');

            $pdf->MultiCell(0, 20, $clientFirstName . "'s short term goal to address this impairment is  " . $short_term_goal . ".", 0, 'L');
        }
    } else {
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 10, 'Minor', 0, 1);
        $pdf->SetFont('helvetica', '', 10);
        if (isset($submit_form_data['minor1'])) {
            if (isset($checkboxMinor['minor1'])) {
                $pdf->MultiCell(0, 5, $checkboxMinor['minor3'], 0, 'L');
                $pdf->SetFont('helvetica', 'B', 10);
                if ($submit_form_data['minor1'] != 'no') {
                    $pdf->MultiCell(0, 5, 'Yes', 0, 'L');
                }
                $pdf->MultiCell(0, 10, $submit_form_data['minor1'], 0, 'L');
                $pdf->SetFont('helvetica', '', 10);
            }
        }
        if (isset($submit_form_data['minor2'])) {

            if (isset($checkboxMinor['minor2'])) {
                $pdf->MultiCell(0, 5, $checkboxMinor['minor2'], 0, 'L');
                $pdf->SetFont('helvetica', 'B', 10);
                if ($submit_form_data['minor2'] != 'no') {
                    $pdf->MultiCell(0, 5, 'Yes', 0, 'L');
                }
                $pdf->MultiCell(0, 20, $submit_form_data['minor2'], 0, 'L');

                $pdf->SetFont('helvetica', '', 10);

                $pdf->Ln(1);
            }
        }
        if (isset($submit_form_data['minor3'])) {
            if (isset($checkboxMinor['minor3'])) {
                $pdf->MultiCell(0, 5, $checkboxMinor['minor3'], 0, 'L');
                $pdf->SetFont('helvetica', 'B', 10);
                if ($submit_form_data['minor3'] != 'no') {
                    $pdf->MultiCell(0, 5, 'Yes', 0, 'L');
                }
                $pdf->MultiCell(0, 20, $submit_form_data['minor3'], 0, 'L');

                $pdf->SetFont('helvetica', '', 10);
            }
        }
        // minor questions 
        $minor_question_1 = isset($submit_form_data['minor_question_1']) ? $submit_form_data['minor_question_1'] : '';
        $minor_question_2 = isset($submit_form_data['minor_question_2']) ? $submit_form_data['minor_question_2'] : '';
        $minor_question_3 = isset($submit_form_data['minor_question_3']) ? $submit_form_data['minor_question_3'] : '';
        $minor_question_4 = isset($submit_form_data['minor_question_4']) ? $submit_form_data['minor_question_4'] : '';
        $minor_question_5 = isset($submit_form_data['minor_question_5']) ? $submit_form_data['minor_question_5'] : '';
        $minor_1_addtional = isset($submit_form_data['minor_1_addtional']) ? $submit_form_data['minor_1_addtional'] : '';
        $minor_2_addtional = isset($submit_form_data['minor_2_addtional']) ? $submit_form_data['minor_2_addtional'] : '';
        $minor_3_addtional = isset($submit_form_data['minor_3_addtional']) ? $submit_form_data['minor_3_addtional'] : '';
        $minor_4_addtional = isset($submit_form_data['minor_4_addtional']) ? $submit_form_data['minor_4_addtional'] : '';
        $minor_5_addtional = isset($submit_form_data['minor_5_addtional']) ? $submit_form_data['minor_5_addtional'] : '';
        $minor_question_3_yes = isset($submit_form_data['minor_question_3_yes']) ? $submit_form_data['minor_question_3_yes'] : '';
        
        if($minor_question_1 != '' || $minor_1_addtional != ''){
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 6,'What evidence exists to show that the current intensity of outpatient treatment for this individual is insufficient to reduce the youth’s symptoms and functional behavioral impairments resulting from mental illness?', 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->MultiCell(0, 6,$minor_question_1, 0, 'L');
            $pdf->MultiCell(0, 10,$minor_1_addtional, 0, 'L');
        }
        if($minor_question_2 != '' || $minor_2_addtional != ''){
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 6,'How will PRP serve to help this youth to age-appropriate development, more independent functioning and independent living skills?', 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->MultiCell(0, 6,$minor_question_2, 0, 'L');
            $pdf->MultiCell(0, 10,$minor_2_addtional, 0, 'L');
        }
        if($minor_question_3 != '' || $minor_3_addtional != ''){
            $select = '';
           
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 6,'Has the youth made progress toward age appropriate development, more independent functioning and independent living skills?', 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->MultiCell(0, 6,$minor_question_3, 0, 'L');
            if(isset($submit_form_data['minor_question_3_yes'])){
                $select = $submit_form_data['minor_question_3_yes'];
                 $pdf->MultiCell(0, 6,$select, 0, 'L');
            }
            $pdf->MultiCell(0, 10,$minor_3_addtional, 0, 'L');
        }
        if($minor_question_4 != '' || $minor_4_addtional != ''){
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 6,'Is a documented crisis response plan in progress or completed?', 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->MultiCell(0, 6,$minor_question_4, 0, 'L');
            $pdf->MultiCell(0, 10,$minor_4_addtional, 0, 'L');
        }
        if($minor_question_5 != '' || $minor_5_addtional != ''){
            $pdf->SetFont('helvetica', '', 10);
            $pdf->MultiCell(0, 6,'As an individual treatment plan/individual rehabilitation plan been completed?', 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->MultiCell(0, 6,$minor_question_5, 0, 'L');
            $pdf->MultiCell(0, 10,$minor_5_addtional, 0, 'L');
        }
    }
    
    // show service
    $pdf->Ln(3);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->MultiCell(0, 5, 'In addition to PRP and BHH services, client needs the following services:', 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 10, $services1 . $services2 . $services3 . $services4 . $services5 . $services6, 0, 'L');

    //signed by
    $pdf->Ln(5);
    $pdf->MultiCell(0, 7, 'Referring provider:', 0, 'L');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 5, 'ELECTRONICALLY SIGNED BY:', 0, 'L');
    $pdf->SetFont('helvetica', ' ', 10);
    $pdf->Cell(0, 5,  $ref_first_name . " " . $ref_last_name . ', CRNP-PMH of Changing Lives at Home, Inc. on ' . $refDate, 0, 'L');
    ob_end_clean();

    $pdf->Output($filePath, 'F');
}

// Function to authenticate with Google Drive and upload the PDF
function uploadToDrive($filePath, $pdfFileName)
{
    $client = new Google_Client();
    $googelData = Secret::getDriveKey();
    // $client->setClientId('1043613198330-cf98b9hggt1of5a0tpf337eab2ph0o3l.apps.googleusercontent.com');
    // $client->setClientSecret('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');
    // $client->setRedirectUri('https://prp-referrals.code4each.com/functions/response.php');
    $client->setClientId($googelData['clientId']);
    $client->setClientSecret($googelData['clientSecret']);
    $client->setRedirectUri($googelData['redirectUri']);

    $client->addScope(Google\Service\Drive::DRIVE_FILE);
    $client->setAccessType('offline');
    $client->setApprovalPrompt("force");
    $client->setIncludeGrantedScopes(true);
    $client->setPrompt('consent');

    $userCredentials = getUserCredentials();

    if ($userCredentials) {
        $client->setAccessToken($userCredentials);

        // Refresh the access token if it's expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken();
                saveUserCredentials($client->getAccessToken());
            } else {
                $authUrl = $client->createAuthUrl() . '&state=' . urlencode($_SERVER['REQUEST_URI']);
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit;
            }
        }
    } else {
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    }

    $driveService = new Google\Service\Drive($client);
    $client->setDefer(TRUE);
    // File metadata

    $fileMetadata = new Google\Service\Drive\DriveFile([
        'name' => $pdfFileName
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
}

// // Create a temporary file path for the PDF
// $filePath = sys_get_temp_dir() . '/example.pdf';

// // if(isset($submit_form_data['clientIssueId'])){
// $submit_form_Id = $submit_form_data['submit_form_Id'];
// $submit_form_Id = 135;

// //get data with id
// $submit_form_data = $db->query('SELECT * FROM submit_form_data WHERE id = ?', $submit_form_Id)->fetchArray();

// $submitted_impairment_questionnaire = $db->query('SELECT *  FROM submitted_impairment_questionnaire LEFT JOIN submitted_impairment_questionnaire_data ON submitted_impairment_questionnaire_data.impairment_questionnaire_id = submitted_impairment_questionnaire.id WHERE submitted_impairment_questionnaire.submit_form_id = ?', $submit_form_Id)->fetchAll();


// $getMedications = $db->query('SELECT * FROM medication_form_data WHERE submit_form_id = ?', $submit_form_Id)->fetchAll();

// // Create the PDF
// createPDF($filePath, $submit_form_data, $submitted_impairment_questionnaire, $getMedications);

// // Upload the PDF to Google Drive
// $firstName = isset($submit_form_data['client_first_name']) ? $submit_form_data['client_first_name'] : '';
// $lastName = isset($submit_form_data['client_last_name']) ? $submit_form_data['client_last_name'] : '';
// $pdfFileName = 'PRP_referral_' . $firstName . '_' . $lastName . '_' . time() . '.pdf';
// uploadToDrive($filePath, $pdfFileName);