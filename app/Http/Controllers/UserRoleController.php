<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use App\Http\Requests\Admin\EditUserRoleAdminRequest;

class UserRoleController extends Controller
{
    /**
     * Determine user role screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function UserRoleManagement(Request $request){
        $title = 'User Role List';
        return view('userrole/userrole', compact('title'));
    }

    /**
     * Determine list of role with datatable.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response //list of user role
     */
    public static function getUserRole(Request $request){
        $userRoleDetailObj  = new \App\UserRole;
        $page = datatables()->of($userRoleDetailObj->userRole())
                ->addColumn('name', function ($page) {
                    return $page->name;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_user_role') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_user_role/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                    })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();  
        return $page;
    }

    /**
     * Determine Add User Role screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function addUserRole(Request $request){
        $title = "Add User Role";
        return view('userrole/adduserrole', compact('title'));
    }
    
    /**
     * Determine method to add user role.
     *
     * @param  \App\Http\Requests\EditUserRoleAdminRequest  $request
     * @return response
     */
    public static function actionAddUserRole(EditUserRoleAdminRequest $request){
        $userRoleDetailObj = new \App\UserRole;
        $input = $request->all();
        $userRoleDetailObj->create($input);
        $request->session()->flash('message', 'User Role has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('user-role-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update user role screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editUserRole(Request $request,$id){
        $title = "Edit User Role";
        $userRoleDetailObj = new \App\UserRole;
        $userRole = $userRoleDetailObj->userRole()->find($id);
        return view('userrole/edituserrole', compact('title','userRole'));
    }

    /**
     * Determine method to update user role.
     *
     * @param  \App\Http\Requests\EditUserRoleAdminRequest  $request
     * @return response
     */
    public static function actionEditUserRole(EditUserRoleAdminRequest $request){
        $userRoleDetailObj = new \App\UserRole;
        $input = $request->all();
        $userRole = $userRoleDetailObj->userRole()->find($input['id']);
        $userRole->update(['name' => $input['name']]);
        $request->session()->flash('message', 'User Role has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('user-role-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete user role.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteUserRole(Request $request, $id){
        $userRoleDetailObj = new \App\UserRole;
        $userRole = $userRoleDetailObj->userRole()->find($id);
        $userRole->update(['is_deleted' => '1']);
        $userDetailObj = new \App\User;
        $userDetailObj->where('user_role_id',$id)->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'User Role has been deleted successfully.');
        return redirect('user_role');
    }
}
