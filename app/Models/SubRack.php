<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubRack extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_id',
        'name',
        'description',
        'created_by',
    ];

    // Relationships
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files()
    {
        return $this->hasMany(FileModel::class);
    }

    // Scopes
    public function scopeWithFileCount($query)
    {
        return $query->withCount('files');
    }
}
