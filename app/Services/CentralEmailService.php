<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use App\Jobs\CentralEmailJob;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\EmailType;
use Illuminate\Support\Facades\Log;

class CentralEmailService
{
    /**
     * Send an email through the centralized system.
     *
     * @param string $emailConstant Email constant from EmailConstants
     * @param string $toEmail Recipient email address
     * @param array $data Dynamic data for template variables
     * @return EmailLog|null
     */
    public static function send(string $emailConstant, string $toEmail, array $data = []): ?EmailLog
    {
        try {
            // Get email type by constant
            $emailType = EmailType::where('constant', $emailConstant)->first();
            
            if (!$emailType) {
                Log::error("Email type not found for constant: {$emailConstant}");
                return null;
            }

            // Get the latest template for this email type
            $template = EmailTemplate::where('email_type_id', $emailType->id)
                ->latest()
                ->first();

            if (!$template) {
                Log::error("Email template not found for type: {$emailConstant}");
                return null;
            }

            // Prepare details for CentralEmailJob
            $details = [
                'email' => $toEmail,
                'templateName' => $emailConstant,
                'data' => $data,
                'link' => [], // Can be extended if needed
            ];

            // Dispatch job to send email (on queue)
            CentralEmailJob::dispatch($details)
                ->onQueue(AppConstants::QUEUE_DEFAULT_JOB);

            // Job will create the email log, so we return null here
            // The actual EmailLog will be created inside CentralEmailJob
            return null;
        } catch (\Exception $e) {
            Log::error("Error in CentralEmailService::send: " . $e->getMessage(), [
                'emailConstant' => $emailConstant,
                'toEmail' => $toEmail,
                'exception' => $e,
            ]);
            return null;
        }
    }

    /**
     * Parse template string and replace variables with data.
     * Removes unreplaced variables automatically.
     *
     * @param string $template Template string with {{variable}} placeholders
     * @param array $data Data array to replace variables
     * @return string Parsed template
     */
    public static function parseTemplate(string $template, array $data): string
    {
        // Replace all {{variable}} with actual values
        $parsed = preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($data) {
            $variableName = $matches[1];
            return isset($data[$variableName]) ? $data[$variableName] : '';
        }, $template);

        // Remove any remaining unreplaced variables (clean up empty {{}} patterns)
        $parsed = preg_replace('/\{\{\w+\}\}/', '', $parsed);

        // Clean up any double spaces or newlines that might result
        $parsed = preg_replace('/\s+/', ' ', $parsed);
        $parsed = trim($parsed);

        return $parsed;
    }

    /**
     * Extract all variables from a template string.
     * Useful for admin interface to show available variables.
     *
     * @param string $template Template string with {{variable}} placeholders
     * @return array Array of unique variable names
     */
    public static function extractVariables(string $template): array
    {
        preg_match_all('/\{\{(\w+)\}\}/', $template, $matches);
        return array_unique($matches[1] ?? []);
    }
}

