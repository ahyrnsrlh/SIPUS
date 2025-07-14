<?php

namespace App\Console\Commands;

use App\Models\FileModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;

class DebugAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:auth {user_id?} {file_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug authorization for file access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;
        $fileId = $this->argument('file_id') ?? 1;

        $user = User::find($userId);
        $file = FileModel::with('uploader')->find($fileId);

        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        if (!$file) {
            $this->error("File with ID {$fileId} not found");
            return;
        }

        $this->info("=== Authorization Debug ===");
        $this->info("User ID: {$user->id}");
        $this->info("User Name: {$user->name}");
        $this->info("User Email: {$user->email}");
        $this->info("User Role: {$user->role}");
        $this->info("User Status: {$user->status}");
        $this->info("User Instansi: " . ($user->instansi ?? 'null'));
        $this->info("User isApproved(): " . ($user->isApproved() ? 'true' : 'false'));
        $this->info("User isAdmin(): " . ($user->isAdmin() ? 'true' : 'false'));
        
        $this->info("");
        $this->info("File ID: {$file->id}");
        $this->info("File Title: {$file->title}");
        $this->info("File uploaded_by: {$file->uploaded_by}");
        $this->info("File uploader exists: " . ($file->uploader ? 'true' : 'false'));
        if ($file->uploader) {
            $this->info("File uploader instansi: " . ($file->uploader->instansi ?? 'null'));
        }
        
        $this->info("");
        $this->info("=== Authorization Results ===");
        
        // Test authorization using Gate
        $canView = Gate::forUser($user)->allows('view', $file);
        $canDownload = Gate::forUser($user)->allows('download', $file);
        $canUpdate = Gate::forUser($user)->allows('update', $file);
        $canDelete = Gate::forUser($user)->allows('delete', $file);
        
        $this->info("Can View: " . ($canView ? 'YES' : 'NO'));
        $this->info("Can Download: " . ($canDownload ? 'YES' : 'NO'));
        $this->info("Can Update: " . ($canUpdate ? 'YES' : 'NO'));
        $this->info("Can Delete: " . ($canDelete ? 'YES' : 'NO'));

        $this->info("");
        $this->info("=== Policy Logic Check ===");
        
        // Manual checks
        $ownsFile = $user->id === $file->uploaded_by;
        $sameInstansi = $user->instansi && $file->uploader && $user->instansi === $file->uploader->instansi;
        
        $this->info("Owns file: " . ($ownsFile ? 'true' : 'false'));
        $this->info("Same instansi: " . ($sameInstansi ? 'true' : 'false'));
    }
}
