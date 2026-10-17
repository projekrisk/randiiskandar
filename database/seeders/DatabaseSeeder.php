<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'randi@example.com'],
            [
                'name' => 'Randi Iskandar',
                'password' => Hash::make('password'),
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop',
                'bio' => 'Pengembang web dan penulis lepas. Membagikan portofolio, opini seputar teknologi, tutorial pemrograman, dan catatan eksplorasi digital.',
            ]
        );

        $this->call(PageAndMenuSeeder::class);
    }
}
