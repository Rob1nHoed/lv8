<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_key',
        'user_id',
        'file_name',
        'description',
        'downloads',
        'max_downloads',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
