<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

    public function orders()
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ? Storage::url('staff/' . $this->profile_image) : 'https://ui-avatars.com/api/?name=' . $this->name;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
