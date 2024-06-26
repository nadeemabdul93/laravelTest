<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_id',
        'share_link',
        'token',
        'expiration_date',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
