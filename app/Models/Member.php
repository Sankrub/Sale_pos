<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'member_since'];
    protected $attributes = ['discount_rate' => 0.10];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function discountRate()
    {
        return $this->discount_rate;
    }

    public $timestamps = true;
}
