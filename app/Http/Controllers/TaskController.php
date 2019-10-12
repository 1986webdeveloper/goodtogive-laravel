<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddTaskAdminRequest;
use App\Http\Requests\Admin\EditTaskAdminRequest;

use DB;

class TaskController extends Controller
{


     /**
     * Determine Task screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function TaskGroupManagement(Request $request){
        $title = 'Task Gruop List';
        return view('task/taskgroup', compact('title'));
    }

 /**
     * Determine task list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getTaskGroup(Request $request){
        $taskDetailObj  = new \App\TaskGroup;
        $page = datatables()->of($taskDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('church_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->church_id);
                    return $user->firstname;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit-group-task') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_group_task/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

  
     /**
     * Determine add task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add task screen
     */
    public static function addGroupTask(Request $request){
        $title = "Add Task";
        $userDetailObj = new \App\User;
        $userList = $userDetailObj->all()->where('is_deleted','0')->where('user_role_id','3');
        $taskPriorityDetailObj = new \App\TaskPriority;
        $taskPriority = $taskPriorityDetailObj->task_priority_list();
        return view('task/addgrouptask', compact('title','userList', 'taskPriority'));
    }

      /**
     * Determine add task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add task screen
     */
    public static function getGroupUser(Request $request){
       $userDetailObj = new \App\User;
       $input = $request->all();
        $churchList = $userDetailObj->groupChurchList($input['churchid']);
        return $churchList;
    }

       /**
     * Determine add task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add task screen
     */
    public static function getGroup(Request $request){
       $taskgroupDetailObj = new \App\TaskGroup;
       $userDetailObj = new \App\User;
       $input = $request->all();
        $churchList = $taskgroupDetailObj->taskgroup_list($input['churchid']);
        $groupmember = $userDetailObj->groupChurchList($input['churchid']);

        $arr= array();
        $arr['arr1'] = $churchList;
        $arr['arr2'] = $groupmember;

        return json_encode($arr);
      
       // return $churchList;
    }

      /**
     * Determine method to add task with validation.
     *
     * @param  \App\Http\Requests\AddTaskAdminRequest  $request
     * @return redirect //add task method
     */
    public static function actionAddGroupTask(Request $request){
         $userDetailObj = new \App\User;
        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
         $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $TaskGroup = $taskgroupDetailObj->create($input);
        $input['group_id'] =  $TaskGroup->id; 
        foreach ($input['user_id'] as $key => $value) {
        $input['user_id'] = $value;
        $taskgroupmemberDetailObj->create($input);
        }
        $group_user_id = explode(",",$input['user_id']);
         foreach ($group_user_id as $key => $value) {

          $userList = $userDetailObj->where('id',$input['church_id'])->where('is_deleted','0')->where('status','active')->first();
          $usersend  = $userDetailObj->user_detail_sendnotification($value,$input['church_id']);
          $church_detail = array();
          $church_detail['group_id'] = $TaskGroup->id;
          $church_detail['church_name'] = ucfirst($userList->firstname);
          $church_detail['email'] = $userList->email;
          $church_detail['user_role_id'] = $usersend->user_role_id;
          $church_detail['mobile'] = $userList->mobile;
          $church_detail['image'] = $userList->image;
          $church_detail['church_id'] =$input['church_id'];
          $UserNotificationObj->send_notification($input['user_id'], $value, "create_group_by_church", $church_detail,$input['church_id'],$usersend->user_role_id);
        }
        $request->session()->flash('message', 'Group has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('task-group-management'));
        return response()->json($arrayMessage);
    }

      /**
     * Determine update task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editGroupTask(Request $request,$id){
        $title = "Edit Group Task";
        $userDetailObj = new \App\User;
        $userList = $userDetailObj->all()->where('is_deleted','0')->where('user_role_id','3');
        $taskDetailObj = new \App\TaskGroup;
        $taskgroupdetail = $taskDetailObj->find($id);
        $taskPriorityDetailObj = new \App\TaskGroupMember;
        $taskgroupdetailmember = $taskPriorityDetailObj->group_member_task_list($taskgroupdetail->id);

        return view('task/editgrouptask', compact('title','taskgroupdetail','taskgroupdetailmember','userList'));
    }


/**
     * Determine method to update Task.
     *
     * @param  \App\Http\Requests\EditTaskAdminRequest  $request
     * @return response
     */
    public static function actionEditGroupTask(Request $request){
         $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $input = $request->all();
    
        $taskgroup = $taskgroupDetailObj->find($input['id']);
        $taskgroup->update($input);
        
        $taskgroupmemberDetailObj->delete_member($input['id']);
        $input['group_id'] =  $input['id']; 
        foreach ($input['user_id'] as $key => $value) {
        $input['user_id'] = $value;
        $taskgroupmemberDetailObj->create($input);
        }

        $request->session()->flash('message', 'Task has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('task-group-management'));
        return response()->json($arrayMessage);
    }
 /**
     * Determine method to delete task.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteTaskGroup(Request $request, $id){
         $taskgroupDetailObj = new \App\TaskGroup;
        $task = $taskgroupDetailObj->find($id);
        $task->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Task has been deleted successfully.');
        return redirect('task');
    }
    /**
     * Determine Task screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function TaskManagement(Request $request){
        $title = 'Task List';
        return view('task/task', compact('title'));
    }

    /**
     * Determine task list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getTask(Request $request){
        $taskDetailObj  = new \App\Task;
        $page = datatables()->of($taskDetailObj->all()->where('is_deleted','0')->where('date','>=',date('Y-m-d H:i:s'))->sortByDesc('id'))
                ->addColumn('user_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->user_id);
                    return $user->firstname." ".$user->lastname;
                })->addColumn('priority', function ($page) {
                    $taskPriorityDetailObj = new \App\TaskPriority;
                    $taskPriority = $taskPriorityDetailObj->find($page->priority_id);
                    return $taskPriority->title;
                })->addColumn('date', function ($page) {
                    return date('d-m-Y', strtotime($page->date));
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_task') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_task/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine add task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add task screen
     */
    public static function addTask(Request $request){
        $title = "Add Task";
        $userDetailObj = new \App\User;
        $userList = $userDetailObj->all()->where('is_deleted','0')->where('user_role_id','3');
        $taskPriorityDetailObj = new \App\TaskPriority;
        $taskPriority = $taskPriorityDetailObj->task_priority_list();
        return view('task/addtask', compact('title','userList', 'taskPriority'));
    }

    /**
     * Determine method to add task with validation.
     *
     * @param  \App\Http\Requests\AddTaskAdminRequest  $request
     * @return redirect //add task method
     */
    public static function actionAddTask(AddTaskAdminRequest $request){
        $taskDetailObj = new \App\Task;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $UserNotificationObj  = new \App\UserNotification;
        $userDetailObj = new \App\User;
        $input = $request->all();
        $group = [];
        $member = [];
        $group_user_id = [];
        foreach ($input['group_list'] as $key => $value) {
            $temp = explode('_', $value);
            if($temp[0] == 'group'){
                $group[] = $temp[1];
            }else{
                $member[] = $temp[1];
            }
        }
        $input['group_id']=implode(",",$group);
        $input['member_id']=implode(",",$member);
        $input['date'] = date('Y-m-d', strtotime($input['date']));
        $task = $taskDetailObj->create($input);
       
         $group_id = $group;
                foreach ($group_id as $key => $values) {
                     $taskgroupmemberDetail = $taskgroupmemberDetailObj->group_member_task_list($values);
                     $valueesds = array();

                     foreach ($taskgroupmemberDetail as $key => $valuees) {
                         $group_user_id[] = $valuees->user_id;
                         $get_task_assign = $UserTaskAssignObj->get_task_assign($valuees->user_id,$task->id);
                        if($get_task_assign == 0){
                            $input['assign_user_id'] = $valuees->user_id;
                            $input['task_id'] = $task->id;
                            $taskassign = $UserTaskAssignObj->create($input);
                        }
                     }
                 
                }
               
                foreach ($member as $key => $value) {
                    $get_task_assign = $UserTaskAssignObj->get_task_assign($value,$task->id);
                    if($get_task_assign == 0){
                        $input['assign_user_id'] = $value;
                        $input['task_id'] = $task->id;
                    $taskassign = $UserTaskAssignObj->create($input);
                    }
                }

             $usersend_notification = array_unique(array_merge($group_user_id,$member));
            //$usersend_notification = array_unique($member);
             
           foreach ($usersend_notification as $key => $value) {
              $userList = $userDetailObj->where('id',$input['user_id'])->where('is_deleted','0')->where('status','active')->first();
               $usersend  = $userDetailObj->user_detail_sendnotification($value,$input['user_id']);
              $church_detail = array();
              $church_detail['task_id'] = $task->id;
              $church_detail['task_title'] = $task->title;
              $church_detail['task_description'] = $task->description;
              $church_detail['church_name'] = ucfirst($userList->firstname);
              $church_detail['email'] = $userList->email;
              $church_detail['user_role_id'] = $usersend->user_role_id;
              $church_detail['mobile'] = $userList->mobile;
              $church_detail['image'] = $userList->image;
              $church_detail['church_id'] = $input['user_id'];
              $UserNotificationObj->send_notification($input['user_id'], $value, "create_task_by_church", $church_detail,$input['user_id'],$usersend->user_role_id);
           }
        $request->session()->flash('message', 'Task has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('task-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editTask(Request $request,$id){
        $title = "Edit Task";
        $taskDetailObj = new \App\Task;
        $task = $taskDetailObj->find($id);
        $userDetailObj = new \App\User;
        $userList = $userDetailObj->all()->where('is_deleted','0')->where('user_role_id','!=','1');
        $taskPriorityDetailObj = new \App\TaskPriority;
        $taskPriority = $taskPriorityDetailObj->task_priority_list();
        $taskDetailObj = new \App\TaskGroup;
        $taskgroupdetailmember = explode(",",$task->group_id);
        $taskgroupdetailchurchmember = explode(",",$task->member_id);
        return view('task/edittask', compact('title','userList','task', 'taskPriority','taskgroupdetailchurchmember','taskgroupdetailmember'));
    }

    /**
     * Determine method to update Task.
     *
     * @param  \App\Http\Requests\EditTaskAdminRequest  $request
     * @return response
     */
    public static function actionEditTask(EditTaskAdminRequest $request){
        $taskDetailObj = new \App\Task;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();

        $task = $taskDetailObj->find($input['id']);
        $input['date'] = date('Y-m-d', strtotime($input['date']));
         $group = [];
        $member = [];
        $group_user_id = [];
        foreach ($input['group_list'] as $key => $value) {
            $temp = explode('_', $value);
            if($temp[0] == 'group'){
                $group[] = $temp[1];
            }else{
                $member[] = $temp[1];
            }
        }
         $input['group_id']=implode(",",$group);
        $input['member_id']=implode(",",$member);
        // $input['group_id']=implode(",",$input['group_list']);
        $task->update($input);

        $taskgroupmemberDetail = $UserTaskAssignObj->delete_member($input['id']);
   
        $group_id = $group;
        foreach ($group_id as $key => $values) {
             $taskgroupmemberDetail = $taskgroupmemberDetailObj->group_member_task_list($values);
             $valueesds = array();

             foreach ($taskgroupmemberDetail as $key => $valuees) {
                 $group_user_id[] = $valuees->user_id;
                 $get_task_assign = $UserTaskAssignObj->get_task_assign($valuees->user_id,$input['id']);
                if($get_task_assign == 0){
                    $input['assign_user_id'] = $valuees->user_id;
                    $input['task_id'] = $input['id'];
                    $taskassign = $UserTaskAssignObj->create($input);
                }
             }
         
        }

        foreach ($member as $key => $value) {
            $get_task_assign = $UserTaskAssignObj->get_task_assign($value,$input['id']);
            if($get_task_assign == 0){
                $input['assign_user_id'] = $value;
                $input['task_id'] = $input['id'];
            $taskassign = $UserTaskAssignObj->create($input);
            }
        }
         $usersend_notification = array_unique(array_merge($group_user_id,$member));
         foreach ($usersend_notification as $key => $value) {
              $userList = $userDetailObj->where('id',$input['user_id'])->where('is_deleted','0')->where('status','active')->first();
                 $usersend  = $userDetailObj->user_detail_sendnotification($value,$input['user_id']);
              $church_detail = array();
              $church_detail['task_id'] = $task->id;
               $church_detail['task_title'] = $task->title;
              $church_detail['task_description'] = $task->description;
              $church_detail['church_name'] = $userList->firstname;
              $church_detail['email'] = $userList->email;
              $church_detail['user_role_id'] = $usersend->user_role_id;
              $church_detail['mobile'] = $userList->mobile;
              $church_detail['image'] = $userList->image;
              $church_detail['church_id'] = $input['church_id'];
              $UserNotificationObj->send_notification($input['user_id'], $value, "update_task_by_church", $church_detail,$input['church_id'],$usersend->user_role_id);
           }
        $request->session()->flash('message', 'Task has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('task-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete task.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteTask(Request $request, $id){
        $taskDetailObj = new \App\Task;
        $task = $taskDetailObj->find($id);
        $task->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Task has been deleted successfully.');
        return redirect('task');
    }
}
