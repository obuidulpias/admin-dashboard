<?php

namespace Database\Seeders;

use App\Constants\EmailConstants;
use App\Models\EmailType;
use Illuminate\Database\Seeder;

class EmailTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $constants = [
            EmailConstants::WELCOME_EMAIL,
            EmailConstants::OTP_EMAIL_VERIFICATION,
            EmailConstants::MLL_TRIGGER_EMAIL,
            EmailConstants::PAYOUT_DISBURSED_EMAIL,
            EmailConstants::NEWS_TRADE,
        ];

        foreach ($constants as $constant) {
            // Convert constant value to readable name (e.g., 'welcome-email' => 'Welcome Email')
            $name = ucwords(str_replace('-', ' ', $constant));
            
            EmailType::firstOrCreate(
                ['constant' => $constant],
                ['name' => $name]
            );
        }

        $this->command->info('Email types seeded successfully.');
    }
}
