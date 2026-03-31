<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    public function run(): void
    {
        ApiKey::generate('Mobile App', 365);
        ApiKey::generate('Web App', 365);
        ApiKey::generate('Demo Key', 7);
    }
}
