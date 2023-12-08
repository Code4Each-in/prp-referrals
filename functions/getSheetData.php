<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

class GoogleSheetsReader
{
    private $apiKey;
    private $spreadsheetId;
    private $service;

    public function __construct()
    {
        $this->apiKey = Secret::getGoogleApiKey();
        $this->spreadsheetId = Secret::getSpreadsheetId();
        $this->initializeService();
    }

    private function initializeService()
    {
        $client = new Google_Client();
        $this->service = new Google_Service_Sheets($client);
        $this->service->getClient()->setDeveloperKey($this->apiKey);
    }

    public function readSheet($sheetName, $columnKeys)
    {
        $rageLimit = chr(ord('A') + count($columnKeys) - 1);
        $range = $sheetName. '!A1:'.$rageLimit; 
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            return "No data found.\n";
        } else {
            $headers = array_shift($values);
            $result = [];
            foreach ($values as $row) {
                $row = array_map('trim', $row);
                if(count($row) < count($columnKeys)){
                    $row = array_pad($row, count($columnKeys), ''); 
                }
                $rowData = array_combine($columnKeys, $row);
                $firstValue = reset($rowData);
                if(trim($firstValue) != ''){
                    $result[] = $rowData;
                }
            }
            return $result;
        }
    }
}

