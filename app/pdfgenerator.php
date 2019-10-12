<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_role_id', 'church_id', 'firstname', 'lastname', 'email', 'mobile', 'image', 'password', 'status', 'is_deleted', 'device_type', 'device_token', 'referral_id','qrcode', 'access_token', 'remember_token','church_reference_id','church_reference_id','gift_declaration','created_at', 'updated_at','scripture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // jwt token
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function user_role_detail(){
        return $this->belongsTo('App\UserRole','user_role_id','id');
    }
    
    public function user_settings_detail(){
        return $this->hasMany('App\UserSetting','user_id','id');
    }
    
    /**
     * Does something interesting
     *
     * Showing relationship between project and user
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function project(){
        return $this->belongsTo('App\Project','church_id','id');
    }

    /**
     * Does something interesting
     *
     * Showing relationship between event and user
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function event(){
        return $this->belongsTo('App\Event','user_id','id');
    }

    

    /**
     * Does something interesting
     *
     * Showing relationship between task and user
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function tasks(){
        return $this->belongsTo('App\Task','user_id','id');
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user detail respective user id 
     * @param integer $repeat Showing user detail 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquiant Soft Developer
     * @return Data
     */ 
    public function userDetail($id){
        $data = array();
        if( $id  !=  null )
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('id',$id)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }

 public function userDetails($id){
        $data = array();
        if( $id  !=  null )
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('id',$id)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  check user login 
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
   
    public function user_login_check($email,$user_role){
        $data = array();
        if( $email  !=  null )
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('email',$email)
                        ->where('user_role_id',$user_role)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    
    public function userLoginCheck($email,$user_role){

        $data = array();
        if( $email  !=  null )
            if($user_role == '2'){
                    $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('email',$email)
                    ->where('user_role_id',$user_role)
                    ->where('is_deleted','0')
                    ->first();
            }else{
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('email',$email)
                        ->where(function($query) {
                        $query->where('user_role_id', 3)
                        ->orWhere('user_role_id', 4);
                        })
                        ->where('is_deleted','0')
                        ->first();
            }
        return $data;
    }
 
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of user token 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public function userTokenCheck($email,$user_role){

        $data = array();
        if( $email  !=  null )
            if($user_role == '2'){
                    $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('email',$email)
                    ->where('user_role_id',$user_role)
                    ->first();
            }else{
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('email',$email)
                        ->where(function($query) {
                        $query->where('user_role_id', 3)
                        ->orWhere('user_role_id', 4);
                        })
                        ->first();
            }
        return $data;
    }


     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of email 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    
     public function userLoginEmail($email){
        $data = array();
        if( $email  !=  null)
            $data =  $this::where('email',$email)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of forgot password 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

      public function userCheckEmailForgot($email,$user_role_id){
        $data = array();
        if( $email  !=  null)
            $data =  $this::where('email',$email)
                        ->where('user_role_id',$user_role_id)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  check user login 
     * @param return return check login 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function user_login_check_admin($email,$id=null){
        $data = array();
        if( $email  !=  null && $id != null)
            $data =  $this::where('email',$email)
                        ->where('id','!=',$id)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        else
            $data =  $this::where('email',$email)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  check user login 
     * @param return return check login 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function user_reference_check_admin($church_reference_id,$id=null){
        $data = array();
        if( $church_reference_id  !=  null && $id != null)
            $data =  $this::where('church_reference_id',$church_reference_id)
                        ->where('id','!=',$id)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        else
            $data =  $this::where('church_reference_id',$church_reference_id)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  check user login
     * @param integer $repeat Showing data of user with unique mobile number
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function user_mobile_check($mobile,$id=null){
        $data = array();
        if( $mobile  !=  null && $id != null)
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('mobile',$mobile)
                        ->where('id','!=',$id)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        else
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('mobile',$mobile)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }

/**
     * Does something interesting
     *
     * @param Place   $where  Identify church list from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church list
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function qrCodeChurchList($qr_code){

            $data =  $this::with(['user_role_detail','user_settings_detail'])
            ->where('user_role_id',3)
            ->where('status','active')
            ->where('qrcode',$qr_code)
            ->where('is_deleted','0')
            ->get()->first();
            return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify church list from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church list
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function churchList($page,$getdata,$search_text=null,$search_referral=null){
        $data = array();
        if($search_text == null && $search_referral == null){
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('user_role_id',3)
                        ->where('status','active')
                        ->where('is_deleted','0');
                        if($getdata == '1'){
                            $data = $data->paginate(10)->toArray();  
                        }else{
                            $data = $data->skip($page)->take(10)->get();
                        }

        }
        else if($search_text != null){
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('user_role_id',3)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->Where(DB::raw("CONCAT(`firstname`, ' ', `lastname`)"), 'LIKE', "%".$search_text."%");
                        if($getdata == '1'){
                            $data = $data->paginate(10)->toArray();  
                        }else{
                            $data = $data->skip($page)->take(10)->get();
                        }
        }
        else{
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('user_role_id',3)
                        ->where('referral_id','LIKE','%'.$search_referral.'%')
                        ->where('status','active')
                        ->where('is_deleted','0');
                        if($getdata == '1'){
                            $data = $data->paginate(10)->toArray();  
                        }else{
                            $data = $data->skip($page)->take(10)->get();
                        }
                       
        }
        return $data;
    }
    

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public function churchCount(){
        $data = array();
        $data =  $this::where('user_role_id',3)
                    ->count();
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */ 
    public function userList($church_id,$user_role_id){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id',$user_role_id)
                    ->where('church_id',$church_id)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();
        if(count($data) > 0)
            return $data;
        else
            return (object)array();
    }
   
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of user dashboard 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function userListDashboard($church_id){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('church_id',$church_id)
                    ->where('user_role_id',2)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();
        if(count($data) > 0)
            return $data;
        else
            return (object)array();
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
    public function userListPagination($page,$getdata,$church_id,$user_role_id,$search = null){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id',$user_role_id)
                    ->where('church_id',$church_id)
                    ->where(DB::raw("CONCAT(`firstname`, ' ', `lastname`)"),
                     'LIKE', "%".$search."%")
                    ->where('status','active')
                    ->where('is_deleted','0');
                    if($getdata == '1'){
                        $data = $data->paginate(10)->toArray();  
                    }else{
                        $data = $data->skip($page)->take(10)->get();
                    }
                   
        if(count($data) > 0)
            return $data;
        else
            return (object)array();
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function allUserList(){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id','!=','1')
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list acsept church from database
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function withoutChurchList(){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id','!=','1')
                    ->where('user_role_id','!=','3')
                   /* ->where('status','active')*/
                    ->where('is_deleted','0')
                    ->get();
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function getAllChurch(){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id','3')
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify church list from database 
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function adminChurchList(){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id',3)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
     public function groupChurchList($userid){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('church_id',$userid)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();
            
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 


     public function getchutchname($church_id){
        $data = array();
        $data =  $this::where('user_role_id',3)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->where('id',$church_id)
                    ->first();
        return $data;
    }
   
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

     public function getSingleuserDetail($userid){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id',3)
                     ->where('id',$userid)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->first();
        return $data;
    }
    

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    
    public function adminChurchReferral($referral_id){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id',3)
                    ->where('referral_id',$referral_id)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->first();
        return $data;
    }
}
