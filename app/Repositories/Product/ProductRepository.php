<?php

namespace App\Repositories\Product;

use App\Models\Products;
use App\Models\ProductSizes;
use App\Models\ProductIn;
use App\Interfaces\Product\ProductRepositoryInterface;

/**
 * Product repository
 *
 * @author  Tin Nwe Aye
 * @create  2022/07/19
 */
class ProductRepository implements ProductRepositoryInterface
{   
  
    // public function searchData($request)
    // {
    //     $start = $request['start_date'];
    //     $end = $request['end_date'];
    //    //DB::enableQueryLog();
    //     $ProductData = Tailor::join('product_in','product_in.tailor_id','tailors.tailor_id')
    //                         ->join('products','product_in.product_id','products.id')
    //                         ->leftjoin('sizes','product_in.size_id','sizes.id') 
    //                         ->whereBetween('date', [$start,$end])
    //                         ->when((!empty($request['tailor_id'])), function ($query) use ($request) {
    //                             $query->where('product_in.tailor_id', $request['tailor_id']);
    //                         })
    //                         ->when((!empty($request['tailor_name'])), function ($query) use ($request) {
    //                             $query->where('tailors.name_mm', $request['tailor_name'])
    //                                 ->orwhere('tailors.name_en', $request['tailor_name']);
    //                         })
    //                         ->whereNull('product_in.deleted_at')
    //                         ->whereNull('products.deleted_at')
    //                         ->select('product_in.id','tailors.tailor_id','name_mm','name_en','products.product_name as productName','sizes.size',
    //                         'product_in.date','product_in.tailor_id','product_in.product_id','product_in.size_id','product_in.qty',
    //                         'product_in.price','product_in.total_amount as totalAmount')
    //                         ->orderBy('product_in.date','ASC')
    //                         ->orderBy('product_in.tailor_id','ASC')
    //                         ->get();
    //     //dd(DB::getQueryLog());
    //     return  $ProductData;
    // }

    public function getProductName()
    {
        return Products::all();
    }

    public function getProductSizeByName($request)
    {
        $data = $request->all();
        $sizes = ProductSizes::join('sizes','sizes.id','products_size.size_id')
                                ->where('product_id',$data['product_id'])
                                ->whereNull('sizes.deleted_at')
                                ->select('sizes.id','size')
                                ->get();
        return $sizes;
    }

    public function searchData($request)
    {        
        $condition = [];
        $filter = [];
        if($request['tailor_id']){
            $condition[] = ['product_in.tailor_id',$request['tailor_id']];
        }
        if($request['product_name']){
            $filter[] =  ['product_id',$request['product_name']];
        }
        if($request['product_size']){
            $filter[] =  ['size_id',$request['product_size']];
        }

        $result = ProductIn::whereHas('ProductTranData', function ($query) use ($filter){
                        $query->where( $filter);
                    })
                 ->with('ProductTranData')
                 ->join('tailors','tailors.tailor_id','product_in.tailor_id')
                 ->whereBetween('Date',[$request['start_date'],$request['end_date']])
                 ->where($condition)
                 ->select('product_in.*','tailors.name_mm','tailors.name_en','product_in.total_qty as totalQty','product_in.total_amt as totalAmt')
                 ->get();

                 $results = $result->toArray();
        $items = [];
        foreach ($results as $key => $res) {
            if($request['product_name'] || $request['product_size']){
                $items = collect($res['product_tran_data'])->filter(function ($item) use ($request){
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
                $results[$key]['product_tran_data'] = $items->all();
            }
        }
        return $results;
    }

    public function editProductInTran($id)
    {        
        $condition = [];
        $filter = [];
        if($id){
            $condition[] = ['product_in.id',$id];
        }

        $result = ProductIn::whereHas('ProductTranData', function ($query) use ($filter){
                        $query->where( $filter);
                    })
                 ->with('ProductTranData')
                 ->join('tailors','tailors.tailor_id','product_in.tailor_id')
                 ->where($condition)
                 ->select('product_in.*','tailors.name_mm','tailors.name_en','product_in.total_qty as totalQty','product_in.total_amt as totalAmt')
                 ->get();

                 $results = $result->toArray();

                 $items = [];
                 $res = [];
                 foreach ($results as $key => $res) {
                     foreach ($res['product_tran_data'] as $key1 => $value) {
                        $items[$key1]['tableId'] = $key1;
                        $items[$key1]['name'] = $value['product_id'];
                        $items[$key1]['size'] = $value['size_id'];
                        $items[$key1]['qty'] = $value['qty'];
                        $items[$key1]['rate'] = $value['price'];
                        $items[$key1]['price'] = $value['amount'];
                        $items[$key1]['pName'] = $value['product_name'];
                        $items[$key1]['pSize'] = $value['size_name'];
                        $items[$key1]['imageName'] = "";
                        $items[$key1]['imageUrl'] = "";
                     }

                     $res['product_tran_data'] = $items;
                 }
                 return $res;
    }

}
