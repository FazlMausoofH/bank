<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Centrall;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Agis',
            'email' => 'agis@sm.com',
            'password' => Hash::make('12345agis'),
        ]);
        User::create([
            'name' => 'Erni',
            'email' => 'erni@sm.com',
            'password' => Hash::make('12345erni'),
        ]);
        User::create([
            'name' => 'Lisna',
            'email' => 'lisna@sm.com',
            'password' => Hash::make('12345lisna'),
        ]);
        User::create([
            'name' => 'A',
            'email' => 'a@sm.com',
            'password' => Hash::make('12345a'),
        ]);
        User::create([
            'name' => 'B',
            'email' => 'b@sm.com',
            'password' => Hash::make('12345b'),
        ]);
        User::create([
            'name' => 'c',
            'email' => 'c@sm.com',
            'password' => Hash::make('12345c'),
        ]);

    //    // Centrall dummy
    //     Centrall::create([
    //         'date' => '2025-07-24',
    //         'amount' => 100000.00,
    //         'account_holder' => 'hamid',
    //         'type' => 'bca',
    //     ]);

    //     // Bank dummy
    //     Bank::create([
    //         'date' => '2025-07-24',
    //         'amount' => 100000.00,
    //         'account_holder' => 'hamid',
    //         'type' => 'bca',
    //     ]);

    //     Bank::create([
    //         'date' => '2025-07-24',
    //         'amount' => 150000.00,
    //         'account_holder' => 'hamid',
    //         'type' => 'mandiri',
    //     ]);
    }
}
