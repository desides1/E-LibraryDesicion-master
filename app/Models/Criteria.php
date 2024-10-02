<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subCriteria()
    {
        return $this->hasMany(SubCriteria::class);
    }
}
