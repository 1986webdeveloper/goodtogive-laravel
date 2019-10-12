<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Illuminate\Database\Eloquent\Model;

class UserMultiChurch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'church_id','new_request','user_role_id','primary_church', 'is_deleted'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
  
    public function user_settings_detail(){
        return $this->hasMany('App\UserSetting','user_id','id');
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
    public function user_church_list($user_id){
        
        $data = array();
        $data =  UserMultiChurch::where('user_id',$user_id)
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
    public function find_user_already_register($user_id,$church_id){
        
        $data = array();
        $data =  UserMultiChurch::where('user_id',$user_id)
                        ->where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->count();
        
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
    public function update_user_wise_role($user_id,$church_id,$user_role_id){
        
        $data = array();
         
        $data =  UserMultiChurch::where('user_id',$user_id)
                        ->where('church_id',$church_id)
                        ->update(['user_role_id'=>$user_role_id]);
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
    public function update_user_request($user_id,$new_request,$is_deleted,$church_id){
        
        $data = array();
         
        $data =  UserMultiChurch::where('user_id',$user_id)
                        ->where('church_id',$church_id)
                        ->update(['new_request'=>$new_request,'is_deleted'=>$is_deleted]);
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
    public function count_active_church($user_id){
        
        $data = array();
           $data =  UserMultiChurch::where('user_id',$user_id)
                        ->where('new_request','Y')
                        ->where('is_deleted','0')
                        ->count();
    
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
    public function delete_church_all_user($church_id){
        
        $data = array();
           $data =  UserMultiChurch::where('church_id',$church_id)->get()->all();
    
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
    public function update_scripture_notification($church_id){
        
            $data = array();
            $data =  UserMultiChurch::where('church_id',$church_id)
                        ->where('new_request','Y')
                        ->where('is_deleted','0')
                        ->get();
    
        return $data;
    }

}
