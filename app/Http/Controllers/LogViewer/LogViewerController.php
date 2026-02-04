<?php

namespace App\Http\Controllers\LogViewer;

use App\Http\Controllers\Controller;
use App\Services\LogViewerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class LogViewerController extends Controller
{
    protected $logViewerService;

    public function __construct(LogViewerService $logViewerService)
    {
        $this->logViewerService = $logViewerService;
    }

    /**
     * Display the log viewer dashboard
     */
    public function index(Request $request)
    {
        $file = $request->get('file', null);
        $sort = $request->get('sort', 'newest');
        $perPage = (int) $request->get('per_page', 25);
        $page = (int) $request->get('page', 1);
        
        // Get all log files
        $logFiles = $this->logViewerService->getLogFiles($sort);
        
        // If no file specified, use the newest one
        if (empty($file) && !empty($logFiles)) {
            $file = $logFiles[0]['name'];
        }
        
        // Prepare filters - handle multiple levels
        $levels = $request->get('levels', []);
        if (!is_array($levels)) {
            $levels = $levels ? explode(',', $levels) : [];
        }
        $levels = array_filter($levels); // Remove empty values
        
        $filters = [
            'levels' => $levels,
            'level' => $request->get('level'), // Backward compatibility
            'search' => $request->get('search'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];
        
        // Parse log file
        $logData = [];
        $statistics = [];
        if ($file) {
            // Get statistics from all entries (no filters)
            $statistics = $this->logViewerService->getLogStatistics($file);
            
            // Parse with filters for display
            $parsed = $this->logViewerService->parseLogFile($file, $filters);
            
            // Paginate entries
            $total = count($parsed['entries']);
            $offset = ($page - 1) * $perPage;
            $logData = [
                'entries' => array_slice($parsed['entries'], $offset, $perPage),
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => max(1, ceil($total / $perPage)),
                'levels' => $parsed['levels'],
            ];
        }
        
        return view('admin.log-viewer.index', compact('logFiles', 'file', 'logData', 'statistics', 'sort', 'perPage', 'filters'));
    }

    /**
     * Show a specific log entry
     */
    public function show(Request $request, $file, $line)
    {
        $entry = $this->logViewerService->getLogEntry($file, $line);
        
        if (!$entry) {
            abort(404, 'Log entry not found');
        }
        
        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Download log file
     */
    public function download($file)
    {
        $filePath = storage_path('logs/' . $file);
        
        if (!file_exists($filePath)) {
            abort(404, 'Log file not found');
        }
        
        return response()->download($filePath, $file);
    }

    /**
     * Delete log file
     */
    public function delete($file)
    {
        $filePath = storage_path('logs/' . $file);
        
        if (!file_exists($filePath)) {
            abort(404, 'Log file not found');
        }
        
        unlink($filePath);
        
        return redirect()->route('log-viewer.index')
            ->with('success', 'Log file deleted successfully');
    }

    /**
     * Clear index/cache for log file
     */
    public function clearIndex($file)
    {
        try {
            // Clear Laravel cache
            Artisan::call('cache:clear');
            
            // Clear config cache
            Artisan::call('config:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Index cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete multiple log files
     */
    public function deleteMultiple(Request $request)
    {
        $files = $request->input('files', []);
        $deleted = [];
        $errors = [];
        
        foreach ($files as $file) {
            $filePath = storage_path('logs/' . $file);
            
            if (file_exists($filePath)) {
                try {
                    unlink($filePath);
                    $deleted[] = $file;
                } catch (\Exception $e) {
                    $errors[] = $file . ': ' . $e->getMessage();
                }
            } else {
                $errors[] = $file . ': File not found';
            }
        }
        
        $message = '';
        if (count($deleted) > 0) {
            $message = count($deleted) . ' file(s) deleted successfully.';
        }
        if (count($errors) > 0) {
            $message .= ' Errors: ' . implode(', ', $errors);
        }
        
        return redirect()->route('log-viewer.index')
            ->with('success', $message ?: 'No files were deleted');
    }
}

