<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Omnipay\Omnipay;
use Omnipay\Common\Helper;
use Omnipay\Common\CreditCard;
use Form\Server\Direct;
use App\Http\Controllers\GeneralController AS General;
class ProjectRepeatDonation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'user_id', 'user_role_id', 'declaration_id','qr_scanned_verified','transactionReference','description','currency','amount','repeat_type','repeat_start_date','repeat_end_date','repeat_date','is_deleted','created_at','updated_at'
    ];
   

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */

   public function find_donation_list(){
   	 
     $data = $this::where(function($query) {
                        $query->where('repeat_end_date','>=',date('Y-m-d'))
                        ->orWhere('repeat_end_date',null);
                        })->where('is_repeat_status','0')->where('is_deleted','0')->get();
     if($data){
      	return $data;
     }else{
		return array();
     }
     
   }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */

   public function update_project_idwise($id){
            
            $update_project_repeat = $this::where('id', $id)->update(['is_repeat_status' => '1']);
            return $update_project_repeat;
   }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */

   public function project_archieve_repeat($project_id){
        $userDetailObj  = new \App\User;
        $projectDetailObj = new \App\Project;
        $UserNotificationObj  = new \App\UserNotification;
        $project_detail = $projectDetailObj->find($project_id);
        $repepat_user_list = $this::where('project_id', $project_id)->where('is_repeat_status','0')->get();
        foreach ($repepat_user_list as $key => $value) {
        /* Send Notification church */
        $church_details = $userDetailObj->find($project_detail->church_id);
        $user = $userDetailObj->find($value->user_id);
        $church_detail = array();
        $church_detail['user_id'] = $user->id;
        $church_detail['user_name'] = $user->firstname;
        $church_detail['email'] = $user->email;
        $church_detail['user_role_id'] = $user->user_role_id;
        $church_detail['mobile'] = $user->mobile;
        $church_detail['from_user_id'] = $user->id;
        $church_detail['church_id'] = $church_details->id;
        $church_detail['church_name'] = $church_details->firstname;
        $church_detail['referral_id'] = $church_details->referral_id;
        $church_detail['project_id'] = $project_detail->id;
        $church_detail['project_name'] = $project_detail->name;
        $UserNotificationObj->send_notification($church_details->id,$user->id, "repeat_donation_stop", $church_detail,$church_details->id,'');
        /* Send Notification church */

        }
        $update_project_repeat = $this::where('project_id', $project_id)->where('is_repeat_status','0')->update(['is_repeat_status' => '1']);
        return $update_project_repeat;
   }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
   
   public function repeat_donation_cronjob($value){
     
	$userDetailObj  = new \App\User;
	$UserAddress = new \App\UserAddress;
	$UserCardInfo  = new \App\UserCardInfo;
	$projectDetailObj = new \App\Project;
	$referrarDetailObj  = new \App\Referrar;
	$projectDonationPayments  = new \App\ProjectDonationPayment;
	$gateway_server = config("constants.sagepay_gateway_server");
	$gateway = OmniPay::create($gateway_server);
	$transactionId = 'GTGR-' . rand(10000000, 99999999);
	$admintransactionId = 'ADMR-' . rand(10000000, 99999999);
	$_SESSION['transactionId'] = $transactionId;

    /* Find Percentage*/
    $admin_percentage = config("constants.sagepay_admin_percentage");
    $donation_amount = $value->amount;
    $pesantage_catlog = $admin_percentage / 100;
    $admin_percentage_retrieve = $pesantage_catlog * $donation_amount;
    $church_donation = $donation_amount - $admin_percentage_retrieve;

    /*Get church detail */
    $project = $projectDetailObj->project_detail($value->project_id);
    if($project){
        $referrarDetail= $referrarDetailObj->referrar_detail($project->church_id);

    if($referrarDetail){

        $church_referrer_Id = $referrarDetail->referrer_Id;
        $church_vendor_id = $referrarDetail->vendor_id;
    }else{

        $church_referrer_Id = '';
        $church_vendor_id = '';    
    }
    }else{

        $church_referrer_Id = '';
        $church_vendor_id = '';    
    }
    if($church_referrer_Id && $church_vendor_id){
    if ($gateway_server == 'SagePay\Direct' || $gateway_server == 'SagePay\Server') {

        $gateway = OmniPay::create($gateway_server)
        ->setVendor(config("constants.sagepay_vendor_id"))
        ->setTestMode(config("constants.sagepay_test_mode"))
        ->setReferrerId(config("constants.sagepay_Referrer_id"));

        $church_gateway = OmniPay::create($gateway_server)
        ->setVendor($church_vendor_id)
        ->setTestMode(config("constants.sagepay_test_mode"))
        ->setReferrerId($church_referrer_Id);

    } elseif ($gateway_server == 'AuthorizeNet_SIM' || $gateway_server == 'AuthorizeNet_DPM') {

        $gateway = OmniPay::create($gateway_server)
        ->setApiLoginId(getenv('API_LOGIN_ID'))
        ->setTransactionKey(getenv('TRANSACTION_KEY'))
        ->setHashSecret(getenv('HASH_SECRET'))
        ->setTestMode(true)
        ->setDeveloperMode(true);

    }
    $repeatRequest = $church_gateway->repeatPurchase([
        'transactionReference' => $value->transactionReference,
        'amount' => $church_donation,
        'transactionId' => $transactionId,
        'currency' => 'GBP',
        'description' => $value->description,
        'txtypes'        => array(
        'txtype' => 'REPEAT',
        ),
    ]);
     $repeatResponse = $repeatRequest->send();
        if ($repeatResponse->isSuccessful()) {

        $adminrepeatRequest = $gateway->repeatPurchase([
        'transactionReference' => $value->transactionReference,
        'amount' => $admin_percentage_retrieve,
        'transactionId' => $admintransactionId,
        'currency' => 'GBP',
        'description' => $value->description,
        'txtypes'        => array(
        'txtype' => 'REPEAT',
        ),
        ]);
        $adminrepeatRequest = $adminrepeatRequest->send();

        $ProjectDonationPayments  = new \App\ProjectDonationPayment;
        $projectDetailObj  = new \App\Project;
        $project = $projectDetailObj->find($value->project_id);

        $paymentdetail['church_id'] = $project->church_id;
        $paymentdetail['user_id'] = $value->user_id;
        $paymentdetail['project_id'] = $value->project_id;
        $paymentdetail['amount'] = $church_donation;
        $paymentdetail['payment_status'] = 'completed';
        $paymentdetail['payment_gateway_type'] = 'sagepay';
        $paymentdetail['payment_gateway_response'] = $repeatResponse->getTransactionReference();
        $paymentdetail['payment_transaction_id'] = $transactionId;
        $paymentdetail['qr_scanned_verified'] = $value->qr_scanned_verified;
        $paymentdetail['admin_commission'] = $admin_percentage_retrieve;
        $paymentdetail['transaction_date'] = date('Y-m-d');
        $paymentdetail['user_role_id'] = $value->user_role_id;
        $paymentdetail['created_at'] = NOW();

        $ProjectDonationPayments = $ProjectDonationPayments->create($paymentdetail); 

        if($value->declaration_id == '1'){
        $donation = $projectDonationPayments->finduserProject($value->user_id);
        }else if($value->declaration_id == '2' || $value->declaration_id == '3'){
        $donation = $projectDonationPayments->finduserProject($value->user_id);
        $donation = $projectDonationPayments->addgiftdeclaration($value->user_id,$value->project_id);
        }
        $updatedate = $this->find($value->id);
        $updatedate->update(['repeat_date' => NOW()]);

        $message = "Transaction has been successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,[]);
        return $jsonResponse;

        }elseif ($repeatResponse->isRedirect()) {

        $message = "Transaction has been failed.";
        $errorData = array();
        $errorData['church_id'] = array($message);
        $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
        return $jsonResponse;

        } else {
        $message = $repeatResponse->getMessage();
        $errorData = array();
        $errorData['church_id'] = array($repeatResponse->getMessage());
        $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
        return $jsonResponse;
        }
    }
   }
     
}
