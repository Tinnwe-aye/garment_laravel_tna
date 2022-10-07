<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $fillable = ['name_mm', 'name_en', 'phone_no', 'email', 'businessName', 'address', 'description', 'created_emp', 'updated_emp'];
    use HasFactory;
}
