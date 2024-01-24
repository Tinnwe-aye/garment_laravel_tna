<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRaw extends Model
{
    protected $table = 'products_raw';
    use HasFactory;
    protected $hidden = [
        'created_at' ,'updated_at'
    ];

    protected $fillable = ['product_id','size_id','raw1_id','raw2_id','raw_combination','product_per_raws'];

    public function productRaw1(){
        return $this->belongsTo(Raws::class,'raw1_id');
    }
    public function productRaw2(){
        return $this->belongsTo(Raws::class,'raw2_id');
    }
    
    public function productSize(){
        return $this->belongsTo(Sizes::class,'size_id');
    }
    public function productProduct(){
        return $this->belongsTo(Products::class,'product_id');
    }
}
