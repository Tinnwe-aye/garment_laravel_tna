<?php
namespace App\DBTransactions\Customer;

use App\Models\Customer;
use App\Classes\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UpdateCustomerData extends Transaction  {
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
        $data= Customer::where('id',$this->id)->update([
            'name_mm' => $this->request->name_mm,
            'name_en' => $this->request->name_en,
            'email' => $this->request->mail,
            'phone_no' => $this->request->phone_no,
            'nrc_no' => $this->request->nrc_no,
            'address' => $this->request->addresses,
            'township_id' => $this->request->township_name,
            'status' => $this->request->statuses,   
            'join_date' => $this->request->join_date,          
            'description' => $this->request->descriptions,
            'created_emp' => $this->request->login_id,
            'updated_emp' => $this->request->login_id,
        ]); 

        return ['status' => true, 'error' => ''];
    }
}
?>
