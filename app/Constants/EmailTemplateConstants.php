<?php

namespace App\Constants;

class EmailTemplateConstants
{
    // Welcome and Onboarding
    const WELCOME_EMAIL = 'WELCOME_EMAIL';
    const OTP_EMAIL_VERIFICATION = 'OTP_EMAIL_VERIFICATION';
    
    // Account and Trading
    const MLL_TRIGGER_EMAIL = 'MLL_TRIGGER_EMAIL';
    const PAYOUT_DISBURSED_EMAIL = 'PAYOUT_DISBURSED_EMAIL';
    const NEWS_TRADE = 'NEWS_TRADE';
    
    // Note: These constants should match the constants in EmailConstants
    // and the 'constant' column in the email_types table
    
    // Add more email template constants as needed
    // const ACCOUNT_ACTIVATED_EMAIL = 'ACCOUNT_ACTIVATED_EMAIL';
    // const PASSWORD_RESET_EMAIL = 'PASSWORD_RESET_EMAIL';
    // const ACCOUNT_SUSPENDED_EMAIL = 'ACCOUNT_SUSPENDED_EMAIL';
}

