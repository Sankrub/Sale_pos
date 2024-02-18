<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'price', 'type'];

    public function saleLineItems()
    {
        return $this->hasMany(SaleLineItem::class);
    }

    public $timestamps = true;
}
