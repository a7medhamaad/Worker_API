<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'content',
        'price',
        'status',
        'reject_reason',
    ];

    /**
     * العلاقة مع Worker
     * Post belongs to Worker
     */
    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    /**
     * العلاقة مع الصور
     * Post has many photos
     */
    public function photos()
    {
        return $this->hasMany(PostPhoto::class);
    }
}
