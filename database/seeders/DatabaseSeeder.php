<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create([
             'name' => 'Anand Kumar',
             'email' => 'anand@mickaido.com',
             'password' => 'Tets@123'
         ]);

        $this->call([
            RoleAndPermissionSeeder::class,
        ]);
    }
}
