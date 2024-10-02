<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryBook::class, 'category_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(isset($filters['search-title']), function ($query) use ($filters) {
            $query->where('title', 'like', '%' . $filters['search-title'] . '%');
        })
            ->when(isset($filters['search-isbn']), function ($query) use ($filters) {
                $query->where('isbn', 'like', '%' . $filters['search-isbn'] . '%');
            })
            ->when(isset($filters['search-year']), function ($query) use ($filters) {
                $query->where('publication_date', 'like', '%' . $filters['search-year'] . '%');
            });
    }
}
