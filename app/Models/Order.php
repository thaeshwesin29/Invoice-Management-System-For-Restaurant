<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\BaseModel;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->order_number = 'ORD-' . str_pad(self::max('id') + 1, 8, '0', STR_PAD_LEFT);
        });
    }

    public function orderable()
    {
        return $this->morphTo();
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function scopePending()
    {
        return $this->where('status', 'pending');
    }

    public function scopeNotPending()
    {
        return $this->where('status', '!=', 'pending');
    }

    public function scopeConfirm()
    {
        return $this->where('status', 'confirm');
    }

    public function scopeCancel()
    {
        return $this->where('status', 'cancel');
    }

    public function isPending()
    {
        return $this->status == 'pending';
    }

    public function isConfirm()
    {
        return $this->status == 'confirm';
    }

    public function isCancel()
    {
        return $this->status == 'cancel';
    }
}
