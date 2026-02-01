<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\EmailType;
use App\Services\CentralEmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CentralEmailJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     *
     * @param array $details Array containing:
     *   - 'email' => recipient email address
     *   - 'templateName' => email template constant
     *   - 'data' => array of template variables
     *   - 'link' => array of links (optional)
     */
    public function __construct(
        public array $details
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Extract details
            $email = $this->details['email'] ?? null;
            $templateName = $this->details['templateName'] ?? null;
            $data = $this->details['data'] ?? [];
            $links = $this->details['link'] ?? [];

            if (!$email || !$templateName) {
                Log::error("CentralEmailJob: Missing required fields", ['details' => $this->details]);
                $this->fail(new \Exception("Missing required fields: email or templateName"));
                return;
            }

            // Get email type by template constant
            $emailType = EmailType::where('constant', $templateName)->first();
            
            if (!$emailType) {
                Log::error("Email type not found for template: {$templateName}");
                $this->fail(new \Exception("Email type not found for template: {$templateName}"));
                return;
            }

            // Get the latest template for this email type
            $template = EmailTemplate::where('email_type_id', $emailType->id)
                ->latest()
                ->first();

            if (!$template) {
                Log::error("Email template not found for type: {$templateName}");
                $this->fail(new \Exception("Email template not found for type: {$templateName}"));
                return;
            }

            // Merge data and links for template parsing
            $templateData = array_merge($data, $links);

            // Parse template with data
            $parsedSubject = CentralEmailService::parseTemplate($template->subject, $templateData);
            $parsedBody = CentralEmailService::parseTemplate($template->body, $templateData);

            // Create email log entry
            $emailLog = EmailLog::create([
                'email_type_id' => $emailType->id,
                'to_email' => $email,
                'subject' => $parsedSubject,
                'body' => $parsedBody,
                'status' => 'pending',
                'attempts' => 0,
                'max_attempts' => 3,
            ]);

            // Send email
            $this->sendEmail($emailLog);

            Log::info("Email sent successfully: {$emailLog->to_email} (Log ID: {$emailLog->id}, Template: {$templateName})");
        } catch (\Exception $e) {
            // Try to find or create email log for error tracking
            $emailLog = null;
            if (isset($emailType) && isset($email)) {
                $emailLog = EmailLog::where('email_type_id', $emailType->id)
                    ->where('to_email', $email)
                    ->latest()
                    ->first();
            }

            if ($emailLog) {
                $emailLog->update([
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ]);
            }

            Log::error("Failed to send email: {$email} (Template: {$templateName})", [
                'error' => $e->getMessage(),
                'exception' => $e,
                'details' => $this->details,
            ]);

            // Re-throw to allow queue retry mechanism
            throw $e;
        }
    }

    /**
     * Send the email and update log status.
     */
    private function sendEmail(EmailLog $emailLog): void
    {
        // Check if email can be retried
        if (!$emailLog->canRetry()) {
            Log::info("EmailLog {$emailLog->id} cannot be retried (status: {$emailLog->status}, attempts: {$emailLog->attempts}/{$emailLog->max_attempts})");
            return;
        }

        // Update attempt count and last attempt time before sending
        $emailLog->increment('attempts');
        $emailLog->update([
            'last_attempt_at' => now(),
            'status' => 'pending',
        ]);

        // Send email (support HTML)
        Mail::send([], [], function (Message $message) use ($emailLog) {
            $message->to($emailLog->to_email)
                ->subject($emailLog->subject)
                ->html($emailLog->body);
        });

        // Update log status to sent
        $emailLog->update([
            'status' => 'sent',
            'sent_at' => now(),
            'error' => null,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $email = $this->details['email'] ?? 'unknown';
        $templateName = $this->details['templateName'] ?? 'unknown';

        Log::error("CentralEmailJob failed permanently", [
            'email' => $email,
            'templateName' => $templateName,
            'exception' => $exception,
            'details' => $this->details,
        ]);
    }
}

