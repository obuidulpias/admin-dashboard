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
        $constants = EmailConstants::all();

        foreach ($constants as $constant) {
            EmailType::firstOrCreate(
                ['constant' => $constant],
                ['name' => EmailConstants::getName($constant)]
            );
        }

        $this->command->info('Email types seeded successfully.');
    }
}
