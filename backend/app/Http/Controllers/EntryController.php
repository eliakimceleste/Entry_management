<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use App\Models\Entry;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Entry::orderBy('arrival_time', 'desc')->paginate(10);
    }

    private function logEntry(array $data)
    {
        $logDir = storage_path('logs/');

        // 📁 Assurer que le dossier existe
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // 📌 Format JSON (Append Mode)
        $jsonLogFile = $logDir . 'entries.json';
        $jsonData = file_exists($jsonLogFile) ? json_decode(file_get_contents($jsonLogFile), true) : [];
        $jsonData[] = $data;
        file_put_contents($jsonLogFile, json_encode($jsonData, JSON_PRETTY_PRINT));

        // 📌 Format CSV (Append Mode)
        $csvLogFile = $logDir . 'entries.csv';
        $csvHeader = ['timestamp', 'ip', 'user_agent', 'event', 'details'];
        $csvData = [
            $data['timestamp'],
            $data['ip'],
            $data['user_agent'],
            $data['event'],
            json_encode($data['details'])
        ];

        $csvExists = file_exists($csvLogFile);
        $csvFile = fopen($csvLogFile, 'a');
        if (!$csvExists) {
            fputcsv($csvFile, $csvHeader); // Ajouter l'en-tête si le fichier est nouveau
        }
        fputcsv($csvFile, $csvData);
        fclose($csvFile);
    }

    // Modifier store() pour utiliser logEntry()
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        try {
            $entry = Entry::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'arrival_time' => now(),
            ]);

            // 📝 Enregistrement des logs structurés
            $this->logEntry([
                'timestamp' => now()->toIso8601String(),
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'event' => 'entry',
                'details' => [
                    'first_name' => $entry->first_name,
                    'last_name' => $entry->last_name
                ]
            ]);

            return response()->json($entry, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //     ]);

    //     $entry = Entry::create([
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'arrival_time' => now(),
    //     ]);

    //     // 📝 Log d'information
    //     Log::channel('monitoring')->info("Nouvelle entrée enregistrée",[ 
    //         'first_name' => $entry->first_name,
    //         'last_name' => $entry->last_name,
    //         'arrival_time' => $entry->arrival_time,
    //         'ip' => $request->ip(),
    //         'user_agent' => $request->header('User-Agent')
    //     ]); 

    //     return response()->json($entry, 201);
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
