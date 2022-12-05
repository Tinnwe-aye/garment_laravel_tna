<?php

namespace App\Http\Controllers\API\CustomerTransaction;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerTransaction;
use App\Models\CustomerTransactionData;

class CustomerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->all();
        $cusTran = array(
            'customer_id'=> $request['customer_id'],
            'tran_date' => $request['tran_date'],
            'total_qty' => $request['total_qty'],
            'total_amt' => $request['total_amt'],
            'total_qty' => $request['total_qty'],
            'voucher_no' => ''
        );
        $insertCusTran = CustomerTransaction::insert($cusTran);
        $cusTranMaxid = CustomerTransaction::max('id');

        $CusTranArr = [];
        foreach($request['product_data'] as $productData){
            $cusTranData = array(
                'customer_transaction_id'=>$cusTranMaxid,
                'product_id'=>$productData['productName'],
                'size_id'=>$productData['productSize'],
                'price'=>$productData['productPrice'],
                'qty'=>$productData['productQty'],
                'amount'=>$productData['total']
            );
            array_push( $CusTranArr,$cusTranData);            
        }
        $insertCusTranData = CustomerTransactionData::insert($CusTranArr);/// need db trasaction to roll back data
        if ($insertCusTran && $insertCusTranData) {
            return response()->json([
                'status'    =>  'OK',
                'message'   =>  trans('successMessage.SS001'),
            ],200);
        }else{
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
