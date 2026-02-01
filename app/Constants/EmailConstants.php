<?php

namespace App\Constants;

class EmailConstants
{
    // Welcome and Onboarding
    const WELCOME_EMAIL = 'WELCOME_EMAIL';
    const OTP_EMAIL_VERIFICATION = 'OTP_EMAIL_VERIFICATION';
    
    // Account and Trading
    const MLL_TRIGGER_EMAIL = 'MLL_TRIGGER_EMAIL';
    const PAYOUT_DISBURSED_EMAIL = 'PAYOUT_DISBURSED_EMAIL';
    const NEWS_TRADE = 'NEWS_TRADE';
    
    // Email Links
    const LIVE_CHAT_LINK = 'https://example.com/live-chat';
    
    // Add more email constants as needed
    // const ACCOUNT_ACTIVATED_EMAIL = 'ACCOUNT_ACTIVATED_EMAIL';
    // const PASSWORD_RESET_EMAIL = 'PASSWORD_RESET_EMAIL';
    // const ACCOUNT_SUSPENDED_EMAIL = 'ACCOUNT_SUSPENDED_EMAIL';
    
    /**
     * Get all email constants as an array
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::WELCOME_EMAIL,
            self::OTP_EMAIL_VERIFICATION,
            self::MLL_TRIGGER_EMAIL,
            self::PAYOUT_DISBURSED_EMAIL,
            self::NEWS_TRADE,
        ];
    }
    
    /**
     * Get constant name (without class prefix)
     *
     * @param string $constant
     * @return string
     */
    public static function getName(string $constant): string
    {
        $names = [
            self::WELCOME_EMAIL => 'Welcome Email',
            self::OTP_EMAIL_VERIFICATION => 'OTP Email Verification',
            self::MLL_TRIGGER_EMAIL => 'MLL Trigger Email',
            self::PAYOUT_DISBURSED_EMAIL => 'Payout Disbursed Email',
            self::NEWS_TRADE => 'News Trade Email',
        ];
        
        return $names[$constant] ?? $constant;
    }
}

