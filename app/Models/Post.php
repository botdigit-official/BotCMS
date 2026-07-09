<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'user_id',
        'type',
        'title',
        'slug',
        'content',
        'status',
        'mime_type',
    ];

    /**
     * Scope query to only include pages.
     */
    public function scopePages($query)
    {
        return $query->where('type', 'page');
    }

    /**
     * Scope query to only include published items.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Relationship to the user (Author).
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
