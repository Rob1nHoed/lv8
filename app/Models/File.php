<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function downloads()
    {
        return $this->HasMany(User::class , 'file_user_downloads');
    }

    public function send()
    {
        return $this->HasMany(User::class , 'file_user_recieved');
    }
}
