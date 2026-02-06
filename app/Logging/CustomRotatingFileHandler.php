<?php

namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;

class CustomRotatingFileHandler extends RotatingFileHandler
{
    /**
     * Constructor - override to use custom date format (YYYY-MM-DD)
     */
    public function __construct(
        string $filename,
        int $maxFiles = 0,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
        ?int $filePermission = null,
        bool $useLocking = false
    ) {
        // Convert string level to Level enum if needed
        if (is_string($level)) {
            $level = Level::fromName(ucfirst(strtolower($level)));
        }
        
        // Use date format: 'Y-m-d' 
        // This creates files like: laravel-2026-02-06.log
        parent::__construct(
            $filename,
            $maxFiles,
            $level,
            $bubble,
            $filePermission,
            $useLocking,
            'Y-m-d', // Date format: YYYY-MM-DD
            '{filename}-{date}' // Filename format
        );
    }
}
