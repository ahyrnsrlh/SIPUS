<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use App\Models\Rack;
use App\Models\SubRack;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class FileController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the files.
     */
    public function index(Request $request): Response
    {
        $query = FileModel::with(['rack', 'subRack', 'uploader', 'tags']);

        // If user is not admin, show their own files and files they have access to
        if (Auth::user()->role !== 'admin') {
            $userInstansi = Auth::user()->instansi;
            $query->where(function($q) use ($userInstansi) {
                $q->where('uploaded_by', Auth::id()) // Own files
                  ->orWhere(function($q2) use ($userInstansi) {
                      // Files from same instansi/unit (if instansi field exists)
                      if ($userInstansi) {
                          $q2->whereHas('uploader', function($q3) use ($userInstansi) {
                              $q3->where('instansi', $userInstansi);
                          });
                      }
                  });
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by rack
        if ($request->filled('rack_id')) {
            $query->where('rack_id', $request->rack_id);
        }

        // Filter by sub-rack
        if ($request->filled('sub_rack_id')) {
            $query->where('sub_rack_id', $request->sub_rack_id);
        }

        // Filter by uploader (only for admin)
        if ($request->filled('uploader_id') && Auth::user()->role === 'admin') {
            $query->where('uploaded_by', $request->uploader_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by tags
        if ($request->filled('tags') && is_array($request->tags)) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.id', $request->tags);
            });
        }

        $files = $query->latest()->paginate(12);

        // Transform files to include formatted size
        $files->getCollection()->transform(function ($file) {
            $file->formatted_size = $this->formatFileSize($file->size);
            return $file;
        });

        return Inertia::render('Files/Index', [
            'files' => $files,
            'racks' => Rack::with('subRacks')->get(),
            'subRacks' => SubRack::with('rack')->get(),
            'users' => Auth::user()->role === 'admin' ? User::where('role', 'user')->where('status', 'approved')->get() : [],
            'tags' => Tag::all(),
            'canUpload' => Auth::user()->role === 'admin' || Auth::user()->role === 'user',
            'filters' => $request->only(['search', 'rack_id', 'sub_rack_id', 'uploader_id', 'date_from', 'date_to', 'tags']),
        ]);
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

    /**
     * Show the form for creating a new file.
     */
    public function create(): Response
    {
        return Inertia::render('Files/Create', [
            'racks' => Rack::select('id', 'name')->get(),
            'subRacks' => SubRack::select('id', 'name', 'rack_id')->get(),
            'tags' => Tag::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created file in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'rack_id' => 'required|exists:racks,id',
            'sub_rack_id' => 'nullable|exists:sub_racks,id',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('files', $filename, 'public');

        $fileModel = FileModel::create([
            'title' => $request->title,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'rack_id' => $request->rack_id,
            'sub_rack_id' => $request->sub_rack_id,
            'description' => $request->description,
            'uploaded_by' => Auth::id(),
        ]);

        // Handle tags
        if ($request->filled('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $fileModel->tags()->sync($tagIds);
        }

        return redirect()->route('files.index')
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified file.
     */
    public function show(FileModel $file): Response
    {
        // Load the uploader relationship before authorization
        $file->load('uploader');
        
        $this->authorize('view', $file);
        
        $file->load(['rack', 'subRack', 'tags']);

        return Inertia::render('Files/Show', [
            'file' => $file,
        ]);
    }

    /**
     * Show the form for editing the specified file.
     */
    public function edit(FileModel $file): Response
    {
        // Load the uploader relationship before authorization
        $file->load('uploader');
        
        $this->authorize('update', $file);

        $file->load(['rack', 'subRack', 'tags']);

        return Inertia::render('Files/Edit', [
            'file' => $file,
            'racks' => Rack::select('id', 'name')->get(),
            'subRacks' => SubRack::select('id', 'name', 'rack_id')->get(),
            'tags' => Tag::select('id', 'name')->get(),
        ]);
    }

    /**
     * Update the specified file in storage.
     */
    public function update(Request $request, FileModel $file)
    {
        $this->authorize('update', $file);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $file->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Handle tags
        if ($request->filled('tags')) {
            $tagIds = [];
            foreach ($request->tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }
            $file->tags()->sync($tagIds);
        } else {
            $file->tags()->detach();
        }

        return redirect()->route('files.index')
            ->with('success', 'File updated successfully.');
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy(FileModel $file)
    {
        // Load the uploader relationship before authorization
        $file->load('uploader');
        
        try {
            $this->authorize('delete', $file);

            // Delete the actual file
            if (Storage::disk('public')->exists('files/' . $file->filename)) {
                Storage::disk('public')->delete('files/' . $file->filename);
            }

            // Delete from database
            $file->delete();

            return redirect()->route('files.index')->with('success', 'File deleted successfully.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return back()->withErrors(['error' => 'You are not authorized to delete this file.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete file: ' . $e->getMessage()]);
        }
    }

    /**
     * Download the specified file.
     */
    public function download(FileModel $file)
    {
        // Load the uploader relationship before authorization
        $file->load('uploader');
        
        $this->authorize('download', $file);

        $filePath = storage_path('app/public/files/' . $file->filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath, $file->original_name);
    }

    /**
     * Preview the specified file.
     */
    public function preview(FileModel $file)
    {
        // Load the uploader relationship before authorization
        $file->load('uploader');
        
        $this->authorize('view', $file);

        $filePath = storage_path('app/public/files/' . $file->filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        // Return file for preview in browser
        return response()->file($filePath, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
        ]);
    }
}
