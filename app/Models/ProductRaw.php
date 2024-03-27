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

    protected $fillable = ['product_id','size_id','raw1_id','raw1_name','raw1_qty','raw2_name','raw2_id','raw2_qty','raw_combination','product_per_raws'];

    public function raw1(){
        return $this->hasOne(ProductRaw::class,'raw1_id','id');
    }

    public function raw2(){
       return $this->hasOne(ProductRaw::class,'raw2_id','id');
    }

}
