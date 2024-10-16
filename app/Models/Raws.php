<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Raws extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'created_emp', 'updated_emp', 'created_at' ,'updated_at'
    ];

    protected $fillable = ['name','type','description', 'created_emp', 'updated_emp'];

}
