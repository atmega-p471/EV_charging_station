<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Запуск заполнения базы данных.
     */
    public function run(): void
    {
        // 1. Администратор (Admin)
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@test.ru',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Владелец станций (Owner)
        User::create([
            'name' => 'Владелец ЭЗС',
            'email' => 'owner@test.ru',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // 3. Водитель / клиент (Driver / User)
        User::create([
            'name' => 'Водитель Иван',
            'email' => 'driver@test.ru',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);
    }
}