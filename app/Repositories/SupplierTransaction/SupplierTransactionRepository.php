<?php

namespace App\Repositories\SupplierTransaction;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\BankFileName;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierTransaction;
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
      
       // $start = strtotime(trim($request['fromDate'], '"'));
       // $end = strtotime(trim($request['toDate'], '"'));
       $start       = date('Y-m-d',strtotime(trim($request['fromDate'], '"')));
       $end         = date('Y-m-d',strtotime(trim($request['toDate'], '"')));
      $supplierId   = $request['supplierId'];
      $rawId        = $request['rawId'];

      // $time = strtotime(trim($request['fromDate'], '"'));
      // //dd($time);
      // $newformat = date('Y-m-d',$time);
  // dd($start);
  // dd($end);
 // return SupplierTransaction::all();
      $sql = SupplierTransaction::join('raws', 'raws.id', '=', 'supplier_transactions.supplierId')
              ->join('suppliers', 'suppliers.id', '=', 'supplier_transactions.rawId')
              ->whereNull('deleted_at');
                              
                                 //->whereBetween("DATE_FORMAT(date,'%Y-%m-%d')", [$start,$end]);
                                // ->get();
      if (!empty($supplierId)) {
          $sql->where('supplierId', $supplierId);
      }
      if (!empty($rawId)) {
        $sql->where('rawId', $rawId);
      }

      $result= $sql->get();

    return $result;
                                
    }
}
