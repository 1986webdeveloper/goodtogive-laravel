<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class TaskGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id','creater_id', 'group_title', 'status', 'is_deleted','group_privacy_status','created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * Showing relationship between task and user
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function task(){
        return $this->belongsTo('App\User','id','user_id');
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
  
    public function join_taskgroup_list($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('task_groups')
                        ->where('id',$user_id)  
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

    public function taskgroup_list_pagination($page,$getdata,$user_id,$group_id = null){

        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('task_groups')
                        ->where('church_id',$user_id)
                        ->where('is_deleted','0');
                    if($group_id){
                    $ids = [$group_id];
                    $data = $data->orderBy(DB::raw('FIELD(`id`, '.implode(',', $ids).')'),'DESC');
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
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

     public function taskgroup_list_pagination_count($page,$user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('task_groups')
                        ->where('church_id',$user_id)  
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

    public function taskgroup_list($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('task_groups')
                        ->where('church_id',$user_id)  
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

    public function pastor_taskgroup_list($page,$user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('task_groups')
                        ->where('creater_id',$user_id) 
                        ->where('is_deleted','0')  
                        ->skip($page)->take(11)                     
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
     
    public function taskListUpcoming($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('tasks')->where('date','>=',date('Y-m-d 00:00:00'))
                        ->where('user_id',$user_id)               
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
    public function task_detail($id){
        $data = array();
        if( $id  !=  null )
            $data =  TaskGroup::where('id',$id)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
}
