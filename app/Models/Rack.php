<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subRacks()
    {
        return $this->hasMany(SubRack::class);
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
