<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected $fillable = ['store_id','user_id','name','price','actual_qty'];
}
