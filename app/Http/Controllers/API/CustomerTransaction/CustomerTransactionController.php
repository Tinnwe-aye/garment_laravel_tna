<?php

namespace App\Http\Controllers\API\CustomerTransaction;

use App\DBTransactions\CustomerTransaction\UpdateCustomerTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\CustomerTransaction;
use App\Models\CustomerTransactionData;
use App\Interfaces\CustomerTransaction\CustomerTransactionRepositoryInterface;

class CustomerTransactionController extends Controller
{
    protected $csTranrRepo;
    public function __construct(CustomerTransactionRepositoryInterface $csTranrRepo)
    {
        $this->csTranrRepo = $csTranrRepo;
    }
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
            'voucher_no' => ''
        );
        $insertCusTran = CustomerTransaction::insert($cusTran);
        $cusTranMaxid = CustomerTransaction::max('id');

        $CusTranArr = [];
        foreach($request['product_data'] as $productData){
            $cusTranData = array(
                'customer_transaction_id'=>$cusTranMaxid,
                'product_id'=>$productData['productName'],
                'product_name'=>$productData['pName'],
                'size_id'=>$productData['productSize'],
                'size'=>$productData['pSize'],
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
    public function show(Request $request)
    {
        $cuTranData  = $this->csTranrRepo->searchCusTran($request->all());
        if(!empty($cuTranData)){
            return response()->json([
                'status'    =>  'OK',
                'data'      => $cuTranData,
            ],200);
        }else{
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER009'),
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cuTranData  = $this->csTranrRepo->editCusTran($id);
        if(!empty($cuTranData)){
            return response()->json([
                'status'    =>  'OK',
                'data'      => $cuTranData,
            ],200);
        }else{
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER009'),
            ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   try {
            $request = $request->all();
            $cusTran = array(
                // 'customer_id'=> $request['customer_id'],
                'tran_date' => $request['tran_date'],
                'total_qty' => $request['total_qty'],
                'total_amt' => $request['total_amt'],
                'voucher_no' => ''
            );
        
            if (!CustomerTransaction::where('id',$id)->exists()) {
                return response()->json([
                    'status' =>  'NG',
                    'message'   =>  trans('errorMessage.ER007'),
                ],200);
            }
            //create UpdateCustomerTrasaction Class to update data in db
            $updateCusTran = CustomerTransaction::where('id',$id)->update($cusTran);
            if(!$updateCusTran){
                return response()->json([
                    'status' =>  'NG',
                    'message' =>  trans('errorMessage.ER005'),
                ],200);
            }
            

            #Delete first from customer_transaction_data
            CustomerTransactionData::where('customer_transaction_id',$id)->delete();
            
            #Add new customer_transaction_data
            $CusTranArr = [];
            foreach($request['product_data'] as $productData){
                $cusTranData = array(
                    'customer_transaction_id'=>$id,
                    'product_id'=>$productData['productName'],
                    'product_name'=>$productData['pName'],
                    'size_id'=>$productData['productSize'],
                    'size'=>$productData['pSize'],
                    'price'=>$productData['productPrice'],
                    'qty'=>$productData['productQty'],
                    'amount'=>$productData['total']
                );
                array_push( $CusTranArr,$cusTranData);            
            }

            $insertCusTranData = CustomerTransactionData::insert($CusTranArr);/// need db trasaction to roll back data
            if ($updateCusTran && $insertCusTranData) {
                return response()->json([
                    'status'    =>  'OK',
                    'message'   =>  trans('successMessage.SS002'),
                ],200);
            }else{
                return response()->json([
                    'status' =>  'NG',
                    'message' =>  trans('errorMessage.ER005'),
                ],200);
            }                           
        } catch (\Throwable $th) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            if (!CustomerTransaction::where('id',$request->id)->exists()) {
                return response()->json([
                    'status' =>  'NG',
                    'message'   =>  trans('errorMessage.ER007'),
                ],200);
            }
            if (!CustomerTransactionData::where('customer_transaction_id',$request->id)->exists()) {
                return response()->json([
                    'status' =>  'NG',
                    'message'   =>  trans('errorMessage.ER007'),
                ],200);
            }

            CustomerTransaction::where('id',$request->id)->delete();
            CustomerTransactionData::where('customer_transaction_id',$request->id)->delete();

            return response()->json([
                'status' => 'OK',
                'message'   =>  trans('successMessage.SS003'),
            ],200);

        } catch (\Throwable $th) {
            log::debug($th);
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
    }
}
