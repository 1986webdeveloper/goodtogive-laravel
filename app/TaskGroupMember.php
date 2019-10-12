<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class TaskGroupMember extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'user_id', 'status', 'is_deleted','created_at', 'updated_at'
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
    public function group_member_task_list($group_id){

        $data = array();
        if( $group_id  !=  null )
            $data =  TaskGroupMember::where('group_id',$group_id)
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

    public function join_group_member_task_list($page,$getdata,$user_id){
                
        $data = array();
        if( $user_id  !=  null )
            $data =  \DB::table('task_group_members as tgm')
                    ->select('tg.id','tg.church_id','tg.creater_id','tg.group_title','tg.status','tg.is_deleted','tg.created_at','tg.updated_at','tg.group_privacy_status')
                    ->join('task_groups AS tg', 'tg.id', '=', 'tgm.group_id')
                    ->where('tgm.user_id',$user_id)
                    ->where('tgm.is_deleted','0');
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

    public function join_user_group_member_task_list($page,$getdata,$user_id,$church_id){
                
        $data = array();
        if( $user_id  !=  null )
            $data =  \DB::table('task_group_members as tgm')
                    ->select('tg.id','tg.church_id','tg.creater_id','tg.group_title','tg.status','tg.is_deleted','tg.created_at','tg.updated_at','tg.group_privacy_status')
                    ->join('task_groups AS tg', 'tg.id', '=', 'tgm.group_id')
                    ->where('tg.church_id',$church_id)
                    ->where('tgm.user_id',$user_id)
                    ->where('tgm.is_deleted','0');
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

 public function join_group_member_task_list_all($page,$user_id,$task_group_select){
                
        $data = array();
        $return = array();
        if( $user_id  !=  null )
            $data =  \DB::table('task_group_members as tgm')
                    ->select('tg.id','tg.church_id','tg.creater_id','tg.group_title','tg.status','tg.is_deleted','tg.created_at','tg.updated_at')
                    ->join('task_groups AS tg', 'tg.id', '=', 'tgm.group_id')
                    ->where('tgm.user_id',$user_id)
                    ->where('tgm.is_deleted','0')
                    ->orwhere('tg.creater_id',$user_id);
                    
                    if($task_group_select){
                    $ids = [$task_group_select];
                    $data = $data->orderBy(DB::raw('FIELD(`tg`.`id`, '.implode(',', $ids).')'),'DESC');
                    }

                    $data = $data->get()->unique();
                   // ->skip($page)->take(11) 
                    
              foreach ($data as $key => $value) {
                       $return[] = $value;
                   }     
                   
             
        return $return;
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
     
    public function delete_member($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   \DB::table('task_group_members')
                        ->where('group_id',$user_id)                        
                        ->delete();
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
            $data =  Task::where('id',$id)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
}
