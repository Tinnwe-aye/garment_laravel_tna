<?php

namespace App\Http\Controllers\API\SupplierTransaction;

use Illuminate\Http\Request;
use App\Models\SupplierTransaction;
use App\Http\Controllers\Controller;
use App\Classes\UpdateRaw;
use Illuminate\Support\Facades\Log;
use App\DBTransactions\SupplierTransaction\RemoveSupplierTransaction;
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
                    "qtyPack"=>$data->qty_pkg,
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


    public function destroy(Request $request)
    {
        try {
            //Update balance in raws table   
            $updateRaw = new UpdateRaw();
            $updateRawQty = $updateRaw->updateRawqtyFromSupplierTran($request->id);

            $remove = new RemoveSupplierTransaction($request);
            $bool = $remove->process();
            if ($bool['status']) {
                return response()->json([
                    'status'    =>  'OK',
                    'message'   =>  trans('successMessage.SS003'),
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
}
