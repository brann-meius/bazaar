<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(3)
            ->sequence([
                'email' => 'owner@gmail.com',
            ], [
                'email' => 'moderator@gmail.com',
            ], [
                'email' => 'customer@gmail.com',
            ])
            ->create();
    }
}
