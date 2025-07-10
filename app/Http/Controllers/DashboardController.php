<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rack;
use App\Models\FileModel;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): Response
    {
        $user = Auth::user();
        
        Log::info('Dashboard accessed', ['user_id' => $user->id, 'email' => $user->email]);
        
        if ($user->role === 'admin') {
            // Admin statistics
            $stats = [
                'totalFiles' => FileModel::count(),
                'totalRacks' => Rack::count(),
                'totalStorage' => $this->calculateTotalStorage(),
                'activeUsers' => User::where('status', 'approved')->where('role', 'user')->count(),
                'pendingUsers' => User::where('status', 'pending')->count(),
            ];

            // Get recent files from all users
            $recentFiles = FileModel::with(['rack', 'subRack', 'uploader'])
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'title' => $file->title,
                        'description' => $file->description,
                        'formatted_size' => $this->formatFileSize($file->size ?? 0),
                        'created_at' => $file->created_at,
                        'rack' => $file->rack,
                        'sub_rack' => $file->subRack,
                        'uploader' => $file->uploader,
                    ];
                });
        } else {
            // User statistics
            $userFiles = FileModel::where('uploaded_by', $user->id);
            $stats = [
                'myFiles' => $userFiles->count(),
                'myStorage' => $this->calculateUserStorage($user->id),
                'recentUploads' => $userFiles->where('created_at', '>=', now()->subWeek())->count(),
                'downloadCount' => 0, // You can implement download tracking later
            ];

            // Get recent files from current user only
            $recentFiles = FileModel::with(['rack', 'subRack', 'uploader'])
                ->where('uploaded_by', $user->id)
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'title' => $file->title,
                        'description' => $file->description,
                        'formatted_size' => $this->formatFileSize($file->size ?? 0),
                        'created_at' => $file->created_at,
                        'rack' => $file->rack,
                        'sub_rack' => $file->subRack,
                        'uploader' => $file->uploader,
                    ];
                });
        }

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentFiles' => $recentFiles,
        ]);
    }

    /**
     * Calculate total storage used by all files
     */
    private function calculateTotalStorage()
    {
        $totalBytes = FileModel::sum('size') ?? 0;
        return $this->formatFileSize($totalBytes);
    }

    /**
     * Calculate storage used by specific user
     */
    private function calculateUserStorage($userId)
    {
        $totalBytes = FileModel::where('uploaded_by', $userId)->sum('size') ?? 0;
        return $this->formatFileSize($totalBytes);
    }

    /**
     * Format file size to human readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
