<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskRecurring extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'recurring_date', 'status', 'is_deleted','created_at','updated_at'
    ];
    

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
    public function task_recurring_detail($id){
        $data = array();
        if( $id  !=  null )
            $data =  $this::where('id',$id)
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
     
    public function task_recurring_delete($task_id){
        $data = array();
        if( $task_id  !=  null )
            $data =  $this::where('task_id',$task_id)                        
                        ->delete();
        return $data;
    }
}
