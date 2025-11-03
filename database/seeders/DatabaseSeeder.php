<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Counties;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    //"php artisan db:seed" és "php artisan db:seed --class:TownsFromCsvSeeder" -t kell használni, mivel valamiért a towns feltöltés nem akar működni ¯\_(ツ)_/¯
    public function run(): void
    {
        User::factory() ->create([
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'asd',
        ]);

    }
}
