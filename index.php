<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once  'functions/getSheetData.php';
require_once  'functions/config.php';
require_once  'functions/databseConnection/db_connection.php';
include  'functions/getMedicationData.php';
include  'functions/getDiagnosisSymptoms.php';

$googleSheetsReader = new GoogleSheetsReader();

// Get Clinicians Sheet data 
$getCliniciansSheetDetail = Secret::getCliniciansSheetDetail();
$data = $googleSheetsReader->readSheet($getCliniciansSheetDetail['sheetName'], $getCliniciansSheetDetail['keys']);

// Get client Sheet data 
$getClientSheetDetail = Secret::getClientSheetDetail();
$getClientSheetData = $googleSheetsReader->readSheet($getClientSheetDetail['sheetName'], $getClientSheetDetail['keys']);

// get Credentials 
$referringCredentials = Secret::referringCredentials();

// Get client Sheet data 
$saticText = Secret::saticText();
function showAlert($first, $second, $third)
{
    echo  '<div class="alert alert-danger d-flex align-items-center " role="alert">
    <div>
    <i class="bi-exclamation-octagon-fill"></i> Answering "' . $first . '" or "' . $second . '" to this question
      automatically disqualifies
      the individual from being eligible for PRP services. If the answer is
      "' . $first . '" or "' . $second . '", you will not be able to complete this form and the
      submission buttion will be disabled. If you answer "' . $first . '" or "' . $second . '" in
      error and intended to answer the question with "' . $third . '", simply correct
      your answer and move to the next question.
    </div>
  </div>';
}

function showAlertParticipant()
{
    echo  '<div class="alert alert-danger d-flex align-items-center " role="alert">
     <div>
     <i class="bi-exclamation-octagon-fill"></i> Answering "neither" to this question automatically disqualifies the individual from being eligible for PRP services.  If the answer is "neither", you will not be able to complete this form and the submission buttion will be disabled.  If you answered "neither" in error, simply correct your answer and move to the next question.
     </div>
   </div>';
}
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
        .hidden {
            display: none;
        }

        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            /* background-color: #007BFF;
            color: #fff; */
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
        }

        .commenHidden {
            display: none;

        }

        .submit-btn {
            position: sticky;
            bottom: 20px;
            /* background-color: #4CAF50; */
            /* color: white; */
            padding: 10px 20px;
            /* border: none; */
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-left: -20px;
            /* Offset to align with the form's left edge */
            z-index: 1;
            /* Ensure the button appears on top of the form */
            float: right;
            top: 20px;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <nav class="navbar navbar-light ">

                <a class="navbar-brand" href="#">
                    <img src="assets/images/clh-logo5.png" alt="" width="60" height="40">
                </a>
                <ul class="nav">

                    <li class="nav-item">
                        <p>Mental Health Provider Referral to the Changing Lives at Home </p>
                        <p>Psychiatric Rehabilitation Program (PRP)</p>
                    </li>

                </ul>
            </nav>
        </div>

    </header>
    <div class="container mt-5">

        <div class="row justify-content-center  position: relative;">
            <div class="col-md-8 mb-5">
                <form action="functions/submit_form.php" method="post" id="contactForm" novalidate>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Referring Professional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row row-radio">
                                <label class="col-sm-12 col-form-label">Is this is an initial referral or a concurrent referral?</label>
                                <div class="row row-jhk">
                                    <div class="col-sm-2 form-check mt-2">
                                        <label for="initial" class="col-sm-8 col-form-label">Initial Referral </label>
                                        <input class="form-check-input" type="radio" id="initial" name="referralType" value="initial">
                                    </div>
                                </div>
                                <div class="row row-jhk">
                                    <div class="col-sm-4 form-check mt-2">
                                        <label for="concurrent" class="col-sm-8 col-form-label">Concurrent Referral </label>
                                        <input class="form-check-input" type="radio" id="concurrent" name="referralType" value="concurrent">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="refDate" class="col-sm-4 col-form-label">Referral date:</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control form-control-sm" id="refDate" name="refDate">
                                </div>
                            </div>
                            <div class="form-group mb-3  row">
                                <div class="col">
                                    <label for="refFirstName" class="form-label">Referring Clinician First Name:
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" list="referring_name" id="refFirstName" name="refFirstName" required>
                                    <div class="invalid-feedback">Please enter the Referring Clinician First Name.
                                    </div>
                                    <datalist id="referring_name">
                                        <?php
                                        foreach ($data as $val) {
                                            echo '<option value="' . htmlspecialchars(trim($val['firstName']) . ' ' . trim($val['lastName'])) . '" />';
                                        }
                                        ?>
                                    </datalist>
                                </div>
                                <div class="col">
                                    <label for="refLastName" class="form-label">Referring Clinician Last Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="refLastName" name="refLastName" required>
                                    <div class="invalid-feedback">Please enter the Referring Clinician Last Name.
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="credentials" class="form-label">Credentials: <span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label="Default select example" name="credentials" id="credentials" required>
                                        <?php
                                        foreach ($referringCredentials as $eachVal) {
                                            echo '<option value="' . htmlspecialchars(trim($eachVal)) . '" >' . $eachVal . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Please enter the Credentials.</div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="affiliatedRefOrganization" class="col-sm-4 col-form-label">Affiliated
                                    Referring Organization:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="affiliatedRefOrganization" name="affiliatedRefOrganization" required>
                                    <div class="invalid-feedback">Please enter the Affiliated Referring Organization.</div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="refClinicianPhone" class="col-sm-4 col-form-label">Referring Clinician
                                    Phone
                                    No:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="refClinicianPhone" name="refClinicianPhone" required>
                                    <div class="invalid-feedback">Please enter Referring Clinician
                                        Phone
                                        No.</div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="refClinicianEmail" class="col-sm-4 col-form-label">Referring Clinician
                                    Email
                                    Address:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" id="refClinicianEmail" name="refClinicianEmail" required>
                                    <div class="invalid-feedback">Please enter Referring Clinician
                                        Email
                                        Address.</div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="refNpi" class="col-sm-4 col-form-label">Referring Agency or Clinician
                                    NPI:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="refNpi" name="refNpi">
                                </div>
                            </div>

                            <div class="mb-3 row row-radio">
                                <label class="col-sm-12 col-form-label">In addition to psychiatric rehabilitation
                                    program (PRP) and behavioral health home (BHH) services, what other services
                                    would
                                    the client need? (check all that apply): <span class="text-danger">*</span></label>

                                <div class="row">
                                    <label for="service1" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>

                                        Housing - assisted living
                                        services
                                        - 24/7 supervision</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input checkbox-group" type="checkbox" id="service1" name="services1" value="housing - assisted living services - 24/7 supervision">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service2" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i> Housing - supportive
                                        housing
                                        services - day time supervision</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input checkbox-group" type="checkbox" id="service2" name="services2" value="housing - supportive housing services - day time supervision">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service3" class="col-sm-8 col-form-label"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Psychiatry</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input checkbox-group" type="checkbox" id="service3" name="services3" value="psychiatry">
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="service4" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>Medication
                                        management</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input checkbox-group" type="checkbox" id="service4" name="services4" value="medication management">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service5" class="col-sm-8 col-form-label"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Mental health
                                        counseling</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input checkbox-group" type="checkbox" id="service5" name="services5" value="mental health counseling">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service6" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i> Primary care</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input checkbox-group" type="checkbox" id="service6" name="services6" value="primary care">
                                    </div>
                                </div>
                                <div class="invalid-feedback">Please select at least one service.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Client history questionnaire</h5>
                        </div>
                        <div class="card-body">

                            <div class="mb-3 row">
                                <label for="clientFirstName" class="col-sm-4 col-form-label">Client first
                                    name:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" list="clientName" id="clientFirstName" name="clientFirstName">
                                    <datalist id="clientName">
                                        <?php
                                        foreach ($getClientSheetData as $val) {
                                            echo '<option value="' . htmlspecialchars(trim($val['firstName']) . ' ' . trim($val['lastName'])) . '" />';
                                        }
                                        ?>
                                    </datalist>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="clientLastName" class="col-sm-4 col-form-label">Client last name:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="clientLastName" name="clientLastName">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="clientBirthDate" class="col-sm-4 col-form-label">Client date of
                                    birth:</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control form-control-sm" id="clientBirthDate" name="clientBirthDate">
                                </div>
                            </div>
                            <div class="mb-3 row row-radio">
                                <label class="col-sm-12 col-form-label">Client gender:</label>
                                <div class="row row-jhk">

                                    <div class="col-sm-2 form-check mt-2">
                                        <label for="gender1" class="col-sm-8 col-form-label">Male </label>
                                        <input class="form-check-input" type="radio" id="gender1" name="clientGender" value="Male">
                                    </div>
                                </div>
                                <div class="row row-jhk">
                                    <div class="col-sm-2 form-check mt-2">
                                        <label for="gender2" class="col-sm-8 col-form-label">Female </label>
                                        <input class="form-check-input" type="radio" id="gender2" name="clientGender" value="Female">
                                    </div>
                                </div>

                                <div class="row row-jhk">

                                    <div class="col-sm-6 form-check mt-2">
                                        <label for="gender3" class="col-sm-8 col-form-label">Female-to-Male
                                            (FTM)/Transgender
                                            Male/Trans Man</label>
                                        <input class="form-check-input" type="radio" id="gender3" name="clientGender" value="Female-to-Male (FTM)/Transgender Male/Trans Man">
                                    </div>
                                </div>
                                <div class="row row-jhk">

                                    <div class="col-sm-8 form-check mt-2">
                                        <label for="gender4" class="col-sm-8 col-form-label">Male-to-Female
                                            (MTF)/Transgender
                                            Female/Trans Woman</label>
                                        <input class="form-check-input" type="radio" id="gender4" name="clientGender" value="Male-to-Female (MTF)/Transgender Female/Trans Woman">
                                    </div>
                                </div>
                                <div class="row row-jhk">

                                    <div class="col-sm-6 form-check mt-2">
                                        <label for="gender5" class="col-sm-8 col-form-label">Genderqueer, neither
                                            exclusively
                                            male nor female</label>
                                        <input class="form-check-input" type="radio" id="gender5" name="clientGender" value="Genderqueer, neither exclusively male nor female">
                                    </div>
                                </div>
                                <div class="row row-jhk">

                                    <div class="col-sm-6 form-check mt-2">
                                        <label for="gender6" class="col-sm-8 col-form-label">Additional gender category
                                            or
                                            other, please specify</label>
                                        <input class="form-check-input" type="radio" id="gender6" name="clientGender" value="Additional gender category or other, please specify">
                                    </div>
                                </div>
                                <div class="row row-jhk">

                                    <div class="col-sm-12 form-check mt-2">
                                        <label for="gender7" class="col-sm-8 col-form-label">Choose not to
                                            disclose</label>
                                        <input class="form-check-input" type="radio" id="gender7" name="clientGender" value="Choose not to disclose">
                                    </div>
                                </div>

                            </div>


                            <div class="mb-3 row">
                                <label for="clientSecurityNumber" class="col-sm-4 col-form-label">Client date of social
                                    security number:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="clientSecurityNumber" name="clientSecurityNumber" required>
                                    <div class="invalid-feedback">Please enter the security number.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="clientAddress" class="col-sm-4 col-form-label">Client address:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="clientAddress" name="clientAddress" required>
                                    <div class="invalid-feedback">Please enter the client address.
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3 row row-age">
                                <label class="col-sm-8 col-form-label">Is the client considered a minor (under 18 years
                                    of age)?<span class="text-danger"> *</span></label>
                                <div class="col-sm-4 form-check">
                                    <div class="row row-cols">
                                        <div class="col-sm-4 form-check mt-2">
                                            <label for="minorYes" class="col-form-label">Yes</label>
                                            <input class="form-check-input mt-2" type="radio" id="minorYes" name="minorAge" value="yes" required>
                                        </div>
                                        <div class="col-sm-8 form-check mt-2">
                                            <label for="minorNo" class="col-form-label">No</label>
                                            <input class="form-check-input mt-2" type="radio" id="minorNo" name="minorAge" value="no">

                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback">Please choose one
                                </div>
                            </div>

                            <div class="listing">
                                <ol class="oreder-listing">
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Is the client homeless?<span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="clientHomelessYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="clientHomelessYes" name="clientHomeless" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-8 form-check mt-2">
                                                        <label for="clientHomelessNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="clientHomelessNo" name="clientHomeless" value="no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">Please select one option.</div>
                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Is the Primary reason for the individual'
                                                impairment due to an organic process or syndrome intellectual
                                                disability, a
                                                neurodevelopment disorder or neurocoginitive disorder?<span class="text-danger">
                                                    *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="disorderYes" class="    col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="disorderYes" name="disorder" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-8 form-check mt-2">
                                                        <label for="disorderNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="disorderNo" name="disorder" value="no">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label"> To the best of your knowledge does the
                                                client
                                                suffer from any communicable diseases (HIV, Hepatitis, TB, MMRV,
                                                etc.)?<span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="communicableDiseasesYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="communicableDiseasesYes" name="communicableDiseases" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="communicableDiseasesNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="communicableDiseasesNo" name="communicableDiseases" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="communicableDiseasesUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="communicableDiseasesUnknown" name="communicableDiseases" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Is the client on any medications to
                                                include
                                                psychotropic and somatic medications?<span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="medicationsYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsYes" name="medications" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="medicationsNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsNo" name="medications" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="medicationsUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsUnknown" name="medications" value="unknown">
                                                    </div>
                                                    <div class="col-sm-8 form-check mt-2">
                                                        <label for="medicationsNone" class="col-form-label">No Medications</label>
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsNone" name="medications" value="medicationsNone">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="medicationsYesRadioDiv" class="hidden">
                                            <label class="col-sm-8 col-form-label">Are any of the medications prescribed
                                                for
                                                MDD or
                                                Bipolar?</label>

                                            <div class="row row-cols">
                                                <div class="col-sm-4 form-check mt-2">
                                                    <label for="medicationsPrescribedYes" class="col-form-label">Yes</label>
                                                    <input class="form-check-input mt-2" type="radio" id="medicationsPrescribedYes" name="medicationsPrescribed" value="yes">
                                                </div>
                                                <div class="col-sm-8 form-check mt-2">
                                                    <label for="medicationsPrescribedNo" class="col-form-label">No</label>
                                                    <input class="form-check-input mt-2" type="radio" id="medicationsPrescribedNo" name="medicationsPrescribed" value="no">
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table hidden" id="medicationTable">
                                            <thead class="table-light">
                                                <tr>

                                                    <th scope="col">Medication name</th>
                                                    <th scope="col">Dosage</th>
                                                    <th scope="col">Frequency</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                for ($i = 0; $i <= 3; $i++) {
                                                ?>
                                                    <tr>
                                                        <td> <select class="form-select emptySelect" aria-label="Default select example" name="medication[<?php echo $i; ?>][name]" id="medicationName<?php echo $i; ?>">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($medicationName as $name) {
                                                                    echo '<option value="' . htmlspecialchars($name['name']) . '" data-medicationnameid = "' . $name['id'] . '">' . $name['name'] . '</option>';
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td> <select class="form-select emptySelect" aria-label="Default select example" name="medication[<?php echo $i; ?>][dosage]" id="medicationDosage<?php echo $i; ?>">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($medicationDosage as $dosage) {
                                                                    echo '<option value="' . htmlspecialchars($dosage['medication_dosage']) . '"   data-medicationdoseid = "' . $dosage['id'] . '">' . $dosage['medication_dosage'] . '</option>';
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td> <select class="form-select emptySelect" aria-label="Default select example" name="medication[<?php echo $i; ?>][frequency]" id="medicationFrequency<?php echo $i; ?>">
                                                                <option value=""></option>

                                                                <?php
                                                                foreach ($medicationFrequency as $frequency) {
                                                                    echo '<option value="' . htmlspecialchars($frequency['frequency']) . '" data-medicationfrequencyid = "' . $frequency['id'] . '" >' . $frequency['frequency'] . '</option>';
                                                                }
                                                                ?>
                                                            </select></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="mb-3 row hidden" id="medicationTreatmentDiv">
                                            <label for="medicationTreatment" class="col-sm-5 col-form-label">Why are
                                                medications not
                                                part of the treatment?
                                                <span class="text-danger"> *</span></label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" id="medicationTreatment" rows="3" name="medicationTreatment"><?php echo $saticText['medicationNoRadio']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="mb-3 row hidden" id="medicationsNoRadioDiv">
                                            <label for="medicationsNoRadio" class="col-sm-4 col-form-label">Please
                                                explain
                                                why the
                                                participant is not on medication:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" id="medicationsNoRadio" rows="3" name="medicationsNoRadio"><?php echo $saticText['medicationNoRadio']; ?></textarea>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Has the client within the past year been
                                                discharged from an inpatient psychiatric facility or hospital?<span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="dischargedYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="dischargedYes" name="discharged" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="dischargedNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="dischargedNo" name="discharged" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="dischargedUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="dischargedUnknown" name="discharged" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Has the client been arrested in the past
                                                six
                                                months?<span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="arrestedYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="arrestedYes" name="arrested" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="arrestedNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="arrestedNo" name="arrested" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="arrestedUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="arrestedUnknown" name="arrested" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p for="clientGrade" class="col-sm-8 col-form-label">What is the highest
                                                grade of
                                                school completed by the client?<span class="text-danger"> *</span></p>

                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="clientGrade" name="clientGrade" required>
                                                <div class="invalid-feedback">Please enter the School Grade.
                                                </div>

                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label"> Is the client currently employed? <span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="employedYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="employedYes" name="employed" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="employedNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="employedNo" name="employed" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="employedUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="employedUnknown" name="employed" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label"> Is the client currently receiving mental
                                                health treatment or psychotherapy from a therapist or psychiatrist?<span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="receivingTreatmentYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="receivingTreatmentYes" name="receivingTreatment" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-8 form-check mt-2">
                                                        <label for="receivingTreatmentNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="receivingTreatmentNo" name="receivingTreatment" value="no">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Is the individual currently enrolled in
                                                SSI
                                                or SSDI?<span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="currentlyEnrolledYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="currentlyEnrolledYes" name="currentlyEnrolled" value="yes" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="currentlyEnrolledNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="currentlyEnrolledNo" name="currentlyEnrolled" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="currentlyEnrolledUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="currentlyEnrolledUnknown" name="currentlyEnrolled" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Does the nature of the individual’s
                                                functional impairments and/or skill deficits can be effectively
                                                remediated through
                                                specific, focused skills-training activities designed to develop and
                                                restore (and
                                                maintain) independent living skills to support the individual’s
                                                recovery? <span class="text-danger"> *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="individualNatureYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualNatureYes" name="individualNature" value="yes" onchange="handleInputChange(this, 'yes')" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="individualNatureNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualNatureNo" name="individualNature" value="no" onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="individualNatureUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualNatureUnknown" name="individualNature" value="unknown" onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="commonClass commenHidden">
                                            <?php showAlert("no", "unknown", "yes"); ?>
                                        </div>

                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <label class="col-sm-8 col-form-label">Does the Individual require a more
                                                intensive
                                                level of care?<span class="text-danger"> *</span></label>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="individualIntensiveCareYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensiveCareYes" name="individualIntensiveCare" value="yes" required onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="individualIntensiveCareNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensiveCareNo" name="individualIntensiveCare" value="no" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="individualIntensiveCareUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensiveCareUnknown" name="individualIntensiveCare" value="unknown" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="individualIntensiveCareWarning commonClass commenHidden">
                                            <?php showAlert("yes", "unknown", "no"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Have all less intensive levels of
                                                treatment
                                                have been determined to be unsafe or unsuccessful?<span class="text-danger">
                                                    *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="individualIntensivelevelYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensivelevelYes" name="individualIntensivelevel" value="yes" required onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="individualIntensivelevelNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensivelevelNo" name="individualIntensivelevel" value="no" onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="individualIntensivelevelUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensivelevelUnknown" name="individualIntensivelevel" value="unknown" onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="hidden" id="intenseServicesDiv">
                                            <div class="mb-3 row row-radio">
                                                <label class="col-sm-12 col-form-label">Which of the following less intense services have been tried?</label>

                                                <div class="row">
                                                    <label for="intenseServices1" class="col-sm-8 col-form-label">
                                                        Individual and/or Group Therapy</label>
                                                    <div class="col-sm-2 form-check mt-2">
                                                        <input class="form-check-input checkbox-group" type="checkbox" id="intenseServices1" name="intenseServices[]" value="Individual and/or Group Therapy">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="intenseServices2" class="col-sm-8 col-form-label">
                                                        Targeted Case Management</label>
                                                    <div class="col-sm-2 form-check mt-2">
                                                        <input class="form-check-input checkbox-group" type="checkbox" id="intenseServices2" name="intenseServices[]" value="Targeted Case Management">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="individualIntensivelevelWarning commonClass commenHidden">
                                            <?php showAlert("no", "unknown", "yes"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Have peer or natural support alternatives
                                                been considered or attempted, and/or are insufficient to meet the need
                                                for specific,
                                                focused skills training to function effectively?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="supportConsideredYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="supportConsideredYes" name="supportConsidered" value="yes" required required onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="supportConsideredNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="supportConsideredNo" name="supportConsidered" value="no" onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="supportConsideredUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="supportConsideredUnknown" name="supportConsidered" value="unknown" onchange="handleInputChange(this, 'yes')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="supportConsideredWarning commonClass commenHidden">
                                            <?php showAlert("no", "unknown", "yes"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Participant is fully eligible for
                                                Developmental Disabilities Administration funded services?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="eligibleDisableAdminServiceYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="eligibleDisableAdminServiceYes" name="eligibleDisableAdminService" value="yes" required onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="eligibleDisableAdminServiceNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="eligibleDisableAdminServiceNo" name="eligibleDisableAdminService" value="no" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="eligibleDisableAdminServiceUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="eligibleDisableAdminServiceUnknown" name="eligibleDisableAdminService" value="unknown" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="eligibleDisableAdminServiceWarning commonClass commenHidden">
                                            <?php showAlert("yes", "unknown", "no"); ?>
                                        </div>
                                    </li>

                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Primary reason for the participant’s
                                                impairment is due to an organic process or syndrome, intellectual
                                                disability, a
                                                neurodevelopmental disorder, or neurocognitive disorder?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="organicProcessOrSyndromeYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="organicProcessOrSyndromeYes" name="organicProcessOrSyndrome" value="yes" required onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="organicProcessOrSyndromeNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="organicProcessOrSyndromeNo" name="organicProcessOrSyndrome" value="no" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="organicProcessOrSyndromeUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="organicProcessOrSyndromeUnknown" name="organicProcessOrSyndrome" value="unknown" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="organicProcessOrSyndromeWarning commonClass commenHidden">
                                            <?php showAlert("yes", "unknown", "no"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">The participant has been judged not to be
                                                in
                                                sufficient behavioral control to be safely or effectively served in PRP?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row   row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="behavioralControlYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="behavioralControlYes" name="behavioralControl" value="yes" required onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="behavioralControlNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="behavioralControlNo" name="behavioralControl" value="no" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="behavioralControlUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="behavioralControlUnknown" name="behavioralControl" value="unknown" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="behavioralControlWarning commonClass commenHidden">
                                            <?php showAlert("yes", "unknown", "no"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">The participant lacks capacity to benefit
                                                from PRP as a result of the level of cognitive impairment, current
                                                mental status or
                                                developmental level which cannot be reasonably accommodated within the
                                                PRP?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="lacksCapacityForPRPYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="lacksCapacityForPRPYes" name="lacksCapacityForPRP" value="yes" required onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="lacksCapacityForPRPNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="lacksCapacityForPRPNo" name="lacksCapacityForPRP" value="no" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="lacksCapacityForPRPUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="lacksCapacityForPRPUnknown" name="lacksCapacityForPRP" value="unknown" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lacksCapacityForPRPWarning commonClass commenHidden">
                                            <?php showAlert("yes", "unknown", "no"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">The referral source is in some way is
                                                paid by
                                                the PRP program or receives other benefit from PRP program?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="referralSourcePaidYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourcePaidYes" name="referralSourcePaid" value="yes" required onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="referralSourcePaidNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourcePaidNo" name="referralSourcePaid" value="no" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="referralSourcePaidUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourcePaidUnknown" name="referralSourcePaid" value="unknown" onchange="handleInputChange(this, 'no')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="referralSourcePaidWarning commonClass commenHidden">
                                            <?php showAlert("yes", "unknown", "no"); ?>
                                        </div>
                                    </li>
                                    <li class="outerRadio">
                                        <div class="row-list">
                                            <p class="col-sm-4 col-form-label">Is the participant being referred from:
                                            </p>
                                            <div class="col-sm-8 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-6 form-check mt-2">
                                                        <label for="referralSourceDigital" class="col-form-label">IP/Crisis Res/Mobile/ ACT/ RTC/ Incarceration</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourceDigital" name="referralSource" value="IP/Crisis Res/Mobile/ ACT/ RTC/ Incarceration" required>
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="referralSourceOutpatient" class="col-form-label">Outpatient</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourceOutpatient" name="referralSource" value="Outpatient">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="referralSourceNeither" class="col-form-label">Neither</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourceNeither" name="referralSource" value="neither">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="referralSourceWarning commonClass commenHidden">
                                            <?php showAlertParticipant(); ?>
                                        </div>
                                    </li>
                                    <li>
                                        <p for="clientAddress" class="col-sm-12 col-form-label">Why is ongoing
                                            outpatient
                                            treatment not sufficient to address concerns?
                                            <span class="text-danger"> *</span>
                                        </p>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" id="reasonForInsufficientTreatment" name="reasonForInsufficientTreatment" required><?php echo $saticText['ansOfQuestion21']; ?></textarea>
                                            <div class="invalid-feedback">Please enter the Value
                                            </div>
                                        </div>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>

                    <div class="card minorQuestionnaire hidden">
                        <div class="card-header">
                            <h5><span class="diagnosisHeadingSpan">Minor</span> Questionnaire</h5>
                        </div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Mental Health diagnosis (<span class="diagnosisHeadingSpan"> Adult</span>)</h5>
                                </div>
                                <div class="card-body">
                                    <label for="diagnosis" class="form-label">What is the client's primary mental health diagnosis? <span class="text-danger">*</span></label>
                                    <select class="form-select emptySelect" aria-label="Default select example" name="diagnosis" id="diagnosis" required>
                                    </select>
                                    <div class="invalid-feedback">Please enter the lient's primary mental health diagnosis.
                                    </div>

                                </div>

                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5>Functional impairment questionnaire (<span class="diagnosisHeadingSpan"> Adult</span>)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="card-body">

                                        <div class="mb-3 row row-radio">
                                            <label class="col-sm-12 col-form-label">To be considered evidence of impaired role functioning at least three of the following must have been present on a continuing or intermittent basis. If to your knowledge, the individual demonstrates impaired role functioning in the specified areas for at least two years, select the areas below. If not, stop, and do not complete this form. <span class="text-danger">*</span></label>
                                            <div id="checkboxWarning"> </div>

                                            <div id="checkboxContainer"> </div>

                                        </div>
                                        <div id="templateContainer"> </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
            </div>

        </div>
        <div class="mt-4">
        </div>
        <button type="submit" class="btn btn-primary submit-btn me-4" id="submitButton">Submit</button>

        <button type="button" class="btn btn-outline-primary submit-btn me-4" onclick="refreshPage()" id="resetButton">Reset</button>
        </form>

        <button id="scrollToTopBtn" class="btn btn-secondary"><i class="fa fa-arrow-up"></i></button>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        //Store sheet data 
        var sheetData = <?php echo json_encode($data); ?>;
        var clientSheetData = <?php echo json_encode($getClientSheetData); ?>;
        var diagnosisAdultArray = <?php echo json_encode($diagnosisAdultArray); ?>;
        var diagnosisMinorArray = <?php echo json_encode($diagnosisMinorArray); ?>;
        var adultCheckobox = <?php echo json_encode($saticText['adultCheckobox']); ?>;
        var minorCheckobox = <?php echo json_encode($saticText['minorCheckobox']); ?>;
        var clientIssue = <?php echo json_encode($clientIssue); ?>;
        var consumerInformation = <?php echo json_encode($consumerInformation); ?>;

        var submitButton = $('#submitButton');
        var diagnosisHeadingSpan = $('.diagnosisHeadingSpan');
        var firstOption = '<option value="">-Select-</option>';

        // Input elemets for Referring Professional Information form
        var refFirstName = $('#refFirstName');
        var refLastName = $('#refLastName');
        var affiliatedRefOrganization = $('#affiliatedRefOrganization');
        var refClinicianPhone = $('#refClinicianPhone');
        var refClinicianEmail = $('#refClinicianEmail');
        var refNpi = $('#refNpi');
        var credentials = $('#credentials');


        // Input elemets for Client history questionnaire form
        var clientFirstName = $('#clientFirstName');
        var clientLastName = $('#clientLastName');
        var clientBirthDate = $('#clientBirthDate');
        var clientSecurityNumber = $('#clientSecurityNumber');
        var clientAddress = $('#clientAddress');
        var clientGrade = $('#clientGrade');

        var templateContainer = $("#templateContainer");
        var diagnosisSelect = $("#diagnosis");
        var symtomsHtml = firstOption;
        var diagnosisVal = '';


        // Bootstrap form validation using Bootstrap 5
        (function() {
            'use strict';

            var form = document.getElementById('contactForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity() || !showFuntionalAlert()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Find the first invalid element and scroll to it
                    var invalidElement = findFirstInvalidElement();
                    if (invalidElement) {
                        invalidElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }


                form.classList.add('was-validated');

            }, false);

            // Show validation feedback on blur
            form.addEventListener('blur', function(event) {
                if (!event.target.checkValidity()) {
                    event.target.classList.add('is-invalid');
                } else {
                    event.target.classList.remove('is-invalid');
                }
                // }
            }, true);

            // Function to find the first invalid element in the form
            function findFirstInvalidElement() {
                var elements = form.elements;
                for (var i = 0; i < elements.length; i++) {
                    if (!elements[i].checkValidity()) {
                        return elements[i];
                    }
                }
                return null;
            }

            // check if one radio check for minor Functional impairment questionnaire
            function isAtLeastOneRadioCheckedMinorFunctionalImpairment() {
                let returnValue = false;
                var radioInputs = form.querySelectorAll('input[type="radio"].functionalImpairment');
                if (radioInputs.length === 0) {
                    returnValue = true;
                } else {
                    for (var i = 0; i < radioInputs.length; i++) {
                        if (radioInputs[i].checked) {
                            returnValue = true;

                        }
                    }
                }
                if (!returnValue) {
                    showFuntionalAlert();
                }

                return returnValue;

            }

        })();

        // on change first name of Referring Professional Information form 
        var refFirstName = document.getElementById('refFirstName');
        refFirstName.addEventListener('input', function() {
            var options = $('datalist')[0].options;

            var inputValue = $(this).val();
            for (var i = 0; i < options.length; i++) {
                if (options[i].value === inputValue) {
                    var matchingObjects = sheetData.filter(function(obj) {
                        var trimmedFirstName = obj.firstName.trim().toLowerCase();
                        var trimmedLastName = obj.lastName.trim().toLowerCase();

                        var fullName = (trimmedFirstName + ' ' + trimmedLastName);
                        var trimmedInputValue = inputValue.toLowerCase();

                        return fullName === trimmedInputValue;
                    });
                    if (matchingObjects.length > 0) {
                        var matchingObject = matchingObjects[0];
                        $(this).val(matchingObject.firstName)
                        refLastName.val(matchingObject.lastName);
                        affiliatedRefOrganization.val(matchingObject.organization);
                        refClinicianPhone.val(matchingObject.phone);
                        refClinicianEmail.val(matchingObject.email);
                        refNpi.val(matchingObject.agencyNpi);

                        // for checkbox
                        var valueToMatch = matchingObject.service.toLowerCase();

                        // Find the checkbox with the corresponding value
                        var checkboxToCheck = $('input[type="checkbox"][value="' + valueToMatch + '"]');

                        checkboxToCheck.prop('checked', true);

                        credentials.val(matchingObject.credentials);
                    } else {
                        emptyReferringProfessional();
                    }
                    break;
                } else {
                    emptyReferringProfessional();
                }
            }
        });

        // empty Referring Professional Information form 
        function emptyReferringProfessional() {
            refLastName.val('');
            affiliatedRefOrganization.val('');
            refClinicianPhone.val('');
            refClinicianEmail.val('');
            refNpi.val('');
            credentials.val('');
            $('input[name="services[]"]').prop('checked', false);
        }

        // on change first name of Client history questionnaire form 
        var clientFirstName = document.getElementById('clientFirstName');
        clientFirstName.addEventListener('input', function() {
            var clientNameOptions = $('#clientName option');
            var clientInputValue = $(this).val();
            for (var i = 0; i < clientNameOptions.length; i++) {
                if (clientNameOptions[i].value === clientInputValue) {
                    var matchingClientObjects = consumerInformation.filter(function(obj) {
                        var trimmedFirstName = obj.client_first_name.trim().toLowerCase();
                        var trimmedLastName = obj.client_last_name.trim().toLowerCase();
                        var fullName = (trimmedFirstName + ' ' + trimmedLastName);
                        var trimmedInputValue = clientInputValue.toLowerCase();
                        return fullName === trimmedInputValue;
                    });
                    if (matchingClientObjects.length > 0) {
                        var matchingClientObject = matchingClientObjects[0];
                        $(this).val(matchingClientObject.client_first_name)
                        clientLastName.val(matchingClientObject.client_last_name);
                        var userAge = calculateAge(matchingClientObject.client_dob);

                        //Set date 
                        clientBirthDate.val(matchingClientObject.client_dob);
                        //SET GENDER
                        if (matchingClientObject.client_gender != null) {

                            var cleintGenderToCheck = matchingClientObject.client_gender;

                            if (matchingClientObject.client_gender.toLowerCase().indexOf("minor") !== -1) {
                                cleintGenderToCheck = matchingClientObject.client_gender.replace(/\s*\(minor\)/i, '');
                            }
                            var radioToCheck = $('input[name="clientGender"][value="' + cleintGenderToCheck + '"]');

                            // Check the radio button if it exists
                            if (radioToCheck.length > 0) {
                                radioToCheck.prop('checked', true);
                            }

                            var selectedType = '';
                            if (userAge >= 18) {
                                $("#minorNo").prop("checked", true);
                                selectedType = 'no';
                            } else {
                                $("#minorYes").prop("checked", true);
                                selectedType = 'yes';
                            }
                            clientAgeOnchange(selectedType);
                        }

                        // SET SECURITY NUMBER
                        if (matchingClientObject.client_social_security_no && matchingClientObject
                            .client_social_security_no != "--") {
                            clientSecurityNumber.val(matchingClientObject.client_social_security_no);
                        } else {
                            clientSecurityNumber.val("");
                        }


                        // SET ADDRESS
                        if (matchingClientObject.client_address && matchingClientObject.client_address != null) {
                            clientAddress.val(matchingClientObject.client_address);
                        } else {
                            clientAddress.val("");
                        }

                        //SET CLIENT MINOR 
                        if (matchingClientObject.is_client_minor && matchingClientObject.is_client_minor != "") {
                            if (matchingClientObject.is_client_minor.toLowerCase() == "minor") {
                                $("#minorYes").prop('checked', true);
                            }
                            if (matchingClientObject.is_client_minor.toLowerCase() == "adult") {
                                $("#minorNo").prop('checked', true);
                            }
                        }
                        // SET ADDRESS
                        if (matchingClientObject.client_highest_grade && matchingClientObject.client_highest_grade != null) {
                            clientGrade.val(matchingClientObject.client_highest_grade);
                        } else {
                            clientGrade.val("");
                        }

                        clientGrade.val(matchingClientObject.client_highest_grade);

                        // set medication 
                        if (matchingClientObject.current_medication && matchingClientObject.current_medication != "") {
                            if (matchingClientObject.current_medication.toLowerCase() == "yes") {
                                $("#medicationsYes").prop('checked', true);
                                $("#medicationsPrescribedYes").prop('checked', true);
                                $('#medicationsYesRadioDiv').removeClass('hidden');
                                $('#medicationTable').removeClass('hidden');

                                getClientMedication(matchingClientObject.id);

                            } else if (matchingClientObject.current_medication.toLowerCase() == "no active medications") {
                                $("#medicationsNone").prop('checked', true);
                            }
                        }
                        $('#diagnosis option[data-diagnosisid="' + matchingClientObject.diagnosis_id + '"]').prop('selected', true);
                    } else {
                        emptyClientForm();
                    }
                    break;
                } else {
                    emptyClientForm();
                }
            }
        });

        // emptyClient history questionnaire form 
        function emptyClientForm() {
            clientLastName.val('');
            clientBirthDate.val('');
            clientSecurityNumber.val('');
            clientAddress.val('');
            clientGrade.val('');
            $('input[name="clientGender"]').prop('checked', false);
            $('input[name="medications"]').prop('checked', false);
            $('input[name="minorAge"]').prop('checked', false);
            $('select.emptySelect').val('');
            $('input[name="medicationsPrescribed"]').prop('checked', false);
        }

        // change date format
        function convertDateFormat(dateString) {
            var parts = dateString.split("/");
            var formattedDate = parts[2] + "-" + parts[0].padStart(2, '0') + "-" + parts[1].padStart(2, '0');
            return formattedDate;
        }

        //Calculate age
        function calculateAge(dateString) {
            // Parse the date string to a Date object
            var birthdateObject = new Date(dateString);

            // Get the current date
            var currentDate = new Date();

            // Calculate the difference in years
            var age = currentDate.getFullYear() - birthdateObject.getFullYear();

            // Check if the birthday has occurred this year
            if (currentDate.getMonth() < birthdateObject.getMonth() ||
                (currentDate.getMonth() === birthdateObject.getMonth() && currentDate.getDate() < birthdateObject.getDate())) {
                age--;
            }
            return age;
        }


        //on medication radio change 
        $(document).ready(function() {

            $('[name="individualIntensivelevel"]').change(function() {
                var value = $(this).val();
                if (value === 'yes') {
                    $('#intenseServicesDiv').removeClass('hidden');
                } else {
                    $('#intenseServicesDiv').addClass('hidden');
                }
            });

            $('[name="medications"]').change(function() {
                var value = $(this).val();
                if (value === 'yes') {
                    $('#medicationsYesRadioDiv').removeClass('hidden');
                    $('#medicationsNoRadioDiv').addClass('hidden');
                } else if (value === 'no') {
                    $('#medicationsNoRadioDiv').removeClass('hidden');
                    $('#medicationsYesRadioDiv').addClass('hidden');
                    $('#medicationTreatmentDiv').addClass('hidden');
                    $('#medicationTable').addClass('hidden');
                    $('input[name="medicationsPrescribed"]').prop('checked', false);
                } else if (value === 'unknown' || value === 'medicationsNone') {
                    $('#medicationsYesRadioDiv').addClass('hidden');
                    $('#medicationsNoRadioDiv').addClass('hidden');
                    $('#medicationTreatmentDiv').addClass('hidden');
                    $('#medicationTable').addClass('hidden');
                    $('input[name="medicationsPrescribed"]').prop('checked', false);
                }
            });

            //on medication radio change 
            $('[name="medicationsPrescribed"]').change(function() {
                var value = $(this).val();
                if (value === 'yes') {
                    $('#medicationTable').removeClass('hidden');
                    $('#medicationTreatmentDiv').addClass('hidden');
                } else if (value === 'no') {
                    $('#medicationTreatmentDiv').removeClass('hidden');
                    $('#medicationTable').addClass('hidden');
                }
            });


            //on minor age change radio
            $('[name="minorAge"]').change(function() {
                var selectedType = $(this).val();
                showFuntionalAlert();
                clientAgeOnchange(selectedType);
            });


            // on change functional Impairment
            $(document).on("change", '.functionalImpairment', function() {
                showFuntionalAlert();
            });

            // On diagnosis selectbox change
            $('#diagnosis').change(function() {
                var selectedValue = $(this).val();
                $('.mentalDiagnosis').val(selectedValue);
                var selectedOption = $(this).find(':selected');
                var diagnosisid = selectedOption.data('diagnosisid');
                $.ajax({
                    url: 'functions/getSymptoms.php',
                    type: 'POST',
                    data: {
                        diagnosisid: diagnosisid
                    },
                    success: function(response) {
                        var responseData = JSON.parse(response);
                        $('.symptom').empty();
                        $('.symptom').append(firstOption);
                        $.each(responseData, function(index, option) {
                            symtomsHtml += '<option value="' + option.name + '" data-symptom="' + option.id + '">' + option.name + '</option> ';
                            $('.symptom').append('<option value="' + option.name + '" data-symptom="' + option.id + '">' + option.name + '</option> ');
                        });

                    },
                    error: function() {
                        $('#result').html('Error loading data.');
                    }
                });
            });


            // srcoll window at first error 
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#scrollToTopBtn').fadeIn();
                } else {
                    $('#scrollToTopBtn').fadeOut();
                }
            });

            // Scroll to the top when the button is clicked
            $('#scrollToTopBtn').click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 300);
            });
        });


        // to go on top button js
        window.onscroll = function() {
            var submitButton = document.querySelector('.submit-btn');
            var form = document.querySelector('form');

            if (window.pageYOffset > form.offsetTop + form.offsetHeight) {
                submitButton.style.position = 'fixed';
            } else {
                submitButton.style.position = 'sticky';
            }
        };


        // refral form input change
        function handleInputChange(element, removeClassRadio) {
            var value = element.value;
            var hiddenElement = $(element).closest('.outerRadio').find('.commonClass');

            if (value != removeClassRadio) {
                hiddenElement.removeClass('commenHidden');
                hiddenElement.addClass('showingWorning');
            } else {
                hiddenElement.addClass('commenHidden');
                hiddenElement.removeClass('showingWorning');
            }


            if ($('body:has(.showingWorning)').length > 0) {
                submitButton.prop('disabled', true);
            } else {
                submitButton.prop('disabled', false);
            }
        }

        // Functional impairment questionnaire check box change (this is only for adult)
        $(document).on("change", 'input[name="functionalImpairment"]', function() {
            var selectedCheckboxValues = $('input[name="functionalImpairment"]:checked').map(function() {
                return this.value;
            }).get();

            var selectedCheckboxId = this.id

            var templateContainer = $("#templateContainer");

            // Remove templates that are unchecked
            templateContainer.find('.template').filter(function() {
                return !selectedCheckboxValues.includes($(this).data('templateid'));
            }).remove();

            var minorAge = $('input[name="minorAge"]:checked');
            var minorAgeValue = minorAge.val();

            if (minorAgeValue === 'yes') {
                templateContainer.empty();
                var selectedCheckboxIdArray = selectedCheckboxId.split('-');
                var minorValue = selectedCheckboxIdArray[1];
            } else {
                diagnosisVal = diagnosisSelect.val();

                // Add new templates for checked checkboxes
                selectedCheckboxValues.forEach(function(templateId, index) {
                    // Check if the template is already present to avoid duplication
                    if (!templateContainer.find(`#template_${templateId}`).length) {
                        var jhfbkljfh = adultQuestionnaireFormHtml(templateId, index);
                        templateContainer.append(jhfbkljfh);
                    }
                });
            }
        });

        // multiple form html (for adult questionnaire) 
        function adultQuestionnaireFormHtml(templateId, indexNo) {
            var optionsHTML = '';
            $.each(clientIssue, function(index, issue) {
                optionsHTML += '<option value="' + issue.issue + '" data-issueId="' + issue.id + '">' + issue.issue + '</option>';
            });

            return `<div class="card hidden FunctionalImpairmentNarrative template" id="template_${templateId}" data-templateId="${templateId}">
                <div class="card-header">
                    <h5><span class="diagnosisHeadingSpan">Adult </span>Functional Narrative Impairment #${indexNo + 1}</h5>
                </div>
                <div class="card-body">
                    <div class="card-body">

                        <p> State priority population diagnosis, the onset/duration of diagnosis, and the DSM‐V symptom of the underlying priority population diagnosis that is presented by the client which adversely affects the functional impairment.</p>

                        <p> State how the symptom adversely affects the functional impairment.</p>
                        <p> State how the PRP can assist the client to
                            improve or restore independent living and
                            social skills necessary to support the
                            individual's recovery, ability to make
                            informed decisions and choices, and
                            participation in community life.</p>
                        <p> To be considered evidence of impaired role functioning at least three of the following must have been present on a continuing or intermittent basis. If to your knowledge, the individual demonstrates impaired role functioning in the specified areas for at least two years, select the areas below. If not, stop, and do not complete this form.</p>

                        <p>Marked inability to establish or maintain independent competitive employment characterized by an established pattern of unemployment; underemployment; or sporadic employment that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.</p>

                        <div class="mb-3 row">
                            <label for="mentalDiagnosis-${templateId}" class="col-sm-4 col-form-label">Client's mental health diagnosis is:<span class="text-danger"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control mentalDiagnosis" id="mentalDiagnosis-${templateId}" name="questionnaire[${templateId}][mentalDiagnosis]" value="${diagnosisVal}" required>
                                <div class="invalid-feedback">Please enter the Client's mental health diagnosis.</div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="symptom-${templateId}" class="form-label">Which symptom of the above diagnosis impairs the client's functioning in this domain?<span class="text-danger"> *</span</label>
                            <select class="form-select symptom" aria-label="Default select example" name="questionnaire[${templateId}][symptom]" id="symptom-${templateId}" required>
                            ${symtomsHtml}
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>

                    
                        <div class="mb-3 row">
                            <label for="experienced-${templateId}" class="col-sm-4 col-form-label">[Client], has experienced [Symptom of diagnosis] since [Onset date]:<span class="text-danger"> *</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control experienced" id="experienced-${templateId}" name="questionnaire[${templateId}][experienced]" required>
                                <div class="invalid-feedback">Please Select Value.</div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="clientIssue-${templateId}" class="form-label">7. Client presents with issues regarding...<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example" name="questionnaire[${templateId}][clientIssue]" id="clientIssue-${templateId}" onchange="handleclientIssue(this)" required>
                                <option value="">--Select--</option>
                                ${optionsHTML}
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>
                        <div class="mb-3 row">
                            <label for="namely-${templateId}" class="form-label">8. Namely, the client's ...<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example" name="questionnaire[${templateId}][namely]" id="namely-${templateId}" onchange="handleClientnamely(this)" required>
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>
                        <div class="mb-3 row">
                            <label for="specifically-${templateId}" class="form-label">9. Specifically...<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][specifically]" id="specifically-${templateId}" required>
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>

                        <div class="mb-3 row">
                            <label for="clientAdditionalInformation-${templateId}" class="form-label">10. Additional information on the client's need in this area:<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][clientAdditionalInformation]" id="clientAdditionalInformation-${templateId}">
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>
                        <div class="mb-3 row">
                        <textarea class="form-control" rows="3" name="questionnaire[${templateId}][clientAdditionalInformationText]"></textarea>
                        </div>
                        <div class="mb-3 row">
                            <label for="intervention-${templateId}" class="form-label">11. The following intervention was implemented:<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][intervention]" id="intervention-${templateId}" onchange="handleClientQuestionnaire(this)" required>
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>


                        <div class="mb-3 row">
                            <label for="specificallyIntervention-${templateId}" class="form-label">12. Specifically, an example for a planned intervention is to:<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][specificallyIntervention]" id="specificallyIntervention-${templateId}" required>
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>


                        <div class="mb-3 row">
                            <label for="serviceAdditionalInformation-${templateId}" class="form-label">13. Additional information on services to address this specific need area:<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][serviceAdditionalInformation]" id="serviceAdditionalInformation-${templateId}">
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>
                        <div class="mb-3 row">
                        <textarea class="form-control" id="test1" rows="3" name="questionnaire[${templateId}][serviceAdditionalInformationText]" ></textarea>
                        </div>
                        <div class="mb-3 row">
                            <label for="clientLongTermGoal-${templateId}" class="form-label">14. Client's long term goal to address this impairment is..<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][clientLongTermGoal]"  id="clientLongTermGoal-${templateId}" required>
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>

                        <div class="mb-3 row">
                            <label for="clientShortTermGoal-${templateId}" class="form-label">15. Client's short term goal to address this impairment is..<span class="text-danger"> *</span</label>
                            <select class="form-select" aria-label="Default select example"  name="questionnaire[${templateId}][clientShortTermGoal]" id="clientShortTermGoal-${templateId}" required>
                                <option value="">--Select--</option>
                            </select>
                            <div class="invalid-feedback">Please Select Value.</div>

                        </div>
                    </div>

                </div>
            </div> `;
        }

        // on client issue change 
        function handleclientIssue(element) {
            var selectId = element.id;
            var idArray = selectId.split('-');
            var testid = idArray[1];
            var selectedValue = element.value;
            var $element = $(element);
            var selectedOption = $element.find(':selected');
            var clientIssueId = selectedOption.data('issueid');
            $.ajax({
                url: 'functions/getClientNamely.php',
                type: 'POST',
                data: {
                    clientIssueId: clientIssueId
                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    $('#namely-' + testid).empty();
                    $('#namely-' + testid).append(firstOption);
                    $('#intervention-' + testid).empty();
                    $('#intervention-' + testid).append(firstOption);
                    $('#clientLongTermGoal-' + testid).empty();
                    $('#clientLongTermGoal-' + testid).append(firstOption);
                    $('#clientShortTermGoal-' + testid).empty();
                    $('#clientShortTermGoal-' + testid).append(firstOption);
                    $('#specifically-' + testid).empty();
                    $('#specificallyIntervention-' + testid).empty();
                    $('#clientAdditionalInformation-' + testid).empty();
                    $('#clientAdditionalInformation-' + testid).append(firstOption);
                    $('#serviceAdditionalInformation-' + testid).empty();
                    $('#serviceAdditionalInformation-' + testid).append(firstOption);
                    if (responseData.clientNamelyData.length > 0) {
                        $.each(responseData.clientNamelyData, function(index, option) {
                            $('#namely-' + testid).append('<option value="' + option.name + '" data-namelyid="' + option.id + '" data-issueid="' + option.issue_id + '">' + option.name + '</option> ');
                        });
                    }

                    if (responseData.interventionData.length > 0) {
                        $.each(responseData.interventionData, function(index, option) {
                            $('#intervention-' + testid).append('<option value="' + option.name + '" data-interventionid="' + option.id + '" data-issueid="' + option.issue_id + '">' + option.name + '</option> ');
                        });
                    }

                    if (responseData.clientLongTermGoalData.length > 0) {
                        $.each(responseData.clientLongTermGoalData, function(index, option) {
                            $('#clientLongTermGoal-' + testid).append('<option value="' + option.name + '" data-clientlongtermgoalid="' + option.id + '" data-issueid="' + option.issue_id + '">' + option.name + '</option> ');
                        });
                    }


                    if (responseData.clientShortTermGoalData.length > 0) {
                        $.each(responseData.clientShortTermGoalData, function(index, option) {
                            $('#clientShortTermGoal-' + testid).append('<option value="' + option.name + '" data-clientshorttermgoalid="' + option.id + '" data-issueid="' + option.issue_id + '">' + option.name + '</option> ');
                        });
                    }

                    if (responseData.additionalInformationClientData.length > 0) {
                        $.each(responseData.additionalInformationClientData, function(index, option) {
                            $('#clientAdditionalInformation-' + testid).append('<option value="' + option.name + '" data-clientAdditionalInformationid="' + option.id + '" data-issueid="' + option.issue_id + '">' + option.name + '</option> ');
                        });
                    }

                    if (responseData.additionalInformationServiceData.length > 0) {
                        $.each(responseData.additionalInformationServiceData, function(index, option) {
                            $('#serviceAdditionalInformation-' + testid).append('<option value="' + option.name + '" data-serviceAdditionalInformationid="' + option.id + '" data-issueid="' + option.issue_id + '">' + option.name + '</option> ');
                        });
                    }

                },
                error: function() {
                    $('#result').html('Error loading data.');
                }
            });
        }

        // on client namely change action
        function handleClientnamely(element) {
            var selectId = element.id;
            var idArray = selectId.split('-');
            var testid = idArray[1];
            var selectedValue = element.value;
            var $element = $(element);
            var selectedOption = $element.find(':selected');
            var clientNameleyId = selectedOption.data('namelyid');
            var clientIssueId = selectedOption.data('issueid');
            $.ajax({
                url: 'functions/getClientSpecifically.php',
                type: 'POST',
                data: {
                    clientIssueId: clientIssueId,
                    clientNameleyId: clientNameleyId,

                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    $('#specifically-' + testid).empty();
                    $('#specifically-' + testid).append(firstOption);
                    if (responseData.length > 0) {
                        $.each(responseData, function(index, option) {
                            $('#specifically-' + testid).append('<option value="' + option.name + '" data-specificallyid="' + option.id + '">' + option.name + '</option> ');
                        });
                    } else {
                        $('#specifically' + testid).empty();
                        $('#specifically' + testid).append(firstOption);
                    }
                },
                error: function() {}
            });
        }


        // on client intervention change
        function handleClientQuestionnaire(element) {
            var selectedValue = element.value;
            var selectId = element.id;
            var idArray = selectId.split('-');
            var testid = idArray[1];
            var $element = $(element);
            var selectedOption = $element.find(':selected');
            var clientIssueId = selectedOption.data('issueid');
            var interventionId = selectedOption.data('interventionid');
            $.ajax({
                url: 'functions/getClientSpecificallyIntervention.php',
                type: 'POST',
                data: {
                    clientIssueId: clientIssueId,
                    interventionId: interventionId,

                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    $('#specificallyIntervention-' + testid).empty();
                    $('#specificallyIntervention-' + testid).append(firstOption);
                    if (responseData.length > 0) {
                        $.each(responseData, function(index, option) {
                            $('#specificallyIntervention-' + testid).append('<option value="' + option.name + '" data-specificallyInterventionid="' + option.id + '">' + option.name + '</option> ');
                        });
                    } else {
                        $('#specificallyIntervention-' + testid).empty();
                        $('#specificallyIntervention-' + testid).append(firstOption);
                    }
                },
                error: function() {}
            });

        }

        // on reset refreash page to empty form
        function refreshPage() {
            if (confirm('Are you sure you want to reset the form?')) {
                location.reload();
            }
        }

        //according to client age handle data / another form html
        function clientAgeOnchange(selectedType) {
            $('.minorQuestionnaire').removeClass('hidden');
            $('#diagnosisSelect').empty();
            var diagnosisHeadingSpan = $('.diagnosisHeadingSpan');
            $('#diagnosis').empty();
            $('#checkboxContainer').empty();
            var allCheckbox = [];
            var diagnosisArray = []
            if (selectedType === 'yes') {
                diagnosisHeadingSpan.text('Minor');
                allCheckbox = minorCheckobox
                diagnosisArray = diagnosisMinorArray
                templateContainer.empty();
                $.each(minorCheckobox, function(index, item) {
                    var createRow = ` <div class="mb-3 row row-age">
                                    <label class="col-sm-8 col-form-label">${item}</label>
                                    <div class="col-sm-4 form-check">
                                        <div class="row row-cols">
                                            <div class="col-sm-4 form-check mt-2">
                                                <label for="minorYes-${index}" class="col-form-label">Yes</label>
                                                <input class="form-check-input mt-2 functionalImpairment" type="radio" id="minorYes-${index}" name="minorForm[${index}]"  value="yes"  onchange="handleMinorRadioYes(this, '${index}')" >
                                            </div>
                                            <div class="col-sm-8 form-check mt-2">
                                                <label for="minorNo-${index}" class="col-form-label">No</label>
                                                <input class="form-check-input mt-2 functionalImpairment" type="radio" id="minorNo-${index}" name="minorForm[${index}]"  value="no" onchange="handleMinorRadioYes(this, '${index}')">

                                            </div></div>
                                </div>`;
                    if (index != 'minor1') {
                        var optionsHtml = firstOption;
                        if (index == 'minor2') {
                            var optionsFromConfig = <?php echo json_encode($saticText['minor2']); ?>;

                        } else {
                            var optionsFromConfig = <?php echo json_encode($saticText['minor3']); ?>;

                        }

                        $.each(optionsFromConfig, function(indexOp, option) {
                            optionsHtml += '<option value="' + option + '">' + option + '</option>';
                        });
                        createRow += `<div class="mb-3 row hidden selectDiv${index}">
                <label for="minorFormAns-${index}" class="form-label">Possible Answers for above?</label>
                <select class="form-select symptom" aria-label="Default select example" name="${index}" id="minorFormAns-${index}">
             ${optionsHtml}
                </select>
            </div>`;
                    }
                    $('#checkboxContainer').append(createRow);
                });
            } else if (selectedType === 'no') {
                diagnosisHeadingSpan.text('Adult');
                allCheckbox = adultCheckobox
                diagnosisArray = diagnosisAdultArray
                $.each(adultCheckobox, function(index, item) {
                    var createRow = '<div class="row"><label for="functionalImpairment-' + index + '" class="col-sm-8 col-form-label">' + item + '</label><div class="col-sm-2 form-check mt-2"><input class="form-check-input functionalImpairment" type="checkbox" id="functionalImpairment-' + index + '" name="functionalImpairment" value="' + index + '"></div></div>';
                    $('#checkboxContainer').append(createRow);
                });
            }

            $('#diagnosis').append(firstOption);
            $.each(diagnosisArray, function(index, option) {
                $('#diagnosis').append('<option value="' + option.diagnosis_name + '" data-diagnosisid="' + option.id + '">' + option.diagnosis_name + '</option>');
            });

        }

        //get medications of clients 
        function getClientMedication(clientId) {
            $.ajax({
                url: 'functions/getConsumerMedication.php',
                type: 'POST',
                data: {
                    clientId: clientId
                },
                success: function(response) {
                    var responseData = JSON.parse(response);
                    $.each(responseData, function(index, medication) {
                        $('#medicationName' + index + ' option[data-medicationnameid="' + medication.medication_name_id + '"]').prop('selected', true);
                        $('#medicationDosage' + index + ' option[data-medicationdoseid="' + medication.medication_dose_id + '"]').prop('selected', true);
                        $('#medicationFrequency' + index + ' option[data-medicationfrequencyid="' + medication.medication_frequency_id + '"]').prop('selected', true);
                    });
                },
                error: function() {
                    $('#result').html('Error loading data.');
                }
            });
        }

        // on Functional impairment questionnaire radio change 
        function handleMinorRadioYes(element, val) {
            if (val != 'minor1') {

            }
            if (element.value === 'yes') {
                $('.selectDiv' + val + '').removeClass('hidden');
            } else {
                $('.selectDiv' + val + '').addClass('hidden');
            }
        }

        // show alert for Functional impairment questionnaire radio or checked select
        function showFuntionalAlert() {

            var returnVal = false;
            var minorAge = $('input[name="minorAge"]:checked');
            var minorAgeValue = minorAge.val();
            var number = 4;
            if (minorAgeValue === 'yes') {
                number = 1;
            }
            $('#checkboxWarning').empty();
            var checkedCheckboxes = $('.functionalImpairment:checked').length;
            $('.FunctionalImpairmentNarrative').removeClass('hidden');

            var alertHtml = `<div class="alert alert-danger d-flex align-items-center " role="alert">
                    <div>
                    <i class="bi-exclamation-octagon-fill"></i> Select At least ${number} checkbox otherwise ,you will not be able to complete this form and the
                    submission buttion will be disabled.
                    </div>
                </div>`;
            if (checkedCheckboxes >= number) {
                $('#checkboxWarning').empty();
                $('#checkboxWarning').removeClass('showingWorning');

            } else {
                $('#checkboxWarning').append(alertHtml);
                $('#checkboxWarning').addClass('showingWorning');

            }

            if ($('body:has(.showingWorning)').length > 0) {
                submitButton.prop('disabled', true);
                returnVal = false;

            } else {
                submitButton.prop('disabled', false);
                returnVal = true;
            }
            return returnVal;
        }
    </script>
</body>

</html>