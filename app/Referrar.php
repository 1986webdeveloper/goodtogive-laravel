<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referrar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id', 'referrer_Id','vendor_id','is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * Showing relationship between task and user
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function referrar(){
        return $this->belongsTo('App\User','id','church_id');
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
    public function referrar_list($church_id){
        $data = array();
        if( $church_id  !=  null )
            $data =  Task::where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->get();
                        dd($data);
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of refferal 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function referrarListUpcoming($church_id){
        $data = array();
        if( $church_id  !=  null )
            $data =   \DB::table('church_referrels')->where('date','>=',date('Y-m-d 00:00:00'))
                        ->where('is_deleted','0')
                        ->where('church_id',$church_id)                        
                        ->get();
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
    public function referrar_detail($church_id){
        $data = array();
        if( $church_id  !=  null )
            $data =  Referrar::where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->first();
        /*if(empty($data)){
            $data =(object) array(
                'id' => '',
                'church_id' => $church_id,
                'referrer_Id' => '',
                'vendor_id' => '',
                'created_at' => '',
                'updated_at' => '',
                'is_deleted' => '',
            );
          
        }  */         
        return $data;
    }
}
