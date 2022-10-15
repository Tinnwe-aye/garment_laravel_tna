<?php

namespace App\Repositories\SupplierTransaction;

use App\Models\SupplierTransaction;
use Illuminate\Support\Arr;
use App\Models\BankFileName;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\SupplierTransaction\SupplierTransactionRepositoryInterface;

/**
 *
 * @author  
 * @create_date 
 */
class SupplierTransactionRepository implements SupplierTransactionRepositoryInterface
{

    /**
     * Get  data
     *
     * @author  
     * @param
     * @return  array
     */
    public function getSupplierTransactionData($request)
    {
      
        $start = $request['fromDate'];
        $end = $request['toDate'];
        $supplier_id = $request['supplier_id'];
        $raw_id = $request['raw_id'];
        dd($request);
        return SupplierTransaction::whereNull('deleted_at')->get()
        ->whereBetween('date', [$start,$end]);
        
    }
}
