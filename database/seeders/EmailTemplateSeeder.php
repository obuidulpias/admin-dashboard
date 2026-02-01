<?php

namespace Database\Seeders;

use App\Constants\EmailConstants;
use App\Models\EmailTemplate;
use App\Models\EmailType;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            EmailConstants::WELCOME_EMAIL => [
                'subject' => 'Welcome {{name}} to FundedNext',
                'body' => '<h1>Welcome {{name}}!</h1>
<p>Thank you for joining FundedNext. We are excited to have you on board.</p>
<p>Your account {{account_number}} has been successfully created and is now active.</p>
<p>If you have any questions, please don\'t hesitate to contact our support team.</p>
<p>Best regards,<br>The FundedNext Team</p>',
                'variables' => ['name', 'account_number'],
            ],
            EmailConstants::OTP_EMAIL_VERIFICATION => [
                'subject' => 'Your OTP Verification Code',
                'body' => '<h1>Hello {{name}},</h1>
<p>Your OTP verification code is: <strong>{{otp_code}}</strong></p>
<p>This code will expire in 10 minutes. Please do not share this code with anyone.</p>
<p>If you did not request this code, please ignore this email.</p>
<p>Best regards,<br>The FundedNext Team</p>',
                'variables' => ['name', 'otp_code'],
            ],
            EmailConstants::MLL_TRIGGER_EMAIL => [
                'subject' => 'MLL Trigger Alert for Account {{account_number}}',
                'body' => '<h1>MLL Trigger Notification</h1>
<p>Hello {{name}},</p>
<p>This is to inform you that the Maximum Loss Limit (MLL) has been triggered for your account {{account_number}}.</p>
<p>Please review your account status and take necessary actions.</p>
<p>If you have any questions, please contact our support team.</p>
<p>Best regards,<br>The FundedNext Team</p>',
                'variables' => ['name', 'account_number'],
            ],
            EmailConstants::PAYOUT_DISBURSED_EMAIL => [
                'subject' => 'Payout Disbursed - Account {{account_number}}',
                'body' => '<h1>Payout Disbursed</h1>
<p>Hello {{name}},</p>
<p>We are pleased to inform you that your payout has been successfully disbursed.</p>
<p><strong>Account Number:</strong> {{account_number}}</p>
<p><strong>Payout Amount:</strong> {{payout_amount}}</p>
<p><strong>Transaction Date:</strong> {{transaction_date}}</p>
<p>The funds should be available in your account shortly.</p>
<p>If you have any questions, please contact our support team.</p>
<p>Best regards,<br>The FundedNext Team</p>',
                'variables' => ['name', 'account_number', 'payout_amount', 'transaction_date'],
            ],
        ];

        foreach ($templates as $constant => $templateData) {
            $emailType = EmailType::where('constant', $constant)->first();
            
            if ($emailType) {
                EmailTemplate::firstOrCreate(
                    [
                        'email_type_id' => $emailType->id,
                    ],
                    [
                        'subject' => $templateData['subject'],
                        'body' => $templateData['body'],
                        'variables' => $templateData['variables'],
                    ]
                );
            }
        }

        $this->command->info('Email templates seeded successfully.');
    }
}
