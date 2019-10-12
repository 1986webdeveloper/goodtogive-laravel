<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Http\Controllers\GeneralController AS General;
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
        'name', 'email', 'password', 'user_role_id', 'church_id', 'firstname', 'lastname', 'email', 'mobile', 'image','dob', 'password', 'status', 'is_deleted', 'device_type', 'device_token', 'referral_id','qrcode', 'access_token', 'remember_token','church_reference_id','church_reference_id','gift_declaration','qrcode_visitor','created_at', 'updated_at','new_request','is_custom','is_primary','is_screen_view','background_Image_default','scripture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The attributes that should be forcedNullFields for arrays.
     *
     * @var array
     */
    
    protected $forcedNullFields = [
        'dob'
    ];
    
    /**
     * The attributes that should be forcedNullFields for arrays.
     *
     * @var array
     */
    

    public static function boot()
    {
        
        parent::boot();

        static::saving(function($model)
        {

            if (count($model->forcedNullFields) > 0) {

                foreach ($model->toArray() as $fieldName => $fieldValue) {
                    if ( empty($fieldValue) && in_array($fieldName,$model->forcedNullFields)) {
                       $model->attributes[$fieldName] = '';
                    }else if($fieldName == 'dob' && $fieldValue == '0000-00-00'){
                      $model->attributes[$fieldName] = '';  
                    }
                }
            }

            return true;
        });

    }

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
                        /*->where('status','active')*/
                        ->where('is_deleted','0')
                        ->first();
            if($data){
             foreach ($data->user_settings_detail as $key => $value) {
                    if($value->field_name == 'BACKGROUND_IMAGE'){
                    $value->field_value = General::get_file_src($value->field_value);
                    }
                    }
            }

        return $data;
    }
    /**
        * Does something interesting
        *
        * Showing user
        * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
        * @return Data
    */
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
     public function userDetails_scripture($id){
        $data = array();
        if( $id  !=  null )
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('church_id',$id)
                        ->where('user_role_id',4)
                        ->where('status','active')
                        ->where('is_deleted','0')
                        ->get();
        return $data;
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
    public function userDetailAccept_reject($id,$church_id){
        $data = array();
        if( $id  !=  null )
                $data =  $this::with(['user_role_detail','user_settings_detail']);
                $data = $data->join('user_multi_churches as umc','umc.user_id','=','users.id')
                ->select('users.*','umc.user_id','umc.church_id','umc.primary_church','umc.new_request','umc.is_deleted','umc.user_role_id');
                $data = $data->where('umc.church_id',$church_id)
                ->where('users.id',$id)
                ->where('users.status','active')
                ->first();
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
     /**
        * Does something interesting
        *
        * Showing check login
        * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
        * @return Data
    */
    public function userLoginCheck($email,$user_role){

        $data = array();
        if( $email  !=  null )
            if($user_role == '2' || $user_role == '5'){
                    $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('email',$email)
                    ->where('is_deleted','0')
                    ->where(function($query) {
                        $query->where('user_role_id', 2)
                        ->orWhere('user_role_id', 5);
                        })
                    ->first();
            }else{
            $data =  $this::with(['user_role_detail','user_settings_detail'])
                        ->where('email',$email)
                        ->where('is_deleted','0')
                        ->where(function($query) {
                        $query->where('user_role_id', 3)
                        ->orWhere('user_role_id', 4);
                        })
                        ->first();
            if($data){
                    foreach ($data->user_settings_detail as $key => $value) {
                    if($value->field_name == 'BACKGROUND_IMAGE'){
                    $value->field_value = General::get_file_src($value->field_value);
                    }
                    }
                }
            }
        return $data;
    }

     /**
            * Does something interesting
            *
            * Showing user tocken check
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
        * Showing user login by email
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
            * Showing user email forgot
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
    public function qrCodeChurchList($qr_code,$user_role_id){

                $data =  $this::with(['user_role_detail','user_settings_detail'])
                ->where('user_role_id',3)
                ->where('status','active');
            if($user_role_id == '2'){
                 $data  =  $data->where('qrcode',$qr_code);
            }else{
                $data = $data->where(function($query) use($qr_code) {
                    $query->where('qrcode_visitor',$qr_code);
                    $query->orwhere('qrcode',$qr_code);
                });
            }
               $data = $data->where('is_deleted','0')
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
    public function qrCodeUserList($qr_code){

                $data =  $this::with(['user_role_detail','user_settings_detail'])
                ->where('user_role_id','!=','3')
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
        * Showing church count
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
        * Showing dashboard list
        * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
        * @return Data
    */

    public function userListDashboard($church_id){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->join('user_multi_churches as umc','umc.user_id','=','users.id')
                    ->where('umc.church_id',$church_id)
                    ->where('umc.user_role_id',2)
                    ->where('users.status','active')
                    ->where('umc.new_request','Y')
                    ->where('users.is_deleted','0')
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
    public function userListPagination($page,$getdata,$church_id,$user_role_id,$search = null,$is_register = null,$is_visitor = null,$task_user_select = null,$task_group_select = null){

        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail']);
            if($user_role_id != 4){
                $data = $data->join('user_multi_churches as umc','umc.user_id','=','users.id')
                ->select('users.*','umc.user_id','umc.church_id','umc.primary_church','umc.new_request','umc.is_deleted','umc.user_role_id');
            } 

        if($user_role_id == '2'){
            if($is_register == 'N'){
                $data = $data->where('umc.new_request',$is_register)
                ->where('umc.user_role_id',$user_role_id);
            }else{
                if($is_visitor == 'Y'){
                    $data = $data->where(function($query) use($is_register) {
                    $query->where('umc.user_role_id',2)->where('umc.new_request',$is_register);
                    $query->orwhere('umc.user_role_id',5);
                    });
                }else{
                    $data = $data->where(function($query) use($is_register) {
                    $query->where('umc.user_role_id',2)->where('umc.new_request',$is_register);
                    });  
                }
            }
        }else if($user_role_id == '5'){
            $data = $data->where('umc.user_role_id',$user_role_id);
        }else{
            $data = $data->where('users.user_role_id',$user_role_id);   
        }

        if($user_role_id == 4){
            $data = $data->where('users.church_id',$church_id);
        }else{
            $data = $data->where('umc.church_id',$church_id);
        }

        if($search != null){
            $data = $data->where(DB::raw("CONCAT(`firstname`, ' ', `lastname`)"),
            'LIKE', "%".$search."%");
        }

        $data = $data->where('users.status','active')
        ->where('users.is_deleted','0');
        if($user_role_id != 4){
            if($task_user_select){
                $ids = [$task_user_select];
                $data = $data->orderBy(DB::raw('FIELD(`users`.`id`, '.implode(',', $ids).')'),'DESC');
            }elseif($task_group_select){
            /*echo $task_group_select; exit;*/
                $ids = [$task_group_select];
                $data = $data->orderBy(DB::raw('FIELD(`users`.`id`, '.implode(',', $ids).')'),'DESC');  
            }else{
                $data = $data->where('umc.is_deleted','0')->orderby('users.id','DESC');
            }

        }else{
            if($task_user_select){
                $ids = [$task_user_select];
                $data = $data->orderBy(DB::raw('FIELD(`users`.`id`, '.implode(',', $ids).')'),'DESC');
            }elseif($task_group_select){
                /*echo $task_group_select; exit;*/
                $ids = [$task_group_select];
                $data = $data->orderBy(DB::raw('FIELD(`users`.`id`, '.implode(',', $ids).')'),'DESC');  
            }
        }

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
                    ->where('is_deleted','0')
                    ->get()->toArray();
        return $data;
    }
 public function withoutChurchList_list(){
        $data = array();
        $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id','!=','1')
                    ->where('user_role_id','!=','3')
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
    public function MemberNewReuqest(){

        $data = array();
        $data =  \DB::table('user_multi_churches as umc')
                 ->join('users as u','u.id','=','umc.user_id')
                 ->select('u.firstname','u.lastname','u.image','u.user_role_id','u.email','u.mobile','umc.id','umc.church_id','umc.user_id','u.status')
                 ->where('umc.new_request','N')
                 ->where('umc.is_deleted','0')
                 ->where('u.is_deleted','0')
                ->get();
      /*  $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id','!=','1')
                    ->where('user_role_id','!=','3')
                    ->where('user_role_id','!=','4')
                    ->where('user_role_id','!=','5')
                    ->where('new_request','N')
                   /* ->where('status','active')*/
                   /* ->where('is_deleted','0')
                    ->get();*/
        
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
     * @param Place   $where  Identify church list from database 
     * @param integer $repeat Showing list of church 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function adminChurchListuser($user_id){
        $data = array();
         $data =  \DB::table('user_multi_churches as umc')
                ->rightjoin('users as u', function ($join)use($user_id) {
                $join->on('u.id', '=', 'umc.church_id')
                ->where('umc.user_id','=', $user_id)
                ->where('umc.is_deleted','=', '0');
                })
                ->select('u.*')
                ->where('u.user_role_id','3')
                ->where('u.is_deleted','0')
                ->where('u.status','active')
                ->whereNull('umc.id')->get();
       /* $data =  $this::with(['user_role_detail','user_settings_detail'])
                    ->where('user_role_id',3)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->get();*/
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
      public function adminChurchListUserSelect($user_id){ 

         $data =  \DB::table('users as u')
                    ->join('user_multi_churches as umc','umc.church_id', '=','u.id')
                    ->select('u.id','u.firstname','u.image','umc.new_request','umc.user_role_id','umc.primary_church')
                    ->where('umc.user_id',$user_id)
                    ->where('umc.is_deleted','0')
                    ->where('u.user_role_id','3')
                    ->where('u.is_deleted','0')
                    ->where('u.status','active')->get();
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
     * @param Place   $where  Identify church list from database 
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
     * @param Place   $where  Identify church list from database 
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
     * @param Place   $where  Identify church list from database 
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
     public function getchurchpastorlist($church_id){
        $data = array();
        $data =  $this::where('user_role_id',4)
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->where('church_id',$church_id)
                    ->get()->all();
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
     public function delete_user_church($church_id){
        $data = array();
        $data =  $this::where('is_deleted','0')
                    ->where('church_id',$church_id)
                    ->get()->all();
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
     public function single_church_detail($church_id){
        $data = array();
        $data =  $this::select('id','firstname','image','referral_id')
                    ->where('status','active')
                    ->where('is_deleted','0')
                    ->where('id',$church_id)
                    ->get()->first();
                     
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
    public function switch_church_list($page,$getdata,$user_id,$search = null){
        $data = array();
        $data =  \DB::table('user_multi_churches as umc')
                ->rightjoin('users as u', function ($join)use($user_id) {
                $join->on('u.id', '=', 'umc.church_id')
                ->where('umc.user_id','=', $user_id)
                ->where('umc.is_deleted','=', '0');
                })
                ->select('u.id','u.firstname','u.image')
                ->where('u.user_role_id','3')
                ->where('u.is_deleted','0')
                ->where('u.status','active')
                ->whereNull('umc.id');
        if($search != null){
        $data = $data->where('u.firstname','LIKE',"%".$search."%");
         }
        if($getdata == '1'){
            $data = $data->paginate(10)->toArray();  
        }else{
            $data = $data->skip($page)->take(10)->get();
        }
      
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
    public function selecte_switch_church_list($page,$getdata,$user_id,$search = null){
        $data = array();
        $data =  \DB::table('users as u')
                ->join('user_multi_churches as umc','umc.church_id', '=','u.id')
                ->select('u.id','u.firstname','u.image','u.referral_id','umc.new_request','umc.user_role_id','umc.primary_church')
                ->where('umc.user_id',$user_id)
                ->where('umc.is_deleted','0')
                ->where('u.user_role_id','3')
                ->where('u.is_deleted','0')
                ->where('u.status','active');
        if($search != null){
        $data = $data->where('u.firstname','LIKE',"%".$search."%");
         }
        if($getdata == '1'){
            $data = $data->paginate(10)->toArray();  
        }else{
            $data = $data->skip($page)->take(10)->get();
        }
      
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
    public function user_detail_sendnotification($user_id,$church_id){
        $data = array();
        $user_detail = $this::where('id',$user_id)->first();
        if($user_detail){
        if($user_detail->user_role_id == 2 || $user_detail->user_role_id == 5){
       
        $data =  \DB::table('users as u')
                ->join('user_multi_churches as umc','umc.user_id', '=','u.id')
                ->select('u.id','u.firstname','u.email','u.mobile','u.image','umc.new_request','umc.user_role_id','umc.primary_church','umc.church_id','umc.user_id')
                ->where('umc.user_id',$user_id)
                ->where('umc.church_id',$church_id)
                ->where('u.is_deleted','0')
                ->where('u.status','active')->get()->first();
        }else{

            $data =  \DB::table('users')->where('id',$user_id)->get()->first();

        }
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
    public function dob_detail_datewise($church_id,$month){
        $data = array();
      
        if( $church_id  !=  null )
            $data =  $this::where('church_id',$church_id)
                        ->whereMonth('dob' , $month)
                        ->where('is_deleted','0')
                        ->get();


        return $data;
    }

    

}
