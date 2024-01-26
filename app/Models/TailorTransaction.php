<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TailorTransaction extends Model
{
    protected $table = 'tailor_transaction';
    use HasFactory;
    protected $hidden = [
        'created_at' ,'updated_at'
   ];

    public function ProductsRaw(){
        return $this->belongsTo(ProductRaw::class,'products_raw_id');
    }
}
