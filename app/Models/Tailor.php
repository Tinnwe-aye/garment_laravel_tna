<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tailor extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $hidden = [
        'created_emp', 'updated_emp', 'created_at' ,'updated_at'
    ];

    protected $fillable = ['tailor_id','name_mm','name_en','phone_no','nrc_no','address','description','created_emp','updated_emp'];
}
