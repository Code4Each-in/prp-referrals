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

// echo $saticText['medicationNoRadio'];die;
// print_r($getClientSheetData);
// $settings = $db->query('SELECT * FROM Settings WHERE VariableDescription = "DelayMinutesToConfirmAirbnbMessageDelivery"')->fetchArray();
function showAlert(){
   echo  '<div class="alert alert-danger d-flex align-items-center " role="alert">
    <div>
    <i class="bi-exclamation-octagon-fill"></i> Answering "no" or "unknown" to this question
      automatically disqualifies
      the individual from being eligible for PRP services. If the answer is
      "no" or "unknown", you will not be able to complete this form and the
      submission buttion will be disabled. If you answer "no" or "unknown" in
      error and intended to answer the question with "yes", simply correct
      your answer and move to the next question.
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

        /* Custom CSS for adding border to Bootstrap inputs */
        /* #contactForm .form-control {
        border: 1px solid #a9a6a6;
    }

    .form-check-input {
        border: 1px solid #a9a6a6;
    }

    i.fa.fa-hand-o-right {
        color: #ff0000;
        font-family: 'FontAwesome';
    }

    .mb-3.row.row-age {
        display: block;
    }

    .row-radio label.col-sm-8.col-form-label {
        color: #444444;
        text-align: justify;
        font-size: 15px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mb-3.row.row-radio {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .row.row-cols {
        display: flex;
        justify-content: flex-start;
        margin: 0px auto;
        align-items: center;
    }

    .row.row-jhk {
        display: flex;
        justify-content: flex-start;
        margin: 0px auto 10px;
        align-items: center;
        padding-left: 30px;
    }

    .row.row-jhk label.col-sm-8.col-form-label {
        color: #444444;
        text-align: justify;
        font-size: 14px;
        font-weight: 400;
        display: inline;
    }

    .row.row-cols label.col-form-label {
        padding-top: 5px;
    }

    ol.oreder-listing {
        padding-left: 1rem;
    }

    .alert.alert-danger.alert-dismissible.fade.show {
        padding-right: 1rem;
        text-align: center;
    }

    .alert.alert-danger.alert-dismissible.fade.show p {
        font-size: 14px;
    }

    .alert.alert-danger.alert-dismissible.fade.show {
        padding-right: 1rem;
        text-align: center;
        position: absolute;
        bottom: -65rem;
        right: 15px;
        width: 20%;
    } */
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
                <form action="process_form.php" method="post" id="contactForm" novalidate>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Referring Professional Information</h5>
                        </div>
                        <div class="card-body">

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
                                    <select class="form-select" aria-label="Default select example" name="credentials" id="credentials">
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
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="refClinicianPhone" class="col-sm-4 col-form-label">Referring Clinician
                                    Phone
                                    No:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="refClinicianPhone" name="refClinicianPhone" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="refClinicianEmail" class="col-sm-4 col-form-label">Referring Clinician
                                    Email
                                    Address:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" id="refClinicianEmail" name="refClinicianEmail" required>
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
                                        <input class="form-check-input" type="checkbox" id="service1" name="services[]" value="housing - assisted living services - 24/7 supervision" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service2" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i> Housing - supportive
                                        housing
                                        services - day time supervision</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="service2" name="services[]" value="housing - supportive housing services - day time supervision" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service3" class="col-sm-8 col-form-label"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Psychiatry</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="service3" name="services[]" value="psychiatry" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="service4" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>Medication
                                        management</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="service4" name="services[]" value="medication management" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service5" class="col-sm-8 col-form-label"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Mental health
                                        counseling</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="service5" name="services[]" value="mental health counseling" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="service6" class="col-sm-8 col-form-label">
                                        <i class="fa fa-hand-o-right" aria-hidden="true"></i> Primary care</label>
                                    <div class="col-sm-2 form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="service6" name="services[]" value="primary care" required>
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


                                    <div class="col-sm-6 form-check mt-2">
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


                                    <div class="col-sm-6 form-check mt-2">
                                        <label for="gender6" class="col-sm-8 col-form-label">Additional gender category
                                            or
                                            other, please specify</label>
                                        <input class="form-check-input" type="radio" id="gender6" name="clientGender" value="Additional gender category or other, please specify">
                                    </div>



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
                                            <input class="form-check-input mt-2" type="radio" id="minorYes" name="minorAge" value="yes">
                                        </div>
                                        <div class="col-sm-8 form-check mt-2">
                                            <label for="minorNo" class="col-form-label">No</label>
                                            <input class="form-check-input mt-2" type="radio" id="minorNo" name="minorAge" value="no">
                                        </div>
                                    </div>
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
                                                        <input class="form-check-input mt-2" type="radio" id="clientHomelessYes" name="clientHomeless" value="yes">
                                                    </div>
                                                    <div class="col-sm-8 form-check mt-2">
                                                        <label for="clientHomelessNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="clientHomelessNo" name="clientHomeless" value="no">
                                                    </div>
                                                </div>
                                            </div>



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
                                                        <input class="form-check-input mt-2" type="radio" id="disorderYes" name="disorder" value="yes">
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
                                                        <input class="form-check-input mt-2" type="radio" id="communicableDiseasesYes" name="communicableDiseases" value="yes">
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
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsYes" name="medications" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="medicationsNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsNo" name="medications" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="medicationsUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="medicationsUnknown" name="medications" value="unknown">
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
                                                for ($i = 1; $i <= 4; $i++) {
                                                ?>
                                                    <tr>
                                                        <td> <select class="form-select" aria-label="Default select example" name="medicationName<?php echo $i; ?>" id="medicationName<?php echo $i; ?>">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($medicationName as $name) {
                                                                    echo '<option value="' . htmlspecialchars($name['name']) . '" >' . $name['name'] . '</option>';
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td> <select class="form-select" aria-label="Default select example" name="medicationDosage<?php echo $i; ?>" id="medicationDosage<?php echo $i; ?>">
                                                                <option value=""></option>
                                                                <?php
                                                                foreach ($medicationDosage as $dosage) {
                                                                    echo '<option value="' . htmlspecialchars($dosage['medication_dosage']) . '" >' . $dosage['medication_dosage'] . '</option>';
                                                                }
                                                                ?>
                                                            </select></td>
                                                        <td> <select class="form-select" aria-label="Default select example" name="medicationFrequency<?php echo $i; ?>" id="medicationFrequency<?php echo $i; ?>">
                                                                <option value=""></option>

                                                                <?php
                                                                foreach ($medicationFrequency as $dosage) {
                                                                    echo '<option value="' . htmlspecialchars($dosage['frequency']) . '" >' . $dosage['frequency'] . '</option>';
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
                                                <textarea class="form-control" id="medicationTreatment" 
                                                rows="3"
                                                name="medicationTreatment"><?php echo $saticText['medicationNoRadio']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="mb-3 row hidden" id="medicationsNoRadioDiv">
                                            <label for="medicationsNoRadio" class="col-sm-4 col-form-label">Please
                                                explain
                                                why the
                                                participant is not on medication:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" id="medicationsNoRadio" 
                                                rows="3"
                                                name="medicationsNoRadio"><?php echo $saticText['medicationNoRadio']; ?></textarea>
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
                                                        <input class="form-check-input mt-2" type="radio" id="dischargedYes" name="discharged" value="yes">
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
                                                        <input class="form-check-input mt-2" type="radio" id="arrestedYes" name="arrested" value="yes">
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
                                                        <input class="form-check-input mt-2" type="radio" id="employedYes" name="employed" value="yes">
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
                                                        <input class="form-check-input mt-2" type="radio" id="receivingTreatmentYes" name="receivingTreatment" value="yes">
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
                                                        <input class="form-check-input mt-2" type="radio" id="currentlyEnrolledYes" name="currentlyEnrolled" value="yes">
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
                                    <li>
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
                                                        <input class="form-check-input mt-2" type="radio" id="individualNatureYes" name="individualNature" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="individualNatureNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualNatureNo" name="individualNature" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="individualNatureUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualNatureUnknown" name="individualNature" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="individualNatureWarning hidden">
                                            <?php showAlert();?>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <label class="col-sm-8 col-form-label">Does the Individual require a more
                                                intensive
                                                level of care?<span class="text-danger"> *</span></label>
                                            <div class="col-sm-4 form-check">
                                                <div class="row  row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="individualIntensiveCareYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensiveCareYes" name="individualIntensiveCare" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="individualIntensiveCareNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensiveCareNo" name="individualIntensiveCare" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="individualIntensiveCareUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensiveCareUnknown" name="individualIntensiveCare" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="individualIntensiveCareWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row-list">

                                            <p class="col-sm-8 col-form-label">Have all less intensive levels of
                                                treatment
                                                have been determined to be unsafe or unsuccessful?<span class="text-danger">
                                                    *</span></p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="individualIntensivelevelYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensivelevelYes" name="individualIntensivelevel" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="individualIntensivelevelNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensivelevelNo" name="individualIntensivelevel" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="individualIntensivelevelUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="individualIntensivelevelUnknown" name="individualIntensivelevel" value="unknown">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="individualIntensivelevelWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
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
                                                        <input class="form-check-input mt-2" type="radio" id="supportConsideredYes" name="supportConsidered" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="supportConsideredNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="supportConsideredNo" name="supportConsidered" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="supportConsideredUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="supportConsideredUnknown" name="supportConsidered" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="supportConsideredWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-8 col-form-label">Participant is fully eligible for
                                                Developmental Disabilities Administration funded services?
                                                <span class="text-danger"> *</span>
                                            </p>
                                            <div class="col-sm-4 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-4 form-check mt-2">
                                                        <label for="eligibleDisableAdminServiceYes" class="col-form-label">Yes</label>
                                                        <input class="form-check-input mt-2" type="radio" id="eligibleDisableAdminServiceYes" name="eligibleDisableAdminService" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="eligibleDisableAdminServiceNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="eligibleDisableAdminServiceNo" name="eligibleDisableAdminService" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="eligibleDisableAdminServiceUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="eligibleDisableAdminServiceUnknown" name="eligibleDisableAdminService" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="eligibleDisableAdminServiceWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>

                                    <li>
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
                                                        <input class="form-check-input mt-2" type="radio" id="organicProcessOrSyndromeYes" name="organicProcessOrSyndrome" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="organicProcessOrSyndromeNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="organicProcessOrSyndromeNo" name="organicProcessOrSyndrome" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="organicProcessOrSyndromeUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="organicProcessOrSyndromeUnknown" name="organicProcessOrSyndrome" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="organicProcessOrSyndromeWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
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
                                                        <input class="form-check-input mt-2" type="radio" id="behavioralControlYes" name="behavioralControl" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="behavioralControlNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="behavioralControlNo" name="behavioralControl" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="behavioralControlUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="behavioralControlUnknown" name="behavioralControl" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="behavioralControlWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
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
                                                        <input class="form-check-input mt-2" type="radio" id="lacksCapacityForPRPYes" name="lacksCapacityForPRP" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="lacksCapacityForPRPNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="lacksCapacityForPRPNo" name="lacksCapacityForPRP" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="lacksCapacityForPRPUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="lacksCapacityForPRPUnknown" name="lacksCapacityForPRP" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lacksCapacityForPRPWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
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
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourcePaidYes" name="referralSourcePaid" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="referralSourcePaidNo" class="col-form-label">No</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourcePaidNo" name="referralSourcePaid" value="no">
                                                    </div>
                                                    <div class="col-sm-5 form-check mt-2">
                                                        <label for="referralSourcePaidUnknown" class="col-form-label">Unknown</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourcePaidUnknown" name="referralSourcePaid" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="referralSourcePaidWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row-list">
                                            <p class="col-sm-4 col-form-label">Is the participant being referred from:
                                            </p>
                                            <div class="col-sm-8 form-check">
                                                <div class="row row-cols">
                                                    <div class="col-sm-6 form-check mt-2">
                                                        <label for="referralSourceDigital" class="col-form-label">IP/
                                                            Crisis Res/
                                                            Mobile/ ACT/ RTC/ Incarceration</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourceDigital" name="referralSource" value="yes">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="referralSourceOutpatient" class="col-form-label">Outpatient</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourceOutpatient" name="referralSource" value="no">
                                                    </div>
                                                    <div class="col-sm-3 form-check mt-2">
                                                        <label for="referralSourceNeither" class="col-form-label">Neither</label>
                                                        <input class="form-check-input mt-2" type="radio" id="referralSourceNeither" name="referralSource" value="unknown">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="referralSourceWarning hidden">
                                            <?php showAlert();?>
                                        </div>
                                    </li>
                                    <li>
                                        <p for="clientAddress" class="col-sm-12 col-form-label">Why is ongoing
                                            outpatient
                                            treatment not sufficient to address concerns?
                                            <span class="text-danger"> *</span>
                                        </p>
                                        <div class="col-sm-7">
                                            <textarea class="form-control" id="reasonForInsufficientTreatment" name="reasonForInsufficientTreatment" required></textarea>
                                        </div>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    
                    <div class="card minorQuestionnaire hidden">
                        <div class="card-header">
                            <h5><span id="diagnosisHeadingSpan">Minor</span> Questionnaire</h5>
                        </div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5>Mental Health diagnosis (<span id="diagnosisHeadingSpan"> Adult</span>)</h5>
                                </div>
                                <div class="card-body">
                                    <label for="diagnosis" class="form-label">What is the client's primary mental health diagnosis? <span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label="Default select example" name="diagnosis" id="diagnosis">
                                    </select>

                                </div>

                            </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Functional impairment questionnaire (<span id="diagnosisHeadingSpan"> Adult</span>)</h5>
                        </div>
                        <div class="card-body">
                        <div class="card-body">

                           <div class="mb-3 row row-radio">
                                <label class="col-sm-12 col-form-label">To be considered evidence of impaired role functioning at least three of the following must have been present on a continuing or intermittent basis. If to your knowledge, the individual demonstrates impaired role functioning in the specified areas for at least two years, select the areas below. If not, stop, and do not complete this form.  <span class="text-danger">*</span></label>
                                <div id="checkboxContainer">
                                 </div>
                    
                            </div>

                            <div class="card hidden FunctionalImpairmentNarrative">
                        <div class="card-header">
                            <h5><span id="diagnosisHeadingSpan">Adult </span>Functional Narrative Impairment #1</h5>
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
                                <label for="mentalDiagnosis" class="col-sm-4 col-form-label">Client's mental health diagnosis is:<span class="text-danger"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="mentalDiagnosis" name="mentalDiagnosis" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                            <label for="symptom" class="form-label">Which symptom of the above diagnosis impairs the client's functioning in this domain? <span class="text-danger">*</span></label>
                                    <select class="form-select" aria-label="Default select example" name="symptom" id="symptom">
                                       
                                    </select>

                                </div>
                                </div>

                            </div>
                        </div>
                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                        </div>

                    </div>
                    <div class="mt-4">
                        <button type="submit" disabled class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
            <!-- <div class="alert alert-danger alert-dismissible fade show">


                <p>
                    <i class="bi-exclamation-octagon-fill"></i> Answering "no" or "unknown" to this question
                    automatically disqualifies
                    the individual from being eligible for PRP services. If the answer is
                    "no" or "unknown", you will not be able to complete this form and the
                    submission buttion will be disabled. If you answer "no" or "unknown" in
                    error and intended to answer the question with "yes", simply correct
                    your answer and move to the next question.
                </p>

                 <p class="mb-0">Once you have filled all the details, click on the 'Next' button to continue.</p>
        </div> -->
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


        var diagnosisHeadingSpan = $('#diagnosisHeadingSpan');
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

        // Bootstrap form validation using Bootstrap 5
        (function() {
            'use strict';

            var form = document.getElementById('contactForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);

            // Show validation feedback on blur
            form.addEventListener('blur', function(event) {
                if (event.target.tagName === 'INPUT') {
                    if (!event.target.checkValidity()) {
                        event.target.classList.add('is-invalid');
                    } else {
                        event.target.classList.remove('is-invalid');
                    }
                }
            }, true);
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
                        console.log(matchingObjects);
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
                    var matchingClientObjects = clientSheetData.filter(function(obj) {
                        var trimmedFirstName = obj.firstName.trim().toLowerCase();
                        var trimmedLastName = obj.lastName.trim().toLowerCase();
                        var fullName = (trimmedFirstName + ' ' + trimmedLastName);
                        var trimmedInputValue = clientInputValue.toLowerCase();
                        return fullName === trimmedInputValue;
                    });
                    if (matchingClientObjects.length > 0) {
                        console.log(matchingClientObjects);
                        var matchingClientObject = matchingClientObjects[0];
                        $(this).val(matchingClientObject.firstName)
                        clientLastName.val(matchingClientObject.lastName);

                        //Set date 
                        clientBirthDate.val(convertDateFormat(matchingClientObject.dob));

                        //SET GENDER
                        if (matchingClientObject.gender != null) {

                            var cleintGenderToCheck = matchingClientObject.gender;

                            if (matchingClientObject.gender.toLowerCase().indexOf("minor") !== -1) {
                                cleintGenderToCheck = matchingClientObject.gender.replace(/\s*\(minor\)/i, '');
                            }
                            var radioToCheck = $('input[name="clientGender"][value="' + cleintGenderToCheck + '"]');

                            // Check the radio button if it exists
                            if (radioToCheck.length > 0) {
                                radioToCheck.prop('checked', true);
                            }
                        }

                        // SET SECURITY NUMBER
                        if (matchingClientObject.clientSocialSecurityNumber && matchingClientObject
                            .clientSocialSecurityNumber != "--") {
                            clientSecurityNumber.val(matchingClientObject.clientSocialSecurityNumber);
                        } else {
                            clientSecurityNumber.val("");
                        }


                        // SET ADDRESS
                        if (matchingClientObject.address && matchingClientObject.address != null) {
                            clientAddress.val(matchingClientObject.address);
                        } else {
                            clientAddress.val("");
                        }

                        //SET CLIENT MINOR 
                        if (matchingClientObject.minor && matchingClientObject.minor != "") {
                            if (matchingClientObject.minor.toLowerCase() == "yes") {
                                $("#minorYes").prop('checked', true);
                            }
                            if (matchingClientObject.minor.toLowerCase() == "no") {
                                $("#minorNo").prop('checked', true);
                            }
                        }
                        // SET ADDRESS
                        if (matchingClientObject.schoolGrade && matchingClientObject.schoolGrade != null) {
                            clientGrade.val(matchingClientObject.schoolGrade);
                        } else {
                            clientGrade.val("");
                        }

                        // checkboxToCheck.prop('checked', true);

                        // credentials.val(matchingObject.credentials);
                    } else {
                        emptyClientForm();
                    }
                    break;
                } else {
                    emptyClientForm();
                }
            }
        });

        function emptyClientForm() {
            clientLastName.val('');
            clientBirthDate.val('');
            clientSecurityNumber.val('');
            clientAddress.val('');
            clientGrade.val('');
            $('input[name="clientGender"]').prop('checked', false);
            $('input[name="minorAge"]').prop('checked', false);
        }

        // change date format
        function convertDateFormat(dateString) {
            var parts = dateString.split("/");
            var formattedDate = parts[2] + "-" + parts[0].padStart(2, '0') + "-" + parts[1].padStart(2, '0');
            return formattedDate;
        }

        //on medication radio change 
        $(document).ready(function() {
            $('[name="medications"]').change(function() {
                var value = $(this).val();
                if (value === 'yes') {
                    console.log("yes");
                    $('#medicationsYesRadioDiv').removeClass('hidden');
                    $('#medicationsNoRadioDiv').addClass('hidden');
                } else if (value === 'no') {
                    console.log("no");
                    $('#medicationsNoRadioDiv').removeClass('hidden');
                    $('#medicationsYesRadioDiv').addClass('hidden');
                    $('#medicationTreatmentDiv').addClass('hidden');
                    $('#medicationTable').addClass('hidden');
                    $('input[name="medicationsPrescribed"]').prop('checked', false);
                } else if (value === 'unknown') {
                    console.log("unknown");
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
                    console.log("yes");
                    $('#medicationTable').removeClass('hidden');
                    $('#medicationTreatmentDiv').addClass('hidden');
                } else if (value === 'no') {
                    console.log("no");
                    $('#medicationTreatmentDiv').removeClass('hidden');
                    $('#medicationTable').addClass('hidden');
                }
            });


            //
            $('[name="minorAge"]').change(function () {
                // Get the selected value
                $('.minorQuestionnaire').removeClass('hidden');
                var selectedType = $(this).val();
              
                $('#diagnosisSelect').empty();
                var diagnosisHeadingSpan = $('#diagnosisHeadingSpan');
                // Add new options based on the selected radio value
               
                if (selectedType === 'yes') {
                    $('#diagnosis').empty();
                    diagnosisHeadingSpan.text('Minor');
                    $('#diagnosis').append(firstOption);
                    $.each(diagnosisMinorArray, function (index, option) {
                    $('#diagnosis').append('<option value="' + option.diagnosis_name + '" data-diagnosisid="' + option.id + '">' + option.diagnosis_name + '</option> ');
                });
                } else if (selectedType === 'no') {
                    $('#diagnosis').empty();
                    diagnosisHeadingSpan.text('Adult');
                    $('#diagnosis').append(firstOption);
                    $.each(diagnosisAdultArray, function (index, option) {
                    $('#diagnosis').append('<option value="' + option.diagnosis_name + '" data-diagnosisid="' + option.id + '">' + option.diagnosis_name + '</option>');
                });
                $('#checkboxContainer').empty();
                $.each(adultCheckobox, function (index, item) {
                    var createRow = '<div class="row"><label for="functionalImpairment" class="col-sm-8 col-form-label">'+ item +'</label><div class="col-sm-2 form-check mt-2"><input class="form-check-input functionalImpairment" type="checkbox" id="functionalImpairment" name="functionalImpairment[]" value="'+ item +'"></div></div>';

                    // checkboxDiv.append(createRow);
                    $('#checkboxContainer').append(createRow);
                });
                }
            });

            $(document).on("change", '.functionalImpairment', function () {
               $('.FunctionalImpairmentNarrative').removeClass('hidden');
            });

            
            $('[name="individualNature"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.individualNatureWarning').removeClass('hidden');
                }else{
                    $('.individualNatureWarning').addClass('hidden'); 
                } 
            });

            $('[name="individualIntensiveCare"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.individualIntensiveCareWarning').removeClass('hidden');
                }else{
                    $('.individualIntensiveCareWarning').addClass('hidden'); 
                } 
            });


            $('[name="individualIntensivelevel"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.individualIntensivelevelWarning').removeClass('hidden');
                }else{
                    $('.individualIntensivelevelWarning').addClass('hidden'); 
                } 
            });


            $('[name="supportConsidered"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.supportConsideredWarning').removeClass('hidden');
                }else{
                    $('.supportConsideredWarning').addClass('hidden'); 
                } 
            });


            $('[name="eligibleDisableAdminService"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.eligibleDisableAdminServiceWarning').removeClass('hidden');
                }else{
                    $('.eligibleDisableAdminServiceWarning').addClass('hidden'); 
                } 
            });

            $('[name="organicProcessOrSyndrome"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.organicProcessOrSyndromeWarning').removeClass('hidden');
                }else{
                    $('.organicProcessOrSyndromeWarning').addClass('hidden'); 
                } 
            });


            $('[name="behavioralControl"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.behavioralControlWarning').removeClass('hidden');
                }else{
                    $('.behavioralControlWarning').addClass('hidden'); 
                } 
            });
            

            $('[name="lacksCapacityForPRP"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.lacksCapacityForPRPWarning').removeClass('hidden');
                }else{
                    $('.lacksCapacityForPRPWarning').addClass('hidden'); 
                } 
            });

            $('[name="referralSourcePaid"]').change(function() {
                var value = $(this).val();
                if (value === 'unknown') {
                    $('.referralSourcePaidWarning').removeClass('hidden');
                }else{
                    $('.referralSourcePaidWarning').addClass('hidden'); 
                } 
            });

            $('#diagnosis').change(function () {
                var selectedValue = $(this).val();
                $('#mentalDiagnosis').val(selectedValue);
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
                        $('#symptom').empty();
                        $('#symptom').append(firstOption);
                        $.each(responseData, function (index, option) {
                    $('#symptom').append('<option value="' + option.name + '" data-symptom="' + option.id + '">' + option.name + '</option> ');
                });
                        
                    },
                    error: function() {
                        $('#result').html('Error loading data.');
                    }
                });
            });
        
        });
    </script>
</body>

</html>