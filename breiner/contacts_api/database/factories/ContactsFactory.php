<?php

namespace Database\Factories;

use App\Models\Contactsphp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contacts.php>
 */
class ContactsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->firstName(),
            'user_id'=> 1,
            'phone_number' =>fake()->text()
        ];
    }
}
