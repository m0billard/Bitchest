<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GenerateDailyReport extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'report:daily-users';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate a daily report of registered users';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $date = Carbon::now()->format('Y-m-d');
    $users = User::all();

    $report = "Report for {$date}\n";
    $report .= "====================\n";

    $this->info("Number of users: " . $users->count());

    foreach ($users as $user) {
      $report .= "ID: {$user->id}\n";
      $report .= "Name: {$user->name}\n";
      $report .= "Email: {$user->email}\n";
      $report .= "Contact Number: " . ($user->contact_number ?? 'N/A') . "\n";
      $report .= "Address: " . ($user->address ?? 'N/A') . "\n";
      $report .= "Wallet Cash: " . ($user->wallet_cash ?? '0') . "\n";
      $report .= "-------------------\n";
    }

    $path = "C:\\Users\\Morgan\\Desktop\\reports-daily-{$date}.txt";
    file_put_contents($path, $report);

    $this->info("Daily report for {$date} generated successfully!");

    return 0;
  }
}
