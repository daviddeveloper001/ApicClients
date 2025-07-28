<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ApiClient;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        ApiClient::updateOrCreate([
            'name' => 'Api Client 1',
            'token' => hash('sha256', 'token1'),
            'permissions' =>['read_customers'],
        ]);


        $this->call([
            CustomerSeeder::class,
        ]);
    }
}
