<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'field_name', 'field_value', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user setting
     * @param integer $repeat  
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint developer
     * @return Data
     */ 
    public function userSetting($user_id,$field_name){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('user_id',$user_id)
                        ->where('field_name',$field_name)
                        ->where('is_deleted','0')
                        ->first();
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

     
     public function updateuserSetting($user_id,$field_name,$field_value){
        $data = array();
         $count_data  =  $this::where('status','active')
                        ->where('user_id',$user_id)
                        ->where('field_name',$field_name)
                        ->where('is_deleted','0')
                        ->count();
         if($count_data > 0){
        $data =  $this::where('status','active')
                        ->where('user_id',$user_id)
                        ->where('field_name',$field_name)
                        ->where('is_deleted','0')
                        ->update(["field_value" => $field_value]);
        }else{
        $data =  $this::create(["user_id" => $user_id,"field_value" => $field_value,"field_name" => $field_name]);
        }
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user setting
     * @param integer $repeat  
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint developer
     * @return Data
     */ 
    public function userSettingList($user_id){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('user_id',$user_id)
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }

    
}
