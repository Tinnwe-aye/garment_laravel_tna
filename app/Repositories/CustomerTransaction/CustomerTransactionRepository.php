<?php

namespace App\Repositories\CustomerTransaction;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerTransaction;
use App\Models\CustomerTransactionData;

use App\Interfaces\CustomerTransaction\CustomerTransactionRepositoryInterface;

/**
 * CustomerTransaction repository
 *
 * @author  Tin Nwe Aye
 * @create  2022/07/19
 */
class CustomerTransactionRepository implements CustomerTransactionRepositoryInterface
{
    public function searchCusTran($request)
    {        
        $condition = [];
        $filter = [];
        if($request['customer_id']){
            $condition[] = ['customer_transactions.customer_id',$request['customer_id']];
        }
        if($request['product_name']){
            $filter[] =  ['product_id',$request['product_name']];
        }
        if($request['product_size']){
            $filter[] =  ['size_id',$request['product_size']];
        }

        $result = CustomerTransaction::whereHas('CusTranData', function ($query) use ($filter){
                        $query->where( $filter);
                    })
                 ->with('CusTranData')
                 ->join('customers','customers.customer_id','customer_transactions.customer_id')
                 ->whereBetween('tran_date',[$request['from_date'],$request['to_date']])
                 ->where($condition)
                 ->select('customer_transactions.*','customers.name_mm','customers.name_mm','customer_transactions.total_qty as totalQty','customer_transactions.total_amt as totalAmt')
                 ->get();

                 $results = $result->toArray();
        $items = [];
        foreach ($results as $key => $res) {
            if($request['product_name'] || $request['product_size']){
                $items = collect($res['cus_tran_data'])->filter(function ($item) use ($request){
                        $fil1 = ($request['product_name']) ?? false;
                        $fil2 = ($request['product_size']) ?? false;
                        if($fil1 != false && $fil2 != false){
                            $fil3 = ($item['product_id'] == $request['product_name'] && $item['size_id'] == $request['product_size']);
                        } else {
                            if($fil1 != false ) $fil3 = $item['product_id'] == $request['product_name'];
                            if($fil2 != false ) $fil3 = $item['size_id'] == $request['product_size'];
                        }
                        return ($fil3);
                });
                $results[$key] = $res;
                $results[$key]['cus_tran_data'] = $items->all();
            }
        }
        return $results;
    }

    public function editCusTran($id)
    {        
        $condition = [];
        $filter = [];
        if($id){
            $condition[] = ['customer_transactions.id',$id];
        }

        $result = CustomerTransaction::whereHas('CusTranData', function ($query) use ($filter){
                        $query->where( $filter);
                    })
                 ->with('CusTranData')
                 ->join('customers','customers.customer_id','customer_transactions.customer_id')
                 ->where($condition)
                 ->select('customer_transactions.*','customers.name_mm','customers.name_en','customer_transactions.total_qty as totalQty','customer_transactions.total_amt as totalAmt')
                 ->get();

                 $results = $result->toArray();

                 $items = [];
                 $res = [];
                 foreach ($results as $key => $res) {
                     foreach ($res['cus_tran_data'] as $key1 => $value) {
                        $items[$key1]['tableId'] = $key1;
                        $items[$key1]['productName'] = $value['product_id'];
                        $items[$key1]['productSize'] = $value['size_id'];
                        $items[$key1]['productQty'] = $value['qty'];
                        $items[$key1]['productPrice'] = $value['price'];
                        $items[$key1]['total'] = $value['amount'];
                        $items[$key1]['pName'] = $value['product_name'];
                        $items[$key1]['pSize'] = $value['size'];
                     }

                     $res['cus_tran_data'] = $items;
                 }
                 return $res;
    }
}
