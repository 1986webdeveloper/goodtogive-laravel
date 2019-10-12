<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade as PDF;
/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @copyright  2019 acquaintsoft Pvt. Ltd
 * @license    http://example.com   PHP License 1.0
 * @version    Release: @package_version@
 * @link       http://example.com
 * @since      Class available since Release 1.0.0
 */

class ProjectDonationPayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id','church_id', 'amount','admin_commission', 'payment_status', 'payment_gateway_type', 'payment_gateway_response', 'payment_transaction_id','transaction_date','user_role_id','qr_scanned_verified', 'is_deleted', 'created_at', 'updated_at'
    ];


     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'payment_gateway_response',
    ];

    /**
     * Does something interesting
     *
     * Showing relationship between project donation and user
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function projectDonationPayment(){
        return $this->belongsTo('App\User','id','user_id');
    }
    public function user_details(){
        return $this->belongsTo('App\User','user_id','id');
    }
    public function project_detail(){
        return $this->belongsTo('App\Project','project_id','id');
    }

/**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function ProjectDonationPaymentDetail($user_id){
        $data = array();
        if( $user_id != null ){
            $data =  ProjectDonationPayment::where('user_id',$user_id)
                        ->where('is_deleted','0')
                        ->groupBy('project_id')
                        ->get()->all();
            }
        
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function total_fund_collection_dnation($project_id){

         $data = array();
        $data =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->sum('amount');
        
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function total_fund_collection($project_id){
        $data = array();
        if( $project_id != null ){
            $total_amount =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->sum('amount');

            $data =(object) array(
                        'total_fund_collection' => $total_amount
                    );
            }
        return $data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function totalFundCollection($project_id){
        $data = 0;
         $dates = range(config("constants.financial_year_start"), date('Y'));

        $date_slot = array();
        foreach($dates as $date){

        if (date('m', strtotime($date)) <= 6) { //Upto June
        $year = ($date-1).'-04-'.'06' . ' To ' . $date.'-04-'.'05';
        } else { //After June
        $year = $date.'-04-'.'06' . ' To ' . ($date + 1).'-04-'.'05';
        }
        $date_slot[] = $year;
        }
        $data_yearwise = array();
        foreach ($date_slot as $key => $value) {

             $date_exp = explode(" To ",$value);
           $start_date = $date_exp[0];
           $end_date  = $date_exp[1];

            $split_startdate = date("d-m-Y", strtotime($start_date));
            $split_enddate = date("d-m-Y", strtotime($end_date));
            $dateyearwise = $split_startdate.' To '.$split_enddate;

        if( $project_id != null ){
            $data =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->where('transaction_date','>=',$start_date)
                        ->where('transaction_date','<=',$end_date)
                        ->sum('amount');
            }
        $data_yearwise[$dateyearwise] = $data;
        }
        $data_yearwise = array_map(function ($date, $res) {
         
        return [
        "year" => $date,
        "transaction" => $res
        ];
        }, array_keys($data_yearwise), array_values($data_yearwise));
       
        return $data_yearwise;
        
    }
   
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function totalFundCollectionprojectamount($project_id,$user_role_id = null){
            $data =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->sum('amount');
           return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function totalFundCollectionproject($project_id,$user_role_id = null){
         $data = 0;
        $dates = range(config("constants.financial_year_start"), date('Y'));

        $date_slot = array();
        foreach($dates as $date){

       if (date('m', strtotime($date)) <= 6) { //Upto June
        $year = ($date-1).'-04-'.'06' . ' To ' . $date.'-04-'.'05';
        } else { //After June
        $year = $date.'-04-'.'06' . ' To ' . ($date + 1).'-04-'.'05';
        }
        $date_slot[] = $year;
        }
        $data_yearwise = array();
         foreach ($date_slot as $key => $value) {
            
           $date_exp = explode(" To ",$value);
           $start_date = $date_exp[0];
           $end_date  = $date_exp[1];

            $split_startdate = date("d-m-Y", strtotime($start_date));
            $split_enddate = date("d-m-Y", strtotime($end_date));
            $dateyearwise = $split_startdate.' To '.$split_enddate;

            if( $project_id != null ){
            $data =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->where('user_role_id',$user_role_id)
                        ->where('transaction_date','>=',$start_date)
                        ->where('transaction_date','<=',$end_date)
                        ->sum('amount');
            }
            
            $data_yearwise[$dateyearwise] = $data;
          }

        $data_yearwise = array_map(function ($date, $res) {
         
        return [
        "year" => $date,
        "transaction" => $res
        ];
        }, array_keys($data_yearwise), array_values($data_yearwise));
       
        return $data_yearwise;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */

    public function projectDonationPayementList($project_id){
        $data = array();
        if( $project_id != null ){
            $total_amount =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->get();
            }
        return $data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function projectDonationPayementCount($project_id){
        $data = 0;
        if( $project_id != null ){
            $data =  ProjectDonationPayment::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->count();
            }
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function getChurchDonationHistory($project_id,$church_id,$role_ids = null)
    { 
        $dates = range(config("constants.financial_year_start"), date('Y'));

        $date_slot = array();
        foreach($dates as $date){

        if (date('m', strtotime($date)) <= 6) { //Upto June
        $year = ($date-1).'-04-'.'06' . ' To ' . $date.'-04-'.'05';
        } else { //After June
        $year = $date.'-04-'.'06' . ' To ' . ($date + 1).'-04-'.'05';
        }
        $date_slot[] = $year;
        }
        $data_yearwise = array();
        foreach ($date_slot as $key => $value) {
            
           $date_exp = explode(" To ",$value);
           $start_date = $date_exp[0];
           $end_date  = $date_exp[1];
           $split_startdate = date("d-m-Y", strtotime($start_date));
           $split_enddate = date("d-m-Y", strtotime($end_date));
           $dateyearwise = $split_startdate.' To '.$split_enddate;
           $data =  ProjectDonationPayment::
                          select('project_donation_payments.id',
                                 'project_donation_payments.user_id',
                                 'project_donation_payments.project_id',
                                 'project_donation_payments.church_id',
                                 'project_donation_payments.amount',
                                 'project_donation_payments.admin_commission',
                                 'project_donation_payments.payment_status',
                                 'project_donation_payments.payment_gateway_type',
                                 'project_donation_payments.payment_gateway_response',
                                 'project_donation_payments.payment_transaction_id',
                                 'project_donation_payments.qr_scanned_verified',
                                 'project_donation_payments.transaction_date',
                                 'users.id as user_id',
                                 'users.church_id as user_church_id',
                                 'project_donation_payments.user_role_id',
                                 'users.firstname',
                                 'users.lastname',
                                 'users.email',
                                 'users.mobile',
                                 'users.gift_declaration',
                                 'users.new_request'
                                )
                        ->leftJoin('users', 'project_donation_payments.user_id', '=', 'users.id')
                        ->where('project_donation_payments.project_id',$project_id)
                        ->where('project_donation_payments.church_id',$church_id)
                        ->where('project_donation_payments.transaction_date','>=',$start_date)
                        ->where('project_donation_payments.transaction_date','<=',$end_date)
                        ->where('project_donation_payments.is_deleted','0')
                        ->orderBy('transaction_date','DESC')
                        ->get()->all();
          
            $data = array_values(array_filter($data, function ($value) use($role_ids){
                
            return ($value->user_role_id == $role_ids);
            }));
          $data_yearwise[$dateyearwise] = $data;
        }
        $data_yearwise = array_map(function ($date, $res) {
        return [
        "year" => $date,
        "transaction" => array_values($res)
        ];
        }, array_keys($data_yearwise), array_values($data_yearwise));

       /* $data =  ProjectDonationPayment::
                          select('project_donation_payments.id',
                                 'project_donation_payments.user_id',
                                 'project_donation_payments.project_id',
                                 'project_donation_payments.church_id',
                                 'project_donation_payments.amount',
                                 'project_donation_payments.admin_commission',
                                 'project_donation_payments.payment_status',
                                 'project_donation_payments.payment_gateway_type',
                                 'project_donation_payments.payment_gateway_response',
                                 'project_donation_payments.payment_transaction_id',
                                 'project_donation_payments.qr_scanned_verified',
                                 'project_donation_payments.transaction_date',
                                 'users.id as user_id',
                                 'users.church_id as user_church_id',
                                 'users.user_role_id',
                                 'users.firstname',
                                 'users.lastname',
                                 'users.email',
                                 'users.mobile',
                                 'users.gift_declaration',
                                 'users.new_request'
                                )
                        ->leftJoin('users', 'project_donation_payments.user_id', '=', 'users.id')
                        ->where('project_donation_payments.project_id',$project_id)
                        ->where('project_donation_payments.church_id',$church_id)
                        ->where('project_donation_payments.is_deleted','0')
                        ->orderBy('transaction_date','DESC')
                        ->get()->all();

            $data = array_values(array_filter($data, function ($value) use($role_ids){
                
            return ($value->user_role_id == $role_ids);
            }));*/
        return $data_yearwise;
    }


     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function getUserDonationHistory($project_id,$user_id)
    {
        
         $dates = range(config("constants.financial_year_start"), date('Y'));

        $date_slot = array();
        foreach($dates as $date){

        if (date('m', strtotime($date)) <= 6) { //Upto June
        $year = ($date-1).'-04-'.'06' . ' To ' . $date.'-04-'.'05';
        } else { //After June
        $year = $date.'-04-'.'06' . ' To ' . ($date + 1).'-04-'.'05';
        }
        $date_slot[] = $year;
        }
        $data_yearwise = array();
        foreach ($date_slot as $key => $value) {
            
           $date_exp = explode(" To ",$value);
           $start_date = $date_exp[0];
           $end_date  = $date_exp[1];
            $split_startdate = date("d-m-Y", strtotime($start_date));
           $split_enddate = date("d-m-Y", strtotime($end_date));
           $dateyearwise = $split_startdate.' To '.$split_enddate;
           $data =  ProjectDonationPayment::
                        select('project_donation_payments.id',
                                 'project_donation_payments.user_id',
                                 'project_donation_payments.project_id',
                                 'project_donation_payments.church_id',
                                 'project_donation_payments.amount',
                                 'project_donation_payments.admin_commission',
                                 'project_donation_payments.payment_status',
                                 'project_donation_payments.payment_gateway_type',
                                 'project_donation_payments.payment_gateway_response',
                                 'project_donation_payments.payment_transaction_id',
                                 'project_donation_payments.qr_scanned_verified',
                                 'project_donation_payments.transaction_date',
                                 'users.id as user_id',
                                 'users.church_id as user_church_id',
                                 'users.user_role_id',
                                 'users.firstname',
                                 'users.lastname',
                                 'users.email',
                                 'users.mobile',
                                 'users.gift_declaration',
                                 'users.new_request'
                                )
                        ->leftJoin('users', 'project_donation_payments.user_id', '=', 'users.id')
                        ->where('project_donation_payments.project_id',$project_id)
                        ->where('project_donation_payments.user_id',$user_id)
                        ->where('project_donation_payments.transaction_date','>=',$start_date)
                        ->where('project_donation_payments.transaction_date','<=',$end_date)
                        ->where('project_donation_payments.is_deleted','0')
                        ->orderBy('transaction_date','DESC')
                        ->get()->all();
            foreach ($data as $key => $value) {
             $value->amount = $value->amount + $value->admin_commission;
            }
           $data_yearwise[$dateyearwise] = $data;             
       }
        $data_yearwise = array_map(function ($date, $res) {
        return [
        "year" => $date,
        "transaction" => array_values($res)
        ];
        }, array_keys($data_yearwise), array_values($data_yearwise));
        return $data_yearwise;
    }
    

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify total ammount of fund
     * @param integer $repeat Showing count of total ammount of collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer 
     * @return Data
     */
    public function fund_ammount(){
        $data = array();
        $data =  ProjectDonationPayment::where('is_deleted','0')
                    ->sum('amount');
        
        return $data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested fund collection
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function fundAmount($church_id){
        $total_amount = array();
        $total_amount =  ProjectDonationPayment::where('church_id',$church_id)
                    ->where('is_deleted','0')
                    ->sum('amount');
        
        return $total_amount;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested pdf
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function pdf($user_id,$project_id,$form_id)
    {
            $App_url = config('app.url');
            $url =  $App_url.'pdf/'.$user_id; 
            //$url = 'https://sector4.acquaintsoft.com/goodtogive-web/pdf/'.$user_id;
            $filename = 'storage/pdf/GTG-'.$user_id.'_'.$project_id.'_'.$form_id.'.pdf';
            $filename_store = 'GTG-'.$user_id.'_'.$project_id.'_'.$form_id.'.pdf';
            $curlCh = curl_init();
            curl_setopt($curlCh, CURLOPT_URL, $url);
            curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlCh, CURLOPT_SSLVERSION,3);
            $curlData = curl_exec ($curlCh);
            curl_close ($curlCh);
            $downloadPath = public_path($filename);
            $file = fopen($downloadPath, "w+");
            fputs($file, $curlData);
            fclose($file);
            return $filename_store;
    }
   
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested project
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */

    public function finduserProject($user_id){
         
        $usergiftdeclarations  = new \App\UserGiftDeclarations;
        $declarationCms  = new \App\DeclarationCms;
        $declarationCms = $declarationCms->get_declaration_form();
        $findproject = array();
        $findproject =  ProjectDonationPayment::where('user_id',$user_id)
                    ->where('is_deleted','0')
                    ->groupBy('project_id')->get();
        foreach ($findproject as $key => $value) {
         $donation = $usergiftdeclarations->check_project_gift($user_id,$value->project_id);
         
         if($donation == '0'){
            $pdfgenerate = self::pdf($user_id,$value->project_id,$declarationCms->id);
         $donation = $usergiftdeclarations->git_add_with_declaration($user_id,$value->project_id,$declarationCms->id,$pdfgenerate);   
         }
        }
        return $findproject;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested gift declaration
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
     public function addgiftdeclaration($user_id,$project_id){
         
        $usergiftdeclarations  = new \App\UserGiftDeclarations;
        $declarationCms  = new \App\DeclarationCms;
        $declarationCms = $declarationCms->get_declaration_form();
        
         $donation = $usergiftdeclarations->check_project_gift($user_id,$project_id);
         
         if($donation == '0'){
             $pdfgenerate = self::pdf($user_id,$project_id,$declarationCms->id);
         $donation = $usergiftdeclarations->git_add_with_declaration($user_id,$project_id,$declarationCms->id,$pdfgenerate);   
         }
        return $donation;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested report
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
     public function church_trasaction_report($page,$getdata,$church_id,$start_date,$end_date,$project_id){
        $data = array();
        $split_startdate = date("d-m-Y", strtotime($start_date));
        $split_enddate = date("d-m-Y", strtotime($end_date));
        $dateyearwise = $split_startdate.' To '.$split_enddate;

        $data =  ProjectDonationPayment::with(['project_detail','user_details'])
        ->where('church_id',$church_id)
        ->where('transaction_date','>=',$start_date)
        ->where('transaction_date','<=',$end_date)
        ->where('is_deleted','0');

        if($project_id){
            $project_ids = explode(",", $project_id);
            $data = $data->whereIn('project_id', $project_ids);
        }
         $grand_total = $data->sum('amount'); 
        if($getdata == '1'){
            $data = $data->paginate(10)->toArray();  
            $data_yearwise = $data;
        }else{
            $data = $data->skip($page)->take(10)->get()->all();
            $data_yearwise[$dateyearwise] = $data;

            $data_yearwise = array_map(function ($date, $res)use($grand_total) {
            return [
                "grand_total" => $grand_total,
                "year" => $date,
                "transaction" => array_values($res)
            ];
            }, array_keys($data_yearwise), array_values($data_yearwise));

        }

        return $data_yearwise;
     }


       /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested report
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
     public function church_trasaction_report_user($page,$getdata,$user_id,$start_date,$end_date,$project_id){
        $data = array();
        $split_startdate = date("d-m-Y", strtotime($start_date));
        $split_enddate = date("d-m-Y", strtotime($end_date));
        $dateyearwise = $split_startdate.' To '.$split_enddate;

        $data =  ProjectDonationPayment::with(['project_detail'])
        ->where('user_id',$user_id)
        ->where('transaction_date','>=',$start_date)
        ->where('transaction_date','<=',$end_date)
        ->where('is_deleted','0');

        if($project_id){
            $project_ids = explode(",", $project_id);
            $data = $data->whereIn('project_id', $project_ids);
        }
        $grand_total = $data->sum('amount'); 
        if($getdata == '1'){
            $data = $data->paginate(10)->toArray();  
            $data_yearwise = $data;
        }else{
            $data = $data->skip($page)->take(10)->get()->all();
            $data_yearwise[$dateyearwise] = $data;

            $data_yearwise = array_map(function ($date, $res)use($grand_total) {
            return [
                "grand_total" => $grand_total,
                "year" => $date,
                "transaction" => array_values($res)
            ];
            }, array_keys($data_yearwise), array_values($data_yearwise));

        }

        return $data_yearwise;
     }


     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested report
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
     
     public function church_trasaction_year_list($church_id){
         
        $data = array();
        $user_detail =  \DB::table('user_settings')->where('field_name','FINACIAL_DAY_MONTH')->where('user_id',$church_id)->where('status','active')->where('is_deleted','0')->get()->first();
        
        if($user_detail && $user_detail->field_value){
            $arraydata = explode("/", $user_detail->field_value);

            $start_date = $arraydata[0]; 
            $start_month = $arraydata[1]; 

            $end_date = $arraydata[0] - 1; 
            $end_month = $arraydata[1]; 

        }else{
            $start_date ='6';
            $start_month ='4';

            $end_date ='5';
            $end_month ='4';
        }

        $donation_first =  ProjectDonationPayment::where('church_id',$church_id)->where('is_deleted','0')->orderBy('id', 'ASC')->get()->first();

        if($donation_first){
             $year = date('Y', strtotime($donation_first->transaction_date));
        }else{
            $year = date('Y');
        }
        
        $dates = range($year, date('Y'));
        $date_slot = array();
        
        foreach($dates as $date){

        if (date('m', strtotime($date)) <= 6) { //Upto June
            $year = ($date-1).'-0'.$start_date.'-0'.$start_month. ' To ' . $date.'-0'.$end_date.'-0'.$end_month;
        } else { //After June
            $year = $date.'-0'.$start_date.'-0'.$start_month. ' To ' . ($date + 1).'-0'.$end_date.'-0'.$end_month;
        }
            $date_slot[] = $year;
        }

        return $date_slot;
       // $data_yearwise = array();

     }
}
