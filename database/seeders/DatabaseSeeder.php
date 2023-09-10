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

    User::create([
      'name' => 'Admin',
      'email' => 'admin@bitchest.com',
      'password' => '$2y$10$5qg5yIQlLeSvQVtdlbAW5Od/qHL5mNqGjiQDYuPUyvF5CeUTMynJy', //admin123
    ]);
    UserRole::create([
      'user_id' => 1,
      'role' => 'ADMIN',
    ]);
  }
}
