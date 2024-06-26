<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'filename',
        'file_type',
        'file_size',
        'encrypted_file',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class);
    }

    public function shareLinks()
    {
        return $this->hasMany(ShareLink::class);
    }
}
