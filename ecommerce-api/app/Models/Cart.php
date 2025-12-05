<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function total()
    {
       return $this->items->sum(fn ($i) => $i->quantity * $i->product->price);
    }
}
