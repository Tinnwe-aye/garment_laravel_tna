<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ProductIn;
use App\Models\ProductUpload;
use App\Models\ProductInData;
use App\Models\TailorTransaction;
use App\Interfaces\Product\ProductRepositoryInterface;

class ProductInController extends Controller
{
    protected $tailorRepo,$productRepo;
    public function __construct(ProductRepositoryInterface $productRepo
                                )
    {
        $this->productRepo = $productRepo;
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
        $productInTran = array(
            'tailor_id'=> $request['tailor_id'],
            'Date' => $request['inDate'],
            'total_qty' => $request['total_qty'],
            'total_amt' => $request['total_amt'],
            'voucher_no' => ''
        );
        $insertPin = ProductIn::insert($productInTran);
        $pInMaxid = ProductIn::max('id');
        $productInArr = [];
        $tailorTransactionArr = [];
        foreach($request['product_data'] as $productData){
            $productInData = array(
                'product_in_id'=>$pInMaxid,
                'product_id'=>$productData['name'],
                'product_name'=>$productData['pName'],
                'size_id'=>$productData['size'],
                'size_name'=>$productData['pSize'],
                'qty'=>$productData['qty'],
                'price'=>$productData['rate'],
                'amount'=>$productData['price'],
            );
            array_push( $productInArr,$productInData);  
            
            $getLeftQty = TailorTransaction::where(['tailor_id'=>$request['tailor_id'],'product_id'=>$productData['name'],'size_id'=>$productData['size']])->orderBy('created_at', 'desc')->first();
            $getLeftQty = ($getLeftQty) ? $getLeftQty->left_qty : 0 ;
            $tailorTransaction = array(
                "date"=> $request['inDate'],
                "tailor_id"=> $request['tailor_id'],
                "product_id"=> $productData['name'],
                "size_id"=> $productData['size'],
                "out_qty"=> 0,
                "in_qty"=> $productData['qty'],
                "left_qty"=> $getLeftQty - $productData['qty'], // need to Modified
            );
            array_push( $tailorTransactionArr,$tailorTransaction);  
        }
        $insertproductInData = ProductInData::insert($productInArr);
        $insertTailorTransaction = TailorTransaction::insert($tailorTransactionArr);

        if (!$insertproductInData) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
        
        //get unique image
        $collection = collect($request['product_data']);
        $unique = $collection->unique('product_id'); 
        $imgDatas=$unique->values()->all();
        $productInImgArr = [];
        foreach($imgDatas as $imgData){
            $productInImgData = array(
                'date'=> $request['inDate'],
                'product_id'=>$imgData['name'],
                'image_name'=>$imgData['imageName'],
                'image_link'=>$imgData['imageUrl'],
                'created_emp'=>1,
                'updated_emp'=>1
            );
            array_push( $productInImgArr,$productInImgData);          
        }
        $insertproductInImgData = ProductUpload::insert($productInImgArr);

        if (!$insertproductInImgData) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }

        if ($insertproductInData && $insertproductInImgData && $insertPin) {
            return response()->json([
                'status'    =>  'OK',
                'message'   =>  trans('successMessage.SS001'),
            ],200);
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cuTranData  = $this->productRepo->editProductInTran($id);
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
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request = $request->all();
            $productInTran = array(
                // 'customer_id'=> $request['customer_id'],
                'date' => $request['inDate'],
                'total_qty' => $request['total_qty'],
                'total_amt' => $request['total_amt'],
                'voucher_no' => ''
            );        
            if (!ProductIn::where('id',$id)->exists()) {
                return response()->json([
                    'status' =>  'NG',
                    'message'   =>  trans('errorMessage.ER007'),
                ],200);
            }
            //create UpdateCustomerTrasaction Class to update data in db
            $updatePinTran = ProductIn::where('id',$id)->update($productInTran);
            if(!$updatePinTran){
                return response()->json([
                    'status' =>  'NG',
                    'message' =>  trans('errorMessage.ER005'),
                ],200);
            }
            

            #Delete first from customer_transaction_data
            ProductInData::where('product_in_id',$id)->delete();
            
            #Add new customer_transaction_data
            $pInTranArr = [];
            foreach($request['product_data'] as $productData){
                $pInTranData = array(
                    'product_in_id'=>$id,
                    'product_id'=>$productData['name'],
                    'product_name'=>$productData['pName'],
                    'size_id'=>$productData['size'],
                    'size_name'=>$productData['pSize'],
                    'price'=>$productData['rate'],
                    'qty'=>$productData['qty'],
                    'amount'=>$productData['price']
                );
                array_push( $pInTranArr,$pInTranData);            
            }

            $insertPinTranData = ProductInData::insert($pInTranArr);/// need db trasaction to roll back data
            if ($updatePinTran && $insertPinTranData) {
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
            log::info($th);
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            if (!ProductIn::where('id',$request->id)->exists()) {
                return response()->json([
                    'status' =>  'NG',
                    'message'   =>  trans('errorMessage.ER007'),
                ],200);
            }
            if (!ProductInData::where('product_in_id',$request->id)->exists()) {
                return response()->json([
                    'status' =>  'NG',
                    'message'   =>  trans('errorMessage.ER007'),
                ],200);
            }

            ProductIn::where('id',$request->id)->delete();
            ProductInData::where('product_in_id',$request->id)->delete();

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
