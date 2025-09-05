<?php

namespace App\Models;

use App\Models\Order;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->invoice_number = 'INV-' . str_pad(self::max('id') + 1, 8, '0', STR_PAD_LEFT);
        });
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
