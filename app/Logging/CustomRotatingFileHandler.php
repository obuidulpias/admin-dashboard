<?php

namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\LogRecord;

class CustomRotatingFileHandler extends RotatingFileHandler
{
    /**
     * Constructor - override to use custom date format (DD-MM-YYYY)
     */
    public function __construct(
        string $filename,
        int $maxFiles = 0,
        int|string|Level $level = Level::Debug,
        bool $bubble = true,
        ?int $filePermission = null,
        bool $useLocking = false
    ) {
        // Use custom date format: 'd-m-Y' instead of default 'Y-m-d'
        // This creates files like: laravel-04-02-2026.log
        parent::__construct(
            $filename,
            $maxFiles,
            $level,
            $bubble,
            $filePermission,
            $useLocking,
            'd-m-Y', // Custom date format: DD-MM-YYYY
            '{filename}-{date}' // Filename format
        );
    }
}
