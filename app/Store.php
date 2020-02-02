<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected $fillable = ['user_id','name','address','phone'];

}
