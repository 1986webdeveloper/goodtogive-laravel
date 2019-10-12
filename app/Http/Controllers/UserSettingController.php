<?php
/**
 * An UserSettingController Class 
 *
 * The controller class is using to controll all user setting funcationality.
 *
 * @package    Good To Give
 * @subpackage Common
 * @author     Acquaint Soft Developer 
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\EditUserSettingAdminRequest;
use DB;

class UserSettingController extends Controller
{
    /**
     * Determine user setting screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function UserSettingManagement(Request $request){
        $title = 'User Setting List';
        return view('usersetting/usersetting', compact('title'));
    }

    /**
     * Determine list of user setting with datatable.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response //list of user setting
     */
    public static function getUserSetting(Request $request){
        $userSettingDetailObj  = new \App\UserSetting;
        $page = datatables()->of($userSettingDetailObj->all()->where('is_deleted','0'))
                ->addColumn('name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->find($page->user_id);
                    return $user->firstname." ".$user->lastname;
                })->addColumn('field_name', function ($page) {
                        return $page->field_name;
                })->addColumn('field_value', function ($page) {
                        if($page->field_value == '1'){
                            return $page->field_value = 'Immediate';
                        }elseif($page->field_value == '2'){
                            return $page->field_value = 'Weekly';
                        }else{
                            return $page->field_value;
                        }
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_user_setting') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a>';
                    })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();  
        return $page;
    }

    /**
     * Determine update user setting screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editUserSetting(Request $request,$id){
        $title = "Edit User Setting";
        $userSettingDetailObj = new \App\UserSetting;
        $userSetting = $userSettingDetailObj->find($id);
        $userDetailObj  = new \App\User;
        $user = $userDetailObj->find($userSetting->user_id);
        $notificationOptionDetailObj  = new \App\NotificationOption;
        $notificationOptions = $notificationOptionDetailObj->all();
        return view('usersetting/editusersetting', compact('title','userSetting','user','notificationOptions'));
    }

    /**
     * Determine method to edit user setting with validation.
     *
     * @param  \App\Http\Requests\EditUserSettingAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionEditUserSetting(EditUserSettingAdminRequest $request){
        $input = $request->all();
        try{
            DB::beginTransaction();
            $userSettingDetailObj = new \App\UserSetting;
            $userSetting = $userSettingDetailObj->find($input['id']);
            $userSetting->update(['field_value' => $input['field_value']]);
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'User setting has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('user-setting-management'));
        return response()->json($arrayMessage);
    }
}
