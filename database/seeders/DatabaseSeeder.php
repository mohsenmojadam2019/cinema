<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(CinemaSeeder::class);
        $this->call(PermissionSeeder::class);
        $admin=User::firstOrCreate(['email'=>'admin@cinemaplus.test'],['name'=>'مدیر سیستم','password'=>Hash::make('ChangeMe123!')]);
        $admin->assignRole(\Spatie\Permission\Models\Role::findByName('مدیر سیستم','web'));
    }
}
