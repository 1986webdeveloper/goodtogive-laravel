<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddReferrarAdminRequest;
use App\Http\Requests\Admin\EditReferrarAdminRequest;

use DB;

class ReferrarController extends Controller
{
    /**
     * Determine Task screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function ReferrarManagement(Request $request){
        $title = 'Referrar List';
        
         $userDetailObj = new \App\User;
         $userList= $userDetailObj->adminChurchList();
        return view('referrar/referrar', compact('title','userList'));
    }

    /**
     * Determine task list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getReferrar(Request $request){
        $referrarDetailObj  = new \App\Referrar;
        
        $page = datatables()->of($referrarDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
               ->addColumn('church_id', function ($page) {
                 $userDetailObj = new \App\User;
                 $userList= $userDetailObj->getSingleuserDetail($page->church_id);
                 if($userList){
                  $church_name = $userList->firstname;
                 }else{
                   $church_name = '';
                 }
                 return $church_name;
               })/*->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/admin/edit_task') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('admin/delete_task/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })*//*->rawColumns(['action'])*/
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
    public static function addReferrar(Request $request){
        $title = "Add Referrar";
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
    public static function actionAddReferrar(AddReferrarAdminRequest $request){
        $referrerDetailObj = new \App\Referrar;
        $input = $request->all();
        $referrer = $referrerDetailObj->referrar_detail($input['church_id']);
        if($referrer){
        $getreferrer = $referrerDetailObj->find($referrer->id);
        $getreferrer->delete();
         }
        
        $referrerDetailObj->create($input);
        $request->session()->flash('message', 'Referrar has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('referrar-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update task screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editReferrar(Request $request,$id){
        $title = "Edit Task";
        $taskDetailObj = new \App\Task;
        $task = $taskDetailObj->find($id);
        $userDetailObj = new \App\User;
        $userList = $userDetailObj->all()->where('is_deleted','0')->where('user_role_id','!=','1');
        $taskPriorityDetailObj = new \App\TaskPriority;
        $taskPriority = $taskPriorityDetailObj->task_priority_list();
        return view('task/edittask', compact('title','userList','task', 'taskPriority'));
    }

    /**
     * Determine method to update Task.
     *
     * @param  \App\Http\Requests\EditTaskAdminRequest  $request
     * @return response
     */
    public static function actionEditReferrar(EditReferrarAdminRequest $request){
        $taskDetailObj = new \App\Task;
        $input = $request->all();
        $task = $taskDetailObj->find($input['id']);
        $input['date'] = date('Y-m-d', strtotime($input['date']));
        
        $task->update($input);
        $request->session()->flash('message', 'Task has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('task-management'));
        return response()->json($arrayMessage);
    }

    public static function GetReferrerId(Request $request){
        $referrarDetailObj  = new \App\Referrar;
          $input = $request->all();
          $userList= $referrarDetailObj->referrar_detail($input['id']);
           return $userList;
          /*if($userList){
            $referrer_Id = $userList->referrer_Id;
          }else{
            $referrer_Id = '';
          }*/
         // return $referrer_Id;
          
    }
    /**
     * Determine method to delete task.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteReferrar(Request $request, $id){
        $taskDetailObj = new \App\Task;
        $task = $taskDetailObj->find($id);
        $task->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Task has been deleted successfully.');
        return redirect('task');
    }
}
