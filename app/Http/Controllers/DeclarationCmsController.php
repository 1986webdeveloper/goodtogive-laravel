<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddDeclarationAdminRequest;
use App\Http\Requests\Admin\EditDeclarationAdminRequest;
use DB;

class DeclarationCmsController extends Controller
{
    /**
     * Determine Declaration CMS Management screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function index(Request $request){
        $title = 'Declaration CMS';
        return view('declaration/cms', compact('title'));
    }

    /**
     * Determine CMS Management list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getCmsList(Request $request){
        $DeclarationCmsDetailObj  = new \App\DeclarationCms;
        $page = datatables()->of($DeclarationCmsDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_declaration_management') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine add CMS screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add CMS screen
     */
    public static function addCms(Request $request){
        $title = "Declaration Add CMS";
        return view('declaration/addcms', compact('title'));
    }

    /**
     * Determine method to add cms management with validation.
     *
     * @param  \App\Http\Requests\AddCmsAdminRequest  $request
     * @return redirect //add project method
     */
    public static function actionAddCms(AddDeclarationAdminRequest $request){
        $cmsManagementDetailObj = new \App\DeclarationCms;
         $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        try{
            DB::beginTransaction();
            // $input['slug'] = str_replace(' ', '-', strtolower(ltrim(rtrim($input['title']))));
           
            $cmsManagement = $cmsManagementDetailObj->delete_prev();
            $cmsManagementDetailObj->create($input);
            DB::commit();
            /* Send Notification*/
            $userList = $userDetailObj->where('is_deleted','0')->where('status','active')->where('user_role_id','2')->get()->all();
            
            foreach ($userList as $key => $value) {
            $church_detail = array();
            $church_detail['church_name'] = $value->firstname;
            $church_detail['email'] = $value->email;
            $church_detail['user_role_id'] = $value->user_role_id;
            $church_detail['mobile'] = $value->mobile;
            $church_detail['image'] = $value->image;
            $church_detail['from_user_id'] = '1';
            $UserNotificationObj->send_notification('1',$value->id, "update_declaration_form", $church_detail,'','');
            }
            /* Send Notification*/
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'Content has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('declaration-cms'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine add CMS screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add CMS screen
     */
    public static function editCms(Request $request,$id){

        $title = "Edit CMS";
        $cmsManagementDetailObj = new \App\DeclarationCms;
        $cmsManagement = $cmsManagementDetailObj->find($id);
        return view('declaration/editcms', compact('title','cmsManagement'));
    }

    /**
     * Determine method to update cms management.
     *
     * @param  \App\Http\Requests\EditCmsAdminRequest  $request
     * @return response 
     */
    public static function actionEditCms(EditDeclarationAdminRequest $request){
        $title = 'Edit CMS';
        $input = $request->all();
        $cmsManagementDetailObj  = new \App\DeclarationCms;
        $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        /*$cmsManagement  = $cmsManagementDetailObj->find($input['id']);
        $cmsManagement->update($input);*/
        $cmsManagement = $cmsManagementDetailObj->delete_prev();
        $cmsManagementDetailObj->create($input);
        /* Send Notification*/
        $userList = $userDetailObj->where('is_deleted','0')->where('status','active')->where('user_role_id','2')->get()->all();
        foreach ($userList as $key => $value) {
            $church_detail = array();
            $church_detail['church_name'] = $value->firstname;
            $church_detail['email'] = $value->email;
            $church_detail['user_role_id'] = $value->user_role_id;
            $church_detail['mobile'] = $value->mobile;
            $church_detail['image'] = $value->image;
            $church_detail['from_user_id'] = '1';
            $UserNotificationObj->send_notification('1',$value->id, "update_declaration_form", $church_detail,'','');
        }
        /* Send Notification*/

        $request->session()->flash('message', 'Content has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('declaration-cms'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete cms.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteCms(Request $request, $id){
        $cmsManagementDetailObj  = new \App\DeclarationCms;
        $cmsManagement = $cmsManagementDetailObj->find($id);
        $cmsManagement->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Content has been deleted successfully.');
        return redirect('cms_management');
    }

    /**
     * Determine method to delete cms.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function declarationForm(Request $request,$id,$church_id){

        $userDetailObj  = new \App\User;
        $UserAddress = new \App\UserAddress;
        $user = $userDetailObj->find($id);
        $user->firstname = ucfirst($user->firstname);
        $user->lastname = ucfirst($user->lastname);
        $church_detail = $userDetailObj->find($church_id);
        $user->church_name = ucfirst($church_detail->firstname);
        $UserAddress = $UserAddress->user_address_detail_user_id($id);
        if($UserAddress){
        $address = ucfirst($UserAddress->billingAddress1);
        $address1 = ucfirst($UserAddress->billingAddress2);
        $address2 = ucfirst($UserAddress->billingState .' , '.$UserAddress->billingCity);
        $user->address = $address;
        $user->address1 = $address1;
        $user->address2 = $address2;
        $user->billingpostcode = $UserAddress->billingPostcode;
        }else{
            $user->address = '';
            $user->address1 = '';
            $user->address2 = '';
            $user->billingpostcode = '';  
        }
        $user->date = date('d-m-Y');
        /*echo "<pre>";
         print_r($UserAddress); exit;*/
        $cmsManagementDetailObj  = new \App\DeclarationCms;
        $cmsManagement = $cmsManagementDetailObj->get_declaration_form();
        
        $cmsManagement->extra_data = $user;
        
        $push_notification_data = collect($cmsManagement->title)
        ->put(
        "extra_data",
        collect($cmsManagement->extra_data)
        ->mapWithKeys(function ($cmsManagement, $key) { 
        return ["##" . strtoupper($key) . "##" => $cmsManagement];
        })
        ->all()
        )
        ->all();

        $search = array_keys($push_notification_data["extra_data"]);
        $replace = array_values($push_notification_data["extra_data"]);

        $cmsManagement->message = str_replace($search, $replace, $cmsManagement->description);
        
        //print_r($cmsManagement); exit;
        $request->session()->flash('message', 'Content has been deleted successfully.');
        return view('declaration/viewcms', compact('title','cmsManagement','user'));
    }

}
