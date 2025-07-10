<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileModel extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'title',
        'filename',
        'original_name',
        'mime_type',
        'size',
        'rack_id',
        'sub_rack_id',
        'description',
        'uploaded_by',
    ];

    // Relationships
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function subRack()
    {
        return $this->belongsTo(SubRack::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'file_tags', 'file_id', 'tag_id');
    }

    // Accessor
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } else {
            $bytes = $bytes . ' bytes';
        }
        return $bytes;
    }

    // Scopes
    public function scopeWithTags($query)
    {
        return $query->with('tags');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }
}
