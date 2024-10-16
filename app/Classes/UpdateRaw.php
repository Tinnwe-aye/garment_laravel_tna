<?php

namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Raws;
use App\Models\TailorTransaction;
use App\Models\SupplierTransaction;
use App\Models\ProductRaw;

class UpdateRaw {
    public function updateRawRegisterDta($raw1id = null,$raw2id  = null)
	{
        $inData1 = SupplierTransaction::where('raw_id',$raw1id)->sum('qty');
        $inData2 = SupplierTransaction::where('raw_id',$raw2id)->sum('qty');

        $outData1 = ProductRaw::where('raw1_id',$raw1id)->sum('raw1_qty');
        $outData2 = ProductRaw::where('raw2_id',$raw2id)->sum('raw2_qty');

        $updateRaw1 = Raws::where('id',$raw1id)->update(['balance' =>  $inData1 - $outData1]);
        $updateRaw2 = Raws::where('id',$raw2id)->update(['balance' =>  $inData2 - $outData2]);

        return ['updateRaw1'=>$updateRaw1,'updateRaw2'=>$updateRaw2];
    }

    public function updateRawqtyFromSupplierTran($supplierTransactionId){

        foreach ($supplierTransactionId as $key => $id) {
            $rawData = SupplierTransaction::where('id',$id)->first();
            $rawId = $rawData->raw_id;
            $qty = $rawData->qty;
            $rawBalance =  Raws::where('id',$rawId)->first('balance');
            $balance = $rawBalance->balance - $qty;
            $rawUpdateFlag = Raws::where('id',$rawId)->update(['balance' =>  $balance]);
        }

        return $rawUpdateFlag;
    }

}