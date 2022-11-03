<?php

namespace App\Http\Controllers\API\SupplierTransactionList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\SupplierTransaction\SupplierTransactionRepositoryInterface;


class SupplierTransactionListController extends Controller
{
    protected $supplierTransactionRepo;
    public function __construct(SupplierTransactionRepositoryInterface $supplierTransactionRepo)
    {
        $this->supplierTransactionRepo = $supplierTransactionRepo;
    }

    public function search(Request $request){
        try{
          
            $data = $this->supplierTransactionRepo->getSupplierTransactionData($request);
           
            $response = $data->map(function($data,$key) {  //dd($data->id);
                return [
                    "key"=>$key+1,
                    "id"=>$data->id,
                    "date"=>$data->date,
                    "supplierNameEN"=>$data->supplierNameEN,
                    "rawName"=>$data->rawName,
                    "qtyBack"=>$data->qty_back,
                    "qty"=>$data->qty,
                    "price"=>$data->price,
                    "totalAmount"=>$data->total_amount,
                    "AlltotalAmount"=>$data->total_amount+=$data->total_amount,
                    "AlltotalQty"=>$data->qty+=$data->qty,
                ];
            }); 
            //dd($response);
            $response=json_decode($response,true);
          
            return response()->json([
                'status' =>  'OK',
                'row_count'=>count($response),
                'data'   =>   $response,
            ],200);
        }catch(\Throwable $th) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
    }
}
