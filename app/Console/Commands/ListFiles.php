<?php

namespace App\Console\Commands;

use App\Models\FileModel;
use Illuminate\Console\Command;

class ListFiles extends Command
{
    protected $signature = 'files:list';
    protected $description = 'List all files in database';

    public function handle()
    {
        $files = FileModel::all(['id', 'title']);
        
        if ($files->isEmpty()) {
            $this->info('No files found in database.');
            return;
        }
        
        $this->info('Files in database:');
        foreach ($files as $file) {
            $this->line("ID: {$file->id} - Title: {$file->title}");
        }
    }
}
