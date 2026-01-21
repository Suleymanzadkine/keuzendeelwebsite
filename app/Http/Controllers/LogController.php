<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display the logs
     */
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            $logs = [];
            $message = 'Geen logbestanden gevonden.';
        } else {
            $contents = file_get_contents($logPath);
            
            // Parse Laravel logs - they're in [timestamp] level format
            $logs = [];
            $currentEntry = '';
            $lines = explode("\n", $contents);
            
            foreach ($lines as $line) {
                // Check if this is a new log entry (starts with [)
                if (preg_match('/^\[[\d\-]+ [\d\:\.]+\]/', $line)) {
                    // Save previous entry if exists
                    if ($currentEntry !== '') {
                        $logs[] = $currentEntry;
                    }
                    $currentEntry = $line;
                } else {
                    // Continuation of previous entry
                    if ($currentEntry !== '' && trim($line) !== '') {
                        $currentEntry .= "\n" . $line;
                    }
                }
            }
            
            // Don't forget the last entry
            if ($currentEntry !== '') {
                $logs[] = $currentEntry;
            }
            
            // Reverse to show newest first
            $logs = array_reverse($logs);
            $logs = array_filter($logs); // Remove empty entries
            
            // Paginate
            $perPage = 20;
            $page = request()->get('page', 1);
            $logs = array_slice($logs, ($page - 1) * $perPage, $perPage);
            
            $message = null;
        }
        
        return view('logs.index', compact('logs', 'message'));
    }
    
    /**
     * Clear the logs
     */
    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
        }
        
        return redirect()->route('logs.index')->with('success', 'Logbestanden zijn gewist.');
    }
    
    /**
     * Download the logs
     */
    public function download()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!file_exists($logPath)) {
            return redirect()->route('logs.index')->with('error', 'Geen logbestand beschikbaar.');
        }
        
        return response()->download($logPath, 'laravel.log', [
            'Content-Type' => 'text/plain',
        ]);
    }
}
