<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LogViewerService
{
    protected $logPath;

    public function __construct()
    {
        $this->logPath = storage_path('logs');
    }

    /**
     * Get all log files
     */
    public function getLogFiles($sort = 'newest')
    {
        $files = [];
        
        if (!File::exists($this->logPath)) {
            return $files;
        }

        $logFiles = File::files($this->logPath);
        
        foreach ($logFiles as $file) {
            if (Str::endsWith($file->getFilename(), '.log')) {
                $files[] = [
                    'name' => $file->getFilename(),
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime(),
                ];
            }
        }

        // Sort files
        if ($sort === 'newest') {
            usort($files, function ($a, $b) {
                return $b['modified'] - $a['modified'];
            });
        } else {
            usort($files, function ($a, $b) {
                return $a['modified'] - $b['modified'];
            });
        }

        return $files;
    }

    /**
     * Parse log file and extract entries
     */
    public function parseLogFile($fileName, $filters = [])
    {
        $filePath = $this->logPath . '/' . $fileName;
        
        if (!File::exists($filePath)) {
            return [
                'entries' => [],
                'total' => 0,
                'levels' => [],
            ];
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $entries = [];
        $levels = [];
        $currentEntry = null;
        $lineNumber = 0;
        
        // Laravel log pattern: [YYYY-MM-DD HH:MM:SS] local.LEVEL: message
        $logPattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+):\s*(.*)$/';
        
        foreach ($lines as $line) {
            $lineNumber++;
            
            if (preg_match($logPattern, $line, $matches)) {
                // Save previous entry if exists
                if ($currentEntry !== null) {
                    $entry = $this->processLogEntry($currentEntry, $filters, $lineNumber - 1);
                    if ($entry !== null) {
                        $entries[] = $entry;
                        if (!in_array($entry['level'], $levels)) {
                            $levels[] = $entry['level'];
                        }
                    }
                }
                
                // Start new entry
                $currentEntry = [
                    'datetime' => $matches[1],
                    'environment' => $matches[2],
                    'level' => strtoupper($matches[3]),
                    'message' => trim($matches[4]),
                    'stack_trace' => '',
                    'full' => $line,
                    'line' => $lineNumber,
                ];
            } else {
                // Continuation of previous entry (stack trace, etc.)
                if ($currentEntry !== null) {
                    // Check if this line is part of stack trace
                    $isStackTrace = (
                        strpos($line, 'Stack trace:') !== false || 
                        strpos($line, '#') === 0 || 
                        strpos($line, 'at ') === 0 ||
                        preg_match('/^\s+in\s+/', $line) ||
                        preg_match('/^\s+at\s+/', $line) ||
                        preg_match('/^#\d+/', $line) ||
                        (strpos($line, 'Exception') !== false && !empty($currentEntry['stack_trace']))
                    );
                    
                    if ($isStackTrace || !empty($currentEntry['stack_trace'])) {
                        // If we already have stack trace or this looks like stack trace, add to stack trace
                        $currentEntry['stack_trace'] .= "\n" . $line;
                    } else {
                        // Otherwise, add to message
                        $currentEntry['message'] .= "\n" . $line;
                    }
                    $currentEntry['full'] .= "\n" . $line;
                }
            }
        }
        
        // Save last entry
        if ($currentEntry !== null) {
            $entry = $this->processLogEntry($currentEntry, $filters, $lineNumber);
            if ($entry !== null) {
                $entries[] = $entry;
                if (!in_array($entry['level'], $levels)) {
                    $levels[] = $entry['level'];
                }
            }
        }
        
        // Reverse to show newest first
        $entries = array_reverse($entries);
        
        return [
            'entries' => $entries,
            'total' => count($entries),
            'levels' => $levels,
        ];
    }
    
    /**
     * Process and filter log entry
     */
    protected function processLogEntry($entry, $filters, $lineNumber)
    {
        // Apply level filters (can be array or single value)
        // If levels array is provided and not empty, filter by it
        if (isset($filters['levels']) && is_array($filters['levels']) && !empty($filters['levels'])) {
            $levels = array_map('strtoupper', $filters['levels']);
            if (!in_array($entry['level'], $levels)) {
                return null;
            }
        } elseif (!empty($filters['level_filter_applied']) && empty($filters['levels'])) {
            // Filter was explicitly applied but no levels selected - show nothing
            return null;
        } elseif (!empty($filters['level'])) {
            // Backward compatibility with single level filter
            if ($entry['level'] !== strtoupper($filters['level'])) {
                return null;
            }
        }
        // If no level filter is set, show all entries (default behavior)
        
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            if (strpos(strtolower($entry['message']), $search) === false && 
                strpos(strtolower($entry['level']), $search) === false &&
                strpos(strtolower($entry['stack_trace']), $search) === false) {
                return null;
            }
        }
        
        if (!empty($filters['date_from'])) {
            if (strtotime($entry['datetime']) < strtotime($filters['date_from'])) {
                return null;
            }
        }
        
        if (!empty($filters['date_to'])) {
            if (strtotime($entry['datetime']) > strtotime($filters['date_to'] . ' 23:59:59')) {
                return null;
            }
        }
        
        $entry['line'] = $lineNumber;
        return $entry;
    }

    /**
     * Get log entry by line number (returns full entry with stack trace)
     */
    public function getLogEntry($fileName, $lineNumber)
    {
        $filePath = $this->logPath . '/' . $fileName;
        
        if (!File::exists($filePath)) {
            return null;
        }

        $allEntries = $this->parseLogFile($fileName, []);
        
        // Find entry by line number
        foreach ($allEntries['entries'] as $entry) {
            if ($entry['line'] == $lineNumber) {
                return $entry['full'] ?? ($entry['message'] . "\n" . $entry['stack_trace']);
            }
        }

        return null;
    }

    /**
     * Get log statistics
     */
    public function getLogStatistics($fileName)
    {
        $parsed = $this->parseLogFile($fileName);
        $stats = [
            'total' => $parsed['total'],
            'by_level' => [],
        ];
        
        foreach ($parsed['entries'] as $entry) {
            $level = $entry['level'];
            if (!isset($stats['by_level'][$level])) {
                $stats['by_level'][$level] = 0;
            }
            $stats['by_level'][$level]++;
        }
        
        return $stats;
    }

    /**
     * Format file size
     */
    public function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

