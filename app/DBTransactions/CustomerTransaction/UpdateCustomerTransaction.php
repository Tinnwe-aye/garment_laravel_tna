<?php
namespace App\DBTransactions\CustomerTransaction;

use App\Classes\Transaction;
use App\Models\CustomerTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UpdateCustomerTransaction extends Transaction  {
    //use LogTrait;
    private $request,$id;
    //private $attendanceArray;
    public function __construct($id,$request)
    {
        $this->request = $request;
        $this->id = $id;
    }
      /**
	 * Write detail of method
     *
     * @author Tin Nwe Aye
     * @create  2023/10/190     * @return  array
	 */
    public function process()
    {
        $data= CustomerTransaction::where('id',$this->id)->update([
            'tran_date' => $this->request->tran_date,
            'total_qty' => $this->request->total_qty,
            'total_amt' => $this->request->total_amt,
            'voucher_no' => '',
            'created_emp' => $this->request->login_id,
            'updated_emp' => $this->request->login_id,
        ]); 

        return ['status' => true, 'error' => ''];
    }
}
?>
