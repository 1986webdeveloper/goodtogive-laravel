<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'billingAddress1', 'billingAddress2','billingState', 'billingCity', 'billingPostcode','billingCountry', 'created_at', 'updated_at'
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

 public function get_update_address($user_id){
    
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->get()->first();
        if(empty($data)){
        $useraddressdetails = array();
        $useraddressdetails['billingAddress1'] = '';
        $useraddressdetails['billingAddress2'] = '';
        $useraddressdetails['billingState'] = '';
        $useraddressdetails['billingCity'] = ''; 
        $useraddressdetails['billingPostcode'] = '';

        }else{
        $useraddressdetails = array();
        $useraddressdetails['billingAddress1'] = $data->billingAddress1;
        $useraddressdetails['billingAddress2'] = $data->billingAddress2;
        $useraddressdetails['billingState'] = $data->billingState;
        $useraddressdetails['billingCity'] = $data->billingCity; 
        $useraddressdetails['billingPostcode'] = $data->billingPostcode;
        }
        return $useraddressdetails;
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
     
  public function update_address($user_id){
        $data = array();
        $data =  self::where('user_id',$user_id)
                ->get()->first();
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
    public function user_address_detail($id){
    $data = array();
        $data =  self::where('id',$id)
                ->get()->first();
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
    public function user_address_detail_user_id($id){
    $data = array();
        $data =  self::where('user_id',$id)
                ->get()->first();
        return $data;
        
    }
  
}
