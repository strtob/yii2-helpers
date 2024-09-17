<?php
namespace strtob\yii2helpers;

use Yii;
use DOMDocument;
use yii\db\Query;
use SimpleXMLElement;
use yii\httpclient\Client;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReportingHelper
{
    /**
     * Generates an Excel report based on the provided query definitions.
     *
     * @param array $reportingSourceSqls Array of query definitions.
     * @param array $reportingExtDataSourceStreams Array of 
     * @param array $excludeColumns Columns to exclude if not explicitly included.
     * @param string $fileName The name of the file to be generated, can include placeholders for dynamic fields.
     * @return string|null File path of the generated Excel file, or null on failure.
     */
    public static function generateExcelReport(
        $reportingSourceSqls,
        $reportingExtDataSourceStreams,
        $excludeColumns = [],
        $fileName = null,
        $templateFileNameWithDir = null
    ) {
        // Load the template if provided, otherwise create a new spreadsheet
        if (!empty($templateFileNameWithDir) && file_exists($templateFileNameWithDir)) {
            $spreadsheet = IOFactory::load($templateFileNameWithDir);
        } else {
            $spreadsheet = new Spreadsheet();
        }

        // Iterate over the reporting source SQLs
        foreach ($reportingExtDataSourceStreams as $reportingExtDataSourceStream) {

            $tblExtDataSource = $reportingExtDataSourceStream->tblExtDataSource;

            // Initialize the HTTP client
            $client = new Client();

            $apiUrl = $reportingExtDataSourceStream->tblExtDataSource->getApiUrl();

            // Set the request headers, including the Accept header for text/csv
            $headers = [
                'accept' => 'application/xml',
            ];

            // Set up and Send the request
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($apiUrl)
                ->setHeaders($headers)
                ->send();


            if ($response->isOk) {


                // Access specific headers
                $contentType = $headers->get('content-type');
                $contentLength = $headers->get('content-length');

                // Iterate over all headers
                foreach ($headers->toArray() as $name => $value) {
                    echo "$name: $value\n";
                }

                $xmlContent = $response->content;

                print_r($xmlContent);

                //$arrayData = self::convertSdmxXmlToArray($response);

                die();


            } else {
                echo "Reponse Error: " . $response->getStatusCode()
                    . ' for url ' . $apiUrl;
                die();
            }
        }

        // Iterate over the reporting source SQLs
        foreach ($reportingSourceSqls as $index => $queryDefinition) {
            // Base table
            $baseTable = $queryDefinition->base_table;

            // Select columns
            $selectColumns = [];
            if (!empty($queryDefinition->select_columns)) {
                $selectColumns = explode(',', $queryDefinition->select_columns);
            } else {
                // Fetch all columns if none are defined
                $tableSchema = Yii::$app->db->getTableSchema($baseTable);
                $selectColumns = $tableSchema->getColumnNames();
            }

            // Exclude certain columns
            $selectColumns = array_diff($selectColumns, $excludeColumns);

            // Initialize query builder
            $query = (new Query())
                ->select($selectColumns)
                ->from($baseTable);

            // Joins
            if (!empty($queryDefinition->joins)) {
                $joins = json_decode($queryDefinition->joins, true);
                foreach ($joins as $join) {
                    $query->join($join['type'], $join['table'], $join['condition']);
                }
            }

            // Where conditions
            if (!empty($queryDefinition->where_conditions)) {
                $whereConditions = json_decode($queryDefinition->where_conditions, true);
                $query->where($whereConditions);
            }

            // Group by columns
            if (!empty($queryDefinition->group_by_columns)) {
                $groupByColumns = explode(',', $queryDefinition->group_by_columns);
                $query->groupBy($groupByColumns);
            }

            // Having conditions
            if (!empty($queryDefinition->having_conditions)) {
                $havingConditions = json_decode($queryDefinition->having_conditions, true);
                $query->having($havingConditions);
            }

            // Order by columns
            if (!empty($queryDefinition->order_by_columns)) {
                $orderByColumns = json_decode($queryDefinition->order_by_columns, true);
                $query->orderBy($orderByColumns);
            }

            // Execute the query and fetch results
            $results = $query->all();

            // Use the 'name' column for the sheet name, ensuring it's a valid sheet name
            $sheetName = $queryDefinition->name ?: 'Sheet' . ($index + 1);

            // Check if the sheet exists
            $sheet = null;
            foreach ($spreadsheet->getAllSheets() as $existingSheet) {
                if ($existingSheet->getTitle() === $sheetName) {
                    $sheet = $existingSheet;
                    // Clear existing sheet data
                    $sheet->removeRow(2, $sheet->getHighestRow());
                    break;
                }
            }

            // Create a new worksheet if the sheet does not exist
            if ($sheet === null) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle(substr($sheetName, 0, 31)); // Excel sheet names must be <= 31 characters
            }

            // Set the tab color (e.g., yellow)
            $sheet->getTabColor()->setRGB('34495e');

            // Add column headers
            $columnIndex = 'A';
            foreach ($selectColumns as $column) {
                $sheet->setCellValue($columnIndex . '1', $column);
                $columnIndex++;
            }

            // Style the header row: bold text, grey background, and autofilter
            $headerRange = 'A1:' . $sheet->getHighestDataColumn() . '1';
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D3D3D3', // Light grey color
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
            $sheet->setAutoFilter($headerRange);

            // Add data rows
            $rowIndex = 2;
            foreach ($results as $result) {
                $columnIndex = 'A';
                foreach ($selectColumns as $column) {
                    $sheet->setCellValue($columnIndex . $rowIndex, $result[$column]);
                    $columnIndex++;
                }
                $rowIndex++;
            }

            // Autofit columns
            foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }


        // set focus to first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Set the file name or default to a timestamped name with dynamic fields
        if (empty($fileName)) {
            $fileName = 'report_' . date('Y-m-d_His') . '.xlsx';
        } else {
            // Replace placeholders in the file name with dynamic values
            $fileName = str_replace(['{date}', '{time}', '{datetime}'], [date('Y-m-d'), date('His'), date('Y-m-d_His')], $fileName);

            // Ensure the file name has a .xlsx extension
            if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'xlsx') {
                $fileName .= '.xlsx';
            }
        }

        // Create a writer and save the file to a temporary location
        $tempFilePath = Yii::getAlias('@runtime') . '/' . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        // Return the file path of the generated Excel file
        return $tempFilePath;
    }


    /**
     * Converts a response of application/vnd.sdmx.genericdata+xml;version=2.1;charset=UTF-8 into a PHP array.
     *
     * @param string $xmlResponse The XML response string.
     * @return array|string The converted PHP array, or the original content with an error message.
     */
    public static function convertSdmxXmlToArray($responseContent)
    {
        try {
            // Remove namespaces from the XML to simplify parsing
            $responseContent = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$3', $responseContent);

            // Parse XML into SimpleXMLElement
            $xml = new SimpleXMLElement($responseContent);

            // Check if the XML contains a GenericData message
            if ($xml->getName() == 'GenericData') {
                // Convert SimpleXMLElement to array
                $json = json_encode($xml);
                $array = json_decode($json, true);

                return $array;
            } elseif ($xml->getName() == 'message') {
                // Handle SDMX error message
                $errorMessage = (string) $xml->xpath('//message:Text')[0];
                return 'Failed to parse SDMX XML: ' . $errorMessage . "\nOriginal Content: " . $responseContent;
            } else {
                // Unexpected XML structure
                return 'Failed to parse SDMX XML: Unexpected XML structure' . "\nOriginal Content: " . $responseContent;
            }
        } catch (Exception $e) {
            // Handle parsing errors
            return 'Failed to parse SDMX XML: ' . $e->getMessage() . "\nOriginal Content: " . $responseContent;
        }
    }

    /**
     * Cleans up the XML string by removing invalid characters and fixing common issues.
     *
     * @param string $xml The XML string to clean up.
     * @return string The cleaned XML string.
     */
    private static function cleanXmlString($xml)
    {
        // Remove any control characters except for line breaks and spaces
        $xml = preg_replace('/[^\P{C}\n\r\t]+/u', '', $xml);

        // Optionally, you can fix other common XML issues here
        // For example, replacing invalid characters or sequences

        return $xml;
    }
}
