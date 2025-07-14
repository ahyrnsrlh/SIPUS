<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all(['id', 'name', 'email', 'instansi', 'role', 'status']);
        
        $this->info("Total users: " . $users->count());
        $this->info("");
        
        foreach ($users as $user) {
            $this->info("ID: {$user->id} | Name: {$user->name} | Email: {$user->email}");
            $this->info("   Instansi: " . ($user->instansi ?? 'null') . " | Role: {$user->role} | Status: {$user->status}");
            $this->info("");
        }
    }
}
