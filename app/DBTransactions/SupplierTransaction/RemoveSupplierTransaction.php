<?php
namespace App\DBTransactions\SupplierTransaction;

use App\Classes\Transaction;
use App\Models\SupplierTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RemoveSupplierTransaction extends Transaction  {

    private $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function process()
    {  // dd($this->request);

       // is_array($this->request->id) ? dd('Array') : dd('not an Array');
        if (!SupplierTransaction::whereIn('id',$this->request->id)->exists()) {
            return response()->json([
                'status' =>  'NG',
                'message'   =>  trans('errorMessage.ER007'),
            ],200);
        }else{
            SupplierTransaction::whereIn('id',$this->request->id)->delete();
            
            return ['status' => true, 'error' => false];
        }
        
    }
}
?>
