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
            dd($data);
            return response()->json([
                'status' =>  'OK',
                'row_count'=>count($data),
                'data'   =>   $data,
            ],200);
        }catch(\Throwable $th) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
    }
}
