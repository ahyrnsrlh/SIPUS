<?php

use App\Models\FileModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::get('/debug-user', function () {
    $user = Auth::user();
    $file = FileModel::with('uploader')->first();
    
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    if (!$file) {
        return response()->json(['error' => 'No files found']);
    }
    
    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'instansi' => $user->instansi,
            'isApproved' => $user->isApproved(),
            'isAdmin' => $user->isAdmin(),
        ],
        'file' => [
            'id' => $file->id,
            'title' => $file->title,
            'uploaded_by' => $file->uploaded_by,
            'uploader_instansi' => $file->uploader ? $file->uploader->instansi : null,
        ],
        'permissions' => [
            'can_view' => Gate::forUser($user)->allows('view', $file),
            'can_download' => Gate::forUser($user)->allows('download', $file),
            'can_update' => Gate::forUser($user)->allows('update', $file),
            'can_delete' => Gate::forUser($user)->allows('delete', $file),
        ]
    ]);
})->middleware(['auth', 'approved']);
