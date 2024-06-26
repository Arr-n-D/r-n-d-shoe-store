<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInventory extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'shoe_id', 'quantity'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}
