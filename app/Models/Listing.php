<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    // Not needed if model::unguard is on

    protected $fillable = [
        'title',
        'company',
        'location',
        'email',
        'description',
        'tags',
        'website'
    ];

    // ------------------------------------

    public function scopeFilter($query, array $filter)
    {
        if ($filter['tag'] ?? false) {
            $query->where('tags', 'like', '%' . request('tag') . '%');
        }

        if ($filter['search'] ?? false) {
            $query->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%')
                ->orWhere('location', 'like', '%' . request('search') . '%');
        }
    }

    // Relationship to user

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
