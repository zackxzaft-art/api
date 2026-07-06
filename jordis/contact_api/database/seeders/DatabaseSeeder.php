<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contact;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()

            ->count(10)

            ->create()

            ->each(function ($user) {

                Contact::factory()

                    ->count(5)

                    ->create([

                        'user_id' => $user->id

                    ]);

            });

    }
}