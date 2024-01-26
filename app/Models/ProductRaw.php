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

}
