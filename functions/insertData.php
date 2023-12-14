<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

class GoogleSheetsHandler
{
    private $apiKey;
    private $spreadsheetId;
    private $service;

    public function __construct()
    {
        $this->apiKey = Secret::getGoogleApiKey();
        $this->spreadsheetId = '10cy37MmDeQ4BortDoHEOxqDwLlhjmlMC4iCXZ0yPt6c';
        $this->initializeService();
    }

    private function initializeService()
    {
        // $client = new Google_Client();
        // $this->service->getClient()->setDeveloperKey($this->apiKey);
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $path = '../prp-35124-2c8d3e1e290a.json';
        $client->setAuthConfig($path);
        $this->service = new Google_Service_Sheets($client);
    }

    public function insertData($data)
    {
        $headingRange = 'Sheet1';
        $headingExists = $this->headingExists($headingRange);
        // if (!$headingExists) {
        //     print_r($data);
        //     $this->addHeading($headingRange, array_keys($data));
        // }

        // $body = new Google_Service_Sheets_ValueRange([
        //     'values' => [array_values($data)],
        // ]);

        // $params = ['valueInputOption' => 'RAW'];

        // $result = $this->service->spreadsheets_values->update($this->spreadsheetId, $headingRange, $body, $params);
        $result = $this->addHeading($headingRange, array_values($data));
        return $result;
    }

    private function headingExists($range)
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $range);
        return !empty($response->getValues());
    }

    private function addHeading($range, $heading)
    {
        $rows = [$heading]; 
        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues($rows);
        $options = ['valueInputOption' => 'RAW'];
        $result = $this->service->spreadsheets_values->append($this->spreadsheetId, $range, $valueRange, $options);
        return $result;
    }

    private function getColumnName($index)
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $letters[$index - 1];
    }

    private function replaceUnderscoresWithSpaces($data)
    {
        return array_map(function ($key) {
            return str_replace('_', ' ', $key);
        }, $data);
    }
}

