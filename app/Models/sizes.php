<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sizes extends Model
{
    use HasFactory;
    protected $hidden = [
        'created_emp', 'updated_emp', 'created_at' ,'updated_at'
    ];
    protected $table = 'sizes';

}