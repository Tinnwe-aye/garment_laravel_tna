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
      $start        = $request['fromDate'];
      $end          = $request['toDate'];
      $supplier_id   = $request['supplier_id'];
      $raw_id        = $request['raw_id'];
    

    //   $sql = DB::table('supplier_transactions')
    //   ->whereNull('deleted_at');    
       
     
    //   if (!empty($supplier_id)) {
    //       $sql->where('supplier_id', $supplier_id);
    //   }
    //   if (!empty($raw_id)) {
    //     $sql->where('raw_id', $raw_id);
    //   }

    //   $result= $sql->get();
     
    // return $result;


    $sql = DB::table('supplier_transactions')
                    ->leftjoin('raws','supplier_transactions.raw_id','raws.id')
                    ->leftjoin('suppliers','supplier_transactions.supplier_id','suppliers.id')
                    ->select(
                              'supplier_transactions.*','raws.name AS rawName',
                              'suppliers.name_en AS supplierNameEN',
                              'suppliers.name_mm AS supplierNameMM'
                              )
                    ->whereNull('supplier_transactions.deleted_at');
                   // ->get();

    if (!empty($supplier_id)) {
        $sql->where('supplier_transactions.supplier_id', $supplier_id);
      }
    if (!empty($raw_id)) {
      $sql->where('supplier_transactions.raw_id', $raw_id);
    }

    return $sql->get();
                //   dd($result);
    }
}
