<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierTransaction extends Model
{
    protected $table = 'supplier_transactions';

    use HasFactory;
    use SoftDeletes;
}
