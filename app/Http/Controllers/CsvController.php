<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;
class CsvController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function uploadCsv()
    {
        return view('upload-csv');
    }
    public function sortCsv(Request $request)
    {
        // Check if the CSV file is uploaded
        if (!$request->hasFile('csv_file')) {
            return back()->withErrors(['csv_file' => 'Please upload a CSV file']);
        }
    
        // Get the uploaded CSV file
        $csvFile = $request->file('csv_file');
    
        // Check if the CSV file is valid
        if (!$csvFile->isValid()) {
            return back()->withErrors(['csv_file' => 'Invalid CSV file']);
        }
    
        // Read the CSV file using League\Csv\Reader
        $reader = Reader::createFromPath($csvFile->getPathname(), 'r');
        $reader->setHeaderOffset(0);
    
        // Get records from the CSV file
        $records = iterator_to_array($reader->getRecords());
    
        // Get the header
        $header = $reader->getHeader();
    
        // Sort the records by the second column in descending order
        usort($records, function ($a, $b) use ($header) {
            return $b[$header[1]] <=> $a[$header[1]];
        });
    
        // Create a new CSV file using League\Csv\Writer
        $writer = Writer::createFromFileObject(new \SplTempFileObject());
        $writer->insertOne($header); // Add header row
        $writer->insertAll($records);
    
        // Store the sorted CSV file in storage
        $sortedCsvFile = 'sorted_' . $csvFile->getClientOriginalName();
        Storage::put($sortedCsvFile, $writer->getContent());
    
        // Return a download response for the sorted CSV file
        return response()->download(storage_path('app/' . $sortedCsvFile), $sortedCsvFile);
    }
}