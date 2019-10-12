<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Datatables;
use App\Http\Controllers\GeneralController AS General;

class ScriptureController extends Controller
{
    /**
     * Determine scripture screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function ScriptureManagement(Request $request){
        $title = 'Scripture List';
        return view('scripture/scripture', compact('title'));
    }

    /**
     * Determine list of scripture to church with datatable.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response //list of church with scripture
     */
    public static function getScripture(Request $request){
        $userDetailObj  = new \App\User;
        $user = $userDetailObj->adminChurchList();
        $page = datatables()->of($user->sortByDesc('id'))
                ->addColumn('church_name', function ($page) {
                    return $page->firstname." ".$page->lastname;
                })->addColumn('scripture', function ($page) {
                    return $page->scripture;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_scripture') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a>';
                    })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();  
        return $page;
    }

    /**
     * Determine update scripture screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editScripture(Request $request,$id){
        $title = "Edit Scripture";
        $userDetailObj  = new \App\User;
        $user = $userDetailObj->find($id);
        return view('scripture/editscripture', compact('title','user'));
    }

    /**
     * Determine method to update scripture.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function actionEditScripture(Request $request){
        $userDetailObj = new \App\User;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
         $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $user = $userDetailObj->find($input['id']);
        $user->update(['scripture'=>$input['scripture']]);

         /* notification send for pastor */
         $pastore_detail  = $userDetailObj->userDetails_scripture($input['id']);
         $church_details = $userDetailObj->find($input['id']);
         foreach ($pastore_detail as $key => $value) {

               $users = $userDetailObj->userDetail($value->id);
               if($users){
                /* pastor send notification user */
                $user_detail = array();
                $user_detail['user_id'] = $users->id;
                $user_detail['email'] = $users->email;
                $user_detail['user_role_id'] = $users->user_role_id;
                $user_detail['mobile'] = $users->mobile;
                $user_detail['image'] = $users->image;
                $user_detail['from_user_id'] = $input['id'];
                $user_detail['user_name'] = $church_details->firstname;
                $user_detail['church_id'] = $input['id'];
                $user_detail['church_name'] = $church_details->firstname;
                $user_detail['referral_id'] = $church_details->referral_id;
                $UserNotificationObj->send_notification($input['id'],$users->id, "update_scripture", $user_detail,'','');
                }
                 /* pastor send notification user */

         }
        /* notification send for pastor */

        $pastore_detail  = $UserMultiChurchObj->update_scripture_notification($input['id']);

        foreach ($pastore_detail as $key => $values) {
             $users = $userDetailObj->userDetail($values->user_id);
             if($users){
                $user_detail = array();
                $user_detail['user_id'] = $users->id;
                $user_detail['email'] = $users->email;
                $user_detail['user_role_id'] = $users->user_role_id;
                $user_detail['mobile'] = $users->mobile;
                $user_detail['image'] = $users->image;
                $user_detail['from_user_id'] = $input['id'];
                $user_detail['user_name'] = $church_details->firstname;
                $user_detail['church_id'] = $input['id'];
                $user_detail['church_name'] = $church_details->firstname;
                $user_detail['referral_id'] = $church_details->referral_id;
                $UserNotificationObj->send_notification($input['id'],$users->id, "update_scripture", $user_detail,'','');
                }
         }

        $request->session()->flash('message', 'Scripture has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('scripture-management'));
        return response()->json($arrayMessage);
    }
}
