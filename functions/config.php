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
        return ['LCSW-C', 'LCSW', 'LMSW','LBSW' , 'LCPC', 'LGPC', 'LCADC', 'LGADC','CAC-AD' , 'CSC-AD', 'ADT', 'LCMFT', 'LGMFT', 'CRNP-F', 'CRNP-PMH', 'MD', 'RN', 'LMSW intern', 'LGPC intern', 'CNS-PMH / APRN-PMH', 'CRNP intern', 'PhD Psychologist', 'Psychologist Associate' ];
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
            'keys' => ['firstName', 'lastName', 'dob', 'gender', 'diagnosis', 'clientMedicaidNumber', 'clientSocialSecurityNumber', 'address','minor', 'schoolGrade']
        ];
    }
 
}
