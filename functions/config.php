<?php

class Secret
{
    public static function getGoogleApiKey()
    {
        return 'AIzaSyBOPcINZBcaBGwoMglLALCph7YtAGRkv84';
    }

    public static function getSpreadsheetId()
    {
        // return '19Zb7X3Xm3k9HChqB1nPX0F8H2xaMHU1Qc8agWj95mRo'; // dev id

        return '1nrMb4SK76iHWr3-EF_1viggQdsmdUdCyg5N6wedRNaY'; //live sheet
    }

    public static function referringCredentials()
    {
        return ['LCSW-C', 'LCSW', 'LMSW', 'LBSW', 'LCPC', 'LGPC', 'LCADC', 'LGADC', 'CAC-AD', 'CSC-AD', 'ADT', 'LCMFT', 'LGMFT', 'CRNP-F', 'CRNP-PMH', 'MD', 'RN', 'LMSW intern', 'LGPC intern', 'CNS-PMH / APRN-PMH', 'CRNP intern', 'PhD Psychologist', 'Psychologist Associate'];
    }

    public static function getCliniciansSheetDetail()
    {
        return [
            'sheetName' => 'SECTION 1: CLINICIANS INFORMATION',
            'keys' => ['firstName', 'lastName', 'credentials', 'organization', 'phone', 'email', 'agencyNpi', 'service']
        ];
    }

    public static function getClientSheetDetail()
    {
        return [
            'sheetName' => 'SECTION 2: CONSUMER INFORMATION ',
            'keys' => ['firstName', 'lastName', 'dob', 'gender', 'diagnosis', 'Diagnosis-DB','medication', 'medicationName', 'test', 'test4', 'clientMedicaidNumber', 'clientSocialSecurityNumber', 'address', 'test1', 'schoolGrade']
        ];
    }


    public static function saticText()
    {
        return [
            'medicationNoRadio' => 'The consumer is ongoing treatment. Medication need, or want is still being assessed to determine if it will be the best fit for the consumer.',
            
            'adultCheckobox' => ['adult1'=>"Marked inability to establish or maintain independent competitive employment characterized by an established pattern of unemployment; underemployment; or sporadic employment that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.",

            'adult2'=>"Marked inability to establish or maintain independent competitive employment characterized by an established pattern of unemployment; underemployment; or sporadic employment that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.", 

            'adult3'=>"Marked inability to perform instrumental activities of daily living (shopping; meal preparation; laundry; basic housekeeping; medication management; transportation; and money management) that is primarily attributable to a diagnosed serious mental illness; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver's license due to legal problems.", 

            'adult4'=>"Marked or frequent deficiencies of concentration; persistence or pace that is primarily attributable to a serious mental illness resulting in a failure to complete in a timely manner tasks commonly found in work; school; or home settings; which requires intervention by the behavioral health system beyond what is available to the individual from by mainstream workforce development; educational; faith-based; community or social service organizations",

             'adult5'=>"Marked deficiencies in self-direction; characterized by an inability to independently plan; initiate; organize; and carry out goal-directed activities that is primarily attributable to a serious mental illness; and which requires intervention by the behavioral health system beyond what can be reasonably provided by mainstream workforce development; educational; faith-based; community or social service organizations",
             'adult6'=>"Marked inability to procure financial assistance to support community living; which inability is primarily attributable to a serious mental illness; and which requires intervention by the behavioral health system beyond what can be reasonably provided by mainstream workforce development; educational; faith-based; community or social service organizations. This does not include limitations due to factors such as geographic location; poverty; lack of education; availability of transportation; or loss of driver’s license due to legal problems."],
            "MinorCheckobox" => [],

            "ansOfQuestion21" => "Client has been engaging in regular outpatient treatment and has made minimal progress. Additional supports are needed."
        ];
    }
}
