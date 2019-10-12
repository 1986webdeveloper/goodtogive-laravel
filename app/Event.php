<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','calender_id', 'title', 'description', 'start_date', 'end_date', 'is_deleted', 'created_at', 'updated_at'
    ];

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
    public function event_list($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =  Event::where('user_id',$user_id)
                        ->where('is_deleted','0')
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
    public function event_detail($id){
        $data = array();
        if( $id  !=  null )
            $data =  Event::where('id',$id)
                        ->where('is_deleted','0')
                        ->first();
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
    public function event_detail_datewise($church_id,$date){
        $data = array();
        if( $church_id  !=  null )
            $data =  Event::where('user_id',$church_id)
                        ->where('start_date', '>=', $date)
                        ->where('end_date', '<=', $date)
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of upcoming event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public function upcoming_event($church_id,$date){
        $data = array();
        if( $church_id  !=  null )
            $data =  Event::where('user_id',$church_id)
                        ->where('start_date', '>=', $date)
                        ->where('is_deleted','0')
                        ->orderBy('start_date', 'ASC')
                         ->limit(3)
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
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function eventDateList($church_id,$start_date=null,$end_date=null){
        $data = array();
        if( $church_id  !=  null && $start_date !=null && $end_date != null )
            $data =  Event::select('start_date','end_date')->where('user_id',$church_id)
                        ->where('is_deleted','0')
                        ->where(function($q)use ($start_date,$end_date) {
                            $q->where('start_date','>=',$start_date)
                            ->where('start_date','<=',$end_date)
                            ->orWhere('end_date','<=',$end_date)
                            ->where('end_date','>=',$start_date);
                        })                        
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
    public function next_event_detail($church_id,$date=''){
        $data = array();
        if( $date  !=  null ){
            $data =  Event::where('user_id',$church_id)
                        ->where('is_deleted','0')
                        ->where(function($q)use ($date) {
                            $q->where('start_date','LIKE', $date.'%')
                              ->orWhere('end_date', 'LIKE', $date.'%');
                        })
                        ->get();
                        
        }
        else{
            $data =  Event::where('user_id',$church_id)
                            ->where('is_deleted','0')
                            ->get();
        }
        return $data;
    }

}
