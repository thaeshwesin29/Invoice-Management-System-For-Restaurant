<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        return Storage::url('category/' . $this->image);
    }
}
