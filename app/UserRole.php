<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'status', 'password', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];

    public function user_roles()
    {
        return $this->hasMany('App\User');
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user role
     * @param integer $repeat  
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint developer
     * @return Data
     */ 
    public function userRole(){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('id','!=','1')
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

    public function withoutChurch(){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('id','!=','1')
                        ->where('id','!=','3')
                        ->where('id','!=','4')
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
     
    public function withoutChurchudonor(){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('id','!=','1')
                        ->where('id','!=','3')
                        ->where('id','!=','2')
                        ->where('id','!=','5')
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }
}
