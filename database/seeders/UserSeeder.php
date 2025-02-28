<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'dni' => '78589368r',
            'name' => 'Admin',
            'surname' => 'Master',
            'email' => 'admin@admin.com',
            'tipo_usuario' => 'admin', 
            'password' => Hash::make('admin123'),
            'tarifa_id' => null,
        ]);
    }
}
