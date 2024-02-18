<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['member_id', 'total_price', 'status', 'sale_date'];

    public function saleLineItems()
    {
        return $this->hasMany(SaleLineItem::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function applyMemberDiscount()
    {
        if ($this->member) {
            $discount = $this->total_price * $this->member->discountRate();
            $this->total_price -= $discount;
        }
    }

    public $timestamps = true;
}
