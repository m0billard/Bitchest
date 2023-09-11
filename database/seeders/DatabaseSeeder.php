<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    // Admin
    User::create([
      'name' => 'Jérôme',
      'email' => 'admin@bitchest.com',
      'password' => '$2y$10$5qg5yIQlLeSvQVtdlbAW5Od/qHL5mNqGjiQDYuPUyvF5CeUTMynJy', //admin123
    ]);
    UserRole::create([
      'user_id' => 1,
      'role' => 'ADMIN',
    ]);

    // Utilisateur 2
    User::create([
      'name' => 'Sophie',
      'email' => 'sophie@gmail.com',
      'password' => bcrypt('sophiepass'),
    ]);
    UserRole::create([
      'user_id' => 2,
      'role' => 'CUSTOMER',
    ]);

    // Utilisateur 3
    User::create([
      'name' => 'Lucas',
      'email' => 'lucas@gmail.com',
      'password' => bcrypt('lucas456'),
    ]);
    UserRole::create([
      'user_id' => 3,
      'role' => 'CUSTOMER',
    ]);

    // Utilisateur 4
    User::create([
      'name' => 'Emilie',
      'email' => 'emilie@gmail.com',
      'password' => bcrypt('emilie789'),
    ]);
    UserRole::create([
      'user_id' => 4,
      'role' => 'CUSTOMER',
    ]);

    // Utilisateur 5
    User::create([
      'name' => 'Antoine',
      'email' => 'antoine@gmail.com',
      'password' => bcrypt('antoinepass'),
    ]);
    UserRole::create([
      'user_id' => 5,
      'role' => 'CUSTOMER',
    ]);

    // Utilisateur 6
    User::create([
      'name' => 'Julie',
      'email' => 'julie@gmail.com',
      'password' => bcrypt('julie321'),
    ]);
    UserRole::create([
      'user_id' => 6,
      'role' => 'CUSTOMER',
    ]);

    $this->call([
      CryptoCurrencySeeder::class,
  ]);
  }
}
