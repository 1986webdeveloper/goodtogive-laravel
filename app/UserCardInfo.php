<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCardInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'field_name','card_holder','field_value','card_number','cvv','status', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user card info from database and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function userCard(){
        $data = array();
        
        $data =  self::where('is_deleted','0')
                        ->where('status','active')
                        ->groupBy('user_id')
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

 public function checkcard_detail_with_card($user_id,$card_number){
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->where('card_number',$card_number)
                ->where('is_deleted','0')
                ->where('status','active')
                ->get()->first();

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

     public function get_card_cvv($user_id,$cardReference){
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->where('field_value',$cardReference)
                ->where('is_deleted','0')
                ->where('status','active')
                ->get()->first();

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

 public function checkcard_detail($user_id){
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->where('is_deleted','0')
                ->where('status','active')
                ->get()->first();

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

    public function userWiseCard($user_id){
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->where('is_deleted','0')
                ->where('status','active')
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

    public function userCardDelete($user_id){
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->where('is_deleted','0')
                ->where('status','active')
                ->update(['is_deleted' => '1']);
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
     
    public function countcarddetail($user_id){
     $data = array();
         $data =  self::where('user_id',$user_id)
                    ->where('is_deleted','0')->count();
     return $data;             
    }
}
