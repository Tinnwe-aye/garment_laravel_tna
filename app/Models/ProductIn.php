<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductIn extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_in';
    protected $hidden = [
        'created_emp', 'updated_emp', 'created_at' ,'updated_at'
    ];

    public function ProductTranData(){
        return $this->hasMany('App\Models\ProductInData');
    }
}
