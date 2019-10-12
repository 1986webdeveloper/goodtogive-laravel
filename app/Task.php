<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','user_creater_id','group_id','member_id', 'priority_id', 'title', 'description', 'date', 'is_deleted','start_date','is_admin_user','end_date','is_recurring','is_private','recurring', 'created_at', 'updated_at'
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
    * Showing relationship between task and user
    * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
    * @return Data
    */
    public function user_task_detail(){
        return $this->hasMany('App\TaskRecurring','task_id','id')->select('id','id as recurring_id','task_id','recurring_date','is_status','is_deleted','created_at','updated_at')->whereDate('recurring_date', '>=', date('Y-m-d'));
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
    public function task_list($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =  Task::with(['user_task_detail'])
                        ->where('user_id',$user_id)
                        ->orwhere('user_creater_id',$user_id)
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }
    
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of task list 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

     public function get_task_detail_list($user_id,$task_id){

        $taskgroupDetailObj = new \App\TaskGroup;
            $data =  \DB::table('task_group_members')
                ->where('user_id',$user_id)   
                ->where('is_deleted','0')                     
                ->get();

        $group_id = [];
        foreach ($data as $key => $value) {
            $taskgroup = $taskgroupDetailObj->task_detail($value->group_id);
            if($taskgroup){
                $group_id[] = $taskgroup->id;
            }
        }
        
        // $group_id = implode(",",$group_id);
        $task_list = [];

        foreach($group_id as $group_id_value){
            $task_list_res =  Task::whereRaw("find_in_set($group_id_value,group_id)")
            ->where('is_deleted','0')
            ->get();
            foreach ($task_list_res as $task_list_res_key => $task_list_res_value) {
                $task_list[] = $task_list_res_value;
                }
        }

        $task_list_details_member =  Task::orwhereRaw("find_in_set($user_id,member_id)")
            ->where('is_deleted','0')
            ->get()->all();
            
        foreach ($task_list_details_member as $task_list_details_member_key => $task_list_details_member_value){
            $task_list[] = $task_list_details_member_value;
            # code...
        }
        $task_list = array_values(array_map("unserialize", array_unique(array_map("serialize", $task_list))));
         $task_list = array_values(array_filter($task_list, function ($value) use($task_id) {
        return ($value->id == $task_id);
        }));
        // print_r($task_list); exit;
        return $task_list;
       
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of task list dashboard
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function get_task_list_dashboard($page,$user_id){

        $taskgroupDetailObj = new \App\TaskGroup;
            $data =  \DB::table('task_group_members')
                ->where('user_id',$user_id)   
                ->where('is_deleted','0')
                ->orderBy('id', 'DESC')
                ->get();

        $group_id = [];
        foreach ($data as $key => $value) {
            $taskgroup = $taskgroupDetailObj->task_detail($value->group_id);
            if($taskgroup){
                $group_id[] = $taskgroup->id;
            }
        }
        // $group_id = implode(",",$group_id);
        $task_list = [];

        foreach($group_id as $group_id_value){
            $task_list_res =  Task::where(function ($query) use($user_id,$group_id_value) {
            $query->whereRaw("find_in_set($group_id_value,group_id)")
            ->orWhere('user_creater_id', '=', $user_id);
            })
            ->where('is_deleted','0')
            ->get();
            foreach ($task_list_res as $task_list_res_key => $task_list_res_value) {
                $task_list[] = $task_list_res_value;
                }
        }

        $task_list_details_member =  Task::where(function ($query) use($user_id) {
            $query->whereRaw("find_in_set($user_id,member_id)")
            ->orWhere('user_creater_id', '=', $user_id);
            })->where('is_deleted','0')
            ->get()->all();
        foreach ($task_list_details_member as $task_list_details_member_key => $task_list_details_member_value){
            $task_list[] = $task_list_details_member_value;
            # code...
        }
        $task_list = array_values(array_map("unserialize", array_unique(array_map("serialize", $task_list))));
       
        return $task_list;
       
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of task list
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function get_task_list($page,$user_id){

        $taskgroupDetailObj = new \App\TaskGroup;
            $data =  \DB::table('task_group_members')
                ->where('user_id',$user_id)   
                ->where('is_deleted','0')
                //->skip($page)->take(11)                     
                ->get();
        $group_id = [];
        foreach ($data as $key => $value) {
            $taskgroup = $taskgroupDetailObj->task_detail($value->group_id);
            if($taskgroup){
                $group_id[] = $taskgroup->id;
            }
        }
        // $group_id = implode(",",$group_id);
        $task_list = [];

        foreach($group_id as $group_id_value){
            $task_list_res =  Task::where(function ($query) use($user_id,$group_id_value) {
            $query->whereRaw("find_in_set($group_id_value,group_id)");
            //->orWhere('user_creater_id', '=', $user_id);
            })
            ->where('is_deleted','0')
            ->get();
            foreach ($task_list_res as $task_list_res_key => $task_list_res_value) {
                $task_list[] = $task_list_res_value;
                }
        }

        $task_list_details_member =  Task::where(function ($query) use($user_id) {
            $query->whereRaw("find_in_set($user_id,member_id)");
            //->orWhere('user_creater_id', '=', $user_id);
            })->where('is_deleted','0')
            ->get()->all();
        foreach ($task_list_details_member as $task_list_details_member_key => $task_list_details_member_value){
            $task_list[] = $task_list_details_member_value;
            # code...
        }
        $task_list = array_values(array_map("unserialize", array_unique(array_map("serialize", $task_list))));
       
        return $task_list;
       
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of task list upcoming
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function taskListUpcoming($user_id){
        $data = array();

        if( $user_id  !=  null )
            $data =   Task::with(['user_task_detail'])
                        ->where('date','>=',date('Y-m-d 00:00:00'))
                        ->where(function ($query) use($user_id) {
                        $query->where('user_id', '=', $user_id)
                        ->orWhere('user_creater_id', '=', $user_id);
                        })
                        ->where('is_deleted','0')           
                        ->where('is_private','N')           
                        ->get();
            
        return $data;
    }
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of task list 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
     public function taskListDisplay($user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   Task::with(['user_task_detail'])
                       ->where('date','>=',date('Y-m-d 00:00:00'))
                        ->where(function ($query) use($user_id) {
                        $query->where('user_id', '=', $user_id)
                        ->orWhere('user_creater_id', '=', $user_id);
                        })
                        ->where('is_deleted','0')
                        ->orderBy('id', 'ASC')
                        ->get();

        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of task list 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
     public function taskListDisplaypastor($task_id,$user_id){
        $data = array();
        if( $user_id  !=  null )
            $data =   Task::with(['user_task_detail'])
                       ->where('id',$task_id)
                        ->where(function ($query) use($user_id) {
                        $query->where('user_id', '=', $user_id)
                        ->orWhere('user_creater_id', '=', $user_id);
                        })
                        ->where('is_deleted','0')
                        ->orderBy('id', 'ASC')
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
    public function task_detail($id){
        $data = array();
        if( $id  !=  null )
            $data =  Task::where('id',$id)
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
    public function task_list_all(){
        $data = array();
        
            $data =  Task::where('is_deleted','0')
                         ->whereDate('end_date','>=',date('Y-m-d'))
                         ->where('is_recurring','N')
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
    public function task_list_all_recurring(){
        $data = array();
        
            $data =  Task::where('is_deleted','0')
                        ->where('recurring','!=','0')
                        ->whereDate('end_date','>=',date('Y-m-d'))
                        ->where('is_recurring','Y')
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

    public function task_assign_create_recurring($value){
           $taskDetailObj = new \App\Task;
            $taskRecurringDetails  = new \App\TaskRecurring;
            $UserTaskAssignObj  = new \App\UserTaskAssign;
            $taskgroupmemberDetailObj = new \App\TaskGroupMember;
            $UserNotificationObj  = new \App\UserNotification;
            $userDetailObj = new \App\User;

                $input = array();
                $inputs = array();
                $inputsd = array();
                $input['date'] = date('Y-m-d H:i:s');
                $task = $taskDetailObj->task_detail($value->id);
                $task->update($input);

                $input['task_id'] = $value->id;
                $input['recurring_date'] = date('Y-m-d H:i:s');
                $taskrecurringto = $taskRecurringDetails->create($input);

                $group = [];
                $group_id = explode(",",$value->group_id);

                foreach ($group_id as $key => $values) {

                    $taskgroupmemberDetail = $taskgroupmemberDetailObj->group_member_task_list($values);

                    $valueesds = array();
                        foreach ($taskgroupmemberDetail as $key => $valuees) {
                            $group[] = $valuees->user_id;
                            $get_task_assign = $UserTaskAssignObj->get_task_assign_withkey($valuees->user_id,$task->id,$taskrecurringto->id);
                        if($get_task_assign == 0){
                            $inputs['recurring_id'] = $taskrecurringto->id;
                            $inputs['assign_user_id'] = $valuees->user_id;
                            $inputs['task_id'] = $task->id;
                            $taskassign = $UserTaskAssignObj->create($inputs);
                        }
                    }

                }

                $member = explode(",",$value->member_id);

                foreach ($member as $key => $valued) {
                    $get_task_assign = $UserTaskAssignObj->get_task_assign_withkey($value,$task->id,$taskrecurringto->id);
                    if($get_task_assign == 0){
                        $inputsd['recurring_id'] = $taskrecurringto->id;
                        $inputsd['assign_user_id'] = $valued;
                        $inputsd['task_id'] = $task->id;
                    $taskassign = $UserTaskAssignObj->create($inputsd);
                    }
                }

                $usersend_notification = array_unique(array_merge($group,$member));
                if($usersend_notification[0]){

                //$usersend_notification = array_unique($member);
                foreach ($usersend_notification as $key => $value) {
                    $userList = $userDetailObj->where('id',$value)->where('is_deleted','0')->where('status','active')->first();
                    $usersend = $userDetailObj->user_detail_sendnotification($value,$task->user_id);
                    if($usersend){
                        $church_detail = array();
                        $church_detail['task_id'] = $task->id;
                        $church_detail['recurring_id'] = $taskrecurringto->id;
                        $church_detail['task_title'] = $task->title;
                        $church_detail['task_description'] = $task->description;
                        $church_detail['church_name'] = ucfirst($userList->firstname);
                        $church_detail['email'] = $userList->email;
                        $church_detail['user_role_id'] = $usersend->user_role_id;
                        $church_detail['mobile'] = $userList->mobile;
                        $church_detail['image'] = $userList->image;
                        $church_detail['from_user_id'] = $value;
                        $church_detail['church_id'] = $task->user_id;
                        $UserNotificationObj->send_notification($task->user_id, $value, "create_task_by_church", $church_detail,$task->user_id,$usersend->user_role_id);
                    }
                }
                }
                return 'success';

    }
}
