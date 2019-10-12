<?php
/**
 * An UserController Class 
 *
 * The controller class is using to controll all user funcationality.
 *
 * @package    Good To Give
 * @subpackage Common
 * @author     Acquaint Soft Developer 
 */
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoginAdminRequest;
use App\Http\Requests\Admin\ChangePasswordAdminRequest;
use App\Http\Requests\Admin\EditProfileAdminRequest;
use App\Http\Requests\Admin\AddUserAdminRequest;
use App\Http\Requests\Admin\AddChurchAdminRequest;
use App\User;
use Datatables;
use Mail;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use DB;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class UserController extends Controller
{
    /**
     * Determine main dashboard.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function Index(Request $request){       

        if ($request->session()->has('ADMIN_SESSION')) {
             return redirect('/dashboard');
        } else {
            $title = 'Login page';
            return view('login', compact('title'));
        }
    }

    /**
     * Check login exists or not.
     *
     * @param  \App\Http\Requests\LoginAdminRequest  $request
     * @return redirect //return list of church fund
     */
    public static function checkLogin(LoginAdminRequest $request){
        $input = $request->all();
        $email = $request->input('email');
        $password = $request->input('password');
        if ($request->session()->has('ADMIN_SESSION')) {
            return redirect('/dashboard');
        }
        if($email == "" || $password == ""){
            $message = 'Please enter email address or password.';        
            $request->session()->put('login_message', $message);
            return redirect('login');
        }

        $userDetailObj  = new \App\User;
        $user = $userDetailObj->user_login_check($input['email'],1);
        if($user){
            if(Hash::check($input['password'] , $user->password))
            {
                $request->session()->put('login_detail', $user);
                $request->session()->put('ADMIN_SESSION', $user->id);
                $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('dashboard'));
                return response()->json($arrayMessage);
            }
            else{
                $request->session()->flash('message', 'Password does not match.');
                $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('login'));
                // return response()->json($arrayMessage);
                return redirect('dashboard');
            }
        }
        else{
            $message = 'Please enter email address or password.';        
            $request->session()->put('login_message', $message);
            return redirect('login');
        }
    }

    /**
     * Determine user management.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public function UserManagement(Request $request) {
        $title = 'User List';
        return view('user/user', compact('title'));
    }

    /**
     * Determine user management.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public function PastorManagement(Request $request) {
        $title = 'User List';
        return view('pastor/user', compact('title'));
    }

    /**
     * Determine forget password.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return  \\return ajax response
     */
    public static function forgetPassword(Request $request){
        $input = $request->all();
        $userDetailObj  = new \App\User;
        $user = $userDetailObj->user_login_check($input['email'],1);     
        return $user;
        if(!empty($user)){            
            $new_password = 'gtg_'.mt_rand(100000, 999999);
            $user->update(['password' => bcrypt($new_password)]);
            $text = 'Hello '.$user->firstname.' '.$user->lastname.',';
            $text .= '<br><br> Your login password is : '.$new_password;
            $text .= '<p>We advise that you change your password immediately for security purposes.</p>';
            
            Mail::send([],[], function($message) use ($user,$text)
            {
                $message->subject('Good to Give Password Reset Request');
                $message->from('hello@gtg.com', 'Good To Give');                
                $message->to($user->email);
                $message->setBody($text, 'text/html');
                // echo "Thanks! Your request has been sent to ".$user->email;
            });
            return 1;
        }
        else{
            return 0;
        }
    }

     /**
     * Determine user list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function getPastor(Request $request){
            $userDetailObj  = new \App\User;
            $user = $userDetailObj->withoutChurchList();
            $user = array_values(array_filter($user,function($value){
            return($value['user_role_id'] == '4');
            }));
         
            $page = datatables()->of($user)
                    ->editColumn('user_photo', function ($page) {
                        $image_url = General::get_file_src($page['image']);
                        return '<img src="' . $image_url . '" alt="" height="50" width="50" />';
                    })
                    ->editColumn('church_name', function ($page) use($userDetailObj) {
                        $church_name = $userDetailObj->getchutchname($page['church_id']);
                        if($church_name){
                        $church_name =  $church_name->firstname;   
                        }else{
                        $church_name =  'N/A';
                        }
                        return $church_name;
                    })->addColumn('user_role', function ($page) {
                        if($page['user_role_id'] == 2)
                            return "donor";
                        else if($page['user_role_id'] == 5)
                            return "visitor";
                        else
                            return "pastor";
                    })->addColumn('user_name', function ($page) {
                        return $page['firstname'].' '.$page['lastname'];
                    })->addColumn('is_primary', function ($page) {
                      if($page['is_primary'] == 'Y'){
                        $primary = 'Yes';
                      }else{
                        $primary = 'No';
                      }
                      return $primary;
                      
                    })->addColumn('email', function ($page) {
                        return $page['email'];
                    })->addColumn('mobile', function ($page) {
                        return $page['mobile'];
                    })->editColumn('status', function ($page) {
                        return '
                            <input type="checkbox" name="status[]" data-id="' . $page['id'] . '" data-toggle="toggle" data-on="<b>Active</b>" data-off="<b>Inactive</b>" data-size="normal" data-onstyle="success" data-offstyle="danger" ' . (($page['status'] == 'active') ? "checked=checked" : "") . ' class="status_toggle_class">';
                    })->addColumn('action', function ($page) {
                        return '<a style="margin-right:10px" href="' . URL::to('/edit_pastor') . '/' . $page['id'] .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_pastor/'.$page['id'] ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                        })->rawColumns(['action','status','user_photo'])
                        ->addIndexColumn()
                        ->toJson();   
                        return $page;
        }

    /**
     * Determine Edit user screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public function editPastor(Request $request,$id) {         
        $title = 'Edit Pastor';
        $input = $request->all();
        $userDetailObj  = new \App\User;
        $UserAddress = new \App\UserAddress;
        $user= $userDetailObj->find($id);
        $user->image = General::get_file_src($user->image);
        $userRoleDetailObj  = new \App\UserRole;
        $user_roles= $userRoleDetailObj->withoutChurchudonor();
        $userOptionDetailObj = new \App\NotificationOption;
        $churchList = $userDetailObj->adminChurchList();
        $userOption = $userOptionDetailObj->all()->where('is_deleted','0');
        $userSettingDetailObj = new \App\UserSetting;
       
        $useraddressdetails = $UserAddress->get_update_address($id);
      

      

        return view('pastor/edituser', compact('title','user_roles', 'notification', 'vibration', 'coloured_blur','cvv_card', 'userOption', 'user', 'churchList', 'userSetting','useraddressdetails'));
    }

    /**
     * Determine user list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function getUser(Request $request){
            $userDetailObj  = new \App\User;
            $UserMultiChurchObj  = new \App\UserMultiChurch;
            $users = $userDetailObj->withoutChurchList();
            $user = [];
            foreach ($users as $key => $value) {
                $count_request = $UserMultiChurchObj->count_active_church($value['id']);
                $values['count_data'] = $count_request;
                $user[] = array_merge($value,$values);
            }

            $user = array_values(array_filter($user,function($value){

            return($value['count_data'] > 0 AND $value['user_role_id'] != '4');

            }));
        
            $page = datatables()->of($user)
                    ->editColumn('user_photo', function ($page) {
                        $image_url = General::get_file_src($page['image']);
                        return '<img src="' . $image_url . '" alt="" height="50" width="50" />';
                    })
                    ->editColumn('church_name', function ($page) use($userDetailObj) {
                        $church_names = $userDetailObj->adminChurchListUserSelect($page['id']);
                        
                        if(count($church_names) > 0){
                        $church_name = '<div class="row" id="countdata">
                        <div class="col-md-6">
                        <div class="form-group">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#inlineForm'.$page['id'].'">
                        <i class="ft-eye"></i>
                        </button>
                        </div>
                        </div>
                        </div><div class="modal fade text-left" id="inlineForm'.$page['id'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                        <label class="modal-title text-text-bold-600" id="myModalLabel33">Church List</label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div><form action="#">
                        <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12 modal-header" >
                         <div class="col-md-4" >
                         <label for="userrole2" style="float:left"><b>Church Name</b> </label>
                         </div>
                         <div class="col-md-4" >
                         <label for="userrole2" style="float:left"><b>User Role</b></label>
                         </div>
                          <div class="col-md-4" >
                         <label for="userrole2" style="float:left"><b>User Status</b></label>
                         </div>
                        </div>
                        ';
                         
                        foreach ($church_names as $value) {
                          if($value->user_role_id == '2'){
                             $value->user_role_id = 'Member';
                          }else{
                             $value->user_role_id = 'Visitor';
                          }
                          if($value->new_request == 'Y'){
                             $value->new_request = 'Approved';
                          }else if($value->new_request == 'N'){
                             $value->new_request = 'Not Yet Approved';
                          }else{
                             $value->new_request = 'N/A'; 
                          }
                        $church_name .= '
                        <div class="col-md-12 modal-header" >
                        <div class="col-md-4" >
                        <div class="form-group">
                        '.$value->firstname.'
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group">
                        '.$value->user_role_id.'
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-group">
                        '.$value->new_request.'
                        </div>
                        </div>
                        </div>';
                        
                        }
                        $church_name .= '</div></div>
                        </form></div></div></div>';
                       // $church_name =  $church_name->firstname;   
                        }else{
                        $church_name =  '<div class="row" id="countdata">
                        <div class="col-md-6">
                        <div class="form-group">
                       N/A
                        </div>
                        </div>
                        </div>';
                        }
                        return $church_name;
                    })->addColumn('user_role', function ($page) {
                        if($page['user_role_id'] == 2)
                            return "Member";
                        else if($page['user_role_id'] == 5)
                            return "visitor";
                        else
                            return "pastor";
                    })->addColumn('user_name', function ($page) {
                        return $page['firstname'].' '.$page['lastname'];
                    })->addColumn('email', function ($page) {
                        return $page['email'];
                    })->addColumn('mobile', function ($page) {
                        return $page['mobile'];
                    })->editColumn('status', function ($page) {
                        return '
                            <input type="checkbox" name="status[]" data-id="' . $page['id'] . '" data-toggle="toggle" data-on="<b>Active</b>" data-off="<b>Inactive</b>" data-size="normal" data-onstyle="success" data-offstyle="danger" ' . (($page['status'] == 'active') ? "checked=checked" : "") . ' class="status_toggle_class">';
                    })->addColumn('action', function ($page) {
                        return '<a style="margin-right:10px" href="' . URL::to('/edit_user') . '/' . $page['id'] .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_user/'.$page['id'] ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                        })
                    ->addColumn('qrcode', function ($page) {
                    return '<a href="'. URL('user_view_qr_code/'.$page['id']) .'" class="btn btn-success" ><i class="la la-cloud-download"></i></a>';
                    })->rawColumns(['action','status','qrcode','user_photo','church_name'])
                        ->addIndexColumn()
                        ->toJson();   
                        return $page;
        }
    
     /**
     * Determine add pastor screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public function addPastor(Request $request) {         
        $title = 'Add pastor'; 
        $userDetailObj  = new \App\User;
        $church= $userDetailObj->getAllChurch();
        $userRoleDetailObj  = new \App\UserRole;
        $user_roles= $userRoleDetailObj->withoutChurchudonor();
        $userOptionDetailObj = new \App\NotificationOption;
        $userOption = $userOptionDetailObj->all()->where('is_deleted','0');
        return view('pastor/adduser', compact('title','user_roles', 'userOption', 'church'));
    }

    /**
     * Determine method to add user with validation.
     *
     * @param  \App\Http\Requests\AddUserAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionAddPastor(AddUserAdminRequest $request){
        $userDetailObj = new \App\User;
         $UserAddress = new \App\UserAddress;
        $input = $request->all();
        $mailpassword = $input['password'];
            
        try{
            DB::beginTransaction();
            $nameofimage = 'user_image' . '_' . 1;
            $input['password'] = bcrypt($input['password']);
            $image = General::upload_file($request->file('image'), $nameofimage, "user_images");
            $image_url = General::get_file_src($image);
            $input['image'] = $image;
            $input['referral_id'] = '';
            $user = $userDetailObj->create($input);
           
           if($user->user_role_id == 2){
             
            $address = array();
            $address['user_id'] = $user->id; 
            $address['billingAddress1'] = $input['billingAddress1']; 
            $address['billingAddress2'] = $input['billingAddress2']; 
            $address['billingState'] = $input['billingState']; 
            $address['billingCity'] = $input['billingCity']; 
            $address['billingPostcode'] = $input['billingPostcode']; 
            
            $useraddress = $UserAddress->create($address); 
            }

           
            $text = 'Hello '.$input['firstname'].' '.$input['lastname'].',';
            $text .= '<p>Thank you For registration with us.</p>';
            $text .= '<br><br> Your login email is : '.$input['email'];
            $text .= '<br><br> Your login password is : '.$mailpassword;
            $email = $input['email'];
            Mail::send([],[], function($message) use ($email,$text)
            {
            $message->subject('Good to Give Church Register');
            $message->from('hello@gtg.com', 'Good To Give');                
            $message->to($email);
            $message->setBody($text, 'text/html');
            // echo "Thanks! Your request has been sent to ".$user->email;
            });
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'Pastor has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('pastor-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine add user screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public function addUser(Request $request) {         
        $title = 'Add User'; 
        $userDetailObj  = new \App\User;
        $church= $userDetailObj->getAllChurch();
        $userRoleDetailObj  = new \App\UserRole;
        $user_roles= $userRoleDetailObj->withoutChurch();
        $userOptionDetailObj = new \App\NotificationOption;
        $userOption = $userOptionDetailObj->all()->where('is_deleted','0');
        return view('user/adduser', compact('title','user_roles', 'userOption', 'church'));
    }
    
    /**
     * Determine method to add user with validation.
     *
     * @param  \App\Http\Requests\AddUserAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionAddUser(AddUserAdminRequest $request){
        $userDetailObj = new \App\User;
         $UserAddress = new \App\UserAddress;
          $UserMultiChurchObj  = new \App\UserMultiChurch;
        $input = $request->all();
        $mailpassword = $input['password'];
            
        try{
            DB::beginTransaction();
            $nameofimage = 'user_image' . '_' . 1;
            $input['password'] = bcrypt($input['password']);
            $image = General::upload_file($request->file('image'), $nameofimage, "user_images");
            $image_url = General::get_file_src($image);
            $input['image'] = $image;
            $input['referral_id'] = '';
            $user = $userDetailObj->create($input);
            
            $input['user_id'] = $user->id;
            $input['primary_church'] = '1';
            $input['new_request'] = 'Y';
            $create_church = $UserMultiChurchObj->create($input);
           
           if($user->user_role_id == 2){
             
            $address = array();
            $address['user_id'] = $user->id; 
            $address['billingAddress1'] = $input['billingAddress1']; 
            $address['billingAddress2'] = $input['billingAddress2']; 
            $address['billingState'] = $input['billingState']; 
            $address['billingCity'] = $input['billingCity']; 
            $address['billingPostcode'] = $input['billingPostcode']; 
            
            $useraddress = $UserAddress->create($address); 
            }

            $userSettingDetailObj = new \App\UserSetting;
            $userSettingArray = array('NOTIFICATION_OF','VIBRATION','COLOURED_BLURED','CVV_CARD');
            $userSettingData['user_id']=$user->id;
            foreach($userSettingArray as $userSettingManual){
                $userSettingData['field_name'] = $userSettingManual;
                $userSettingData['field_value'] = $input[$userSettingManual];
                $userSettingDetailObj->create($userSettingData);
            }
            $qrcode = $user->id.mt_rand(1000000, 9999999).time('Y');
            $useupdates= $userDetailObj->find($user->id);
            $useupdates->update(['qrcode' => $qrcode]);

            $text = 'Hello '.$input['firstname'].' '.$input['lastname'].',';
            $text .= '<p>Thank you For registration with us.</p>';
            $text .= '<br><br> Your login email is : '.$input['email'];
            $text .= '<br><br> Your login password is : '.$mailpassword;
            $email = $input['email'];
            Mail::send([],[], function($message) use ($email,$text)
            {
            $message->subject('Good to Give Church Register');
            $message->from('hello@gtg.com', 'Good To Give');                
            $message->to($email);
            $message->setBody($text, 'text/html');
            // echo "Thanks! Your request has been sent to ".$user->email;
            });
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'User has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('user-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine Edit user screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public function editUser(Request $request,$id) {         
        $title = 'Edit User';
        $input = $request->all();
        $userDetailObj  = new \App\User;
        $UserAddress = new \App\UserAddress;
        $user= $userDetailObj->find($id);
        $user->image = General::get_file_src($user->image);
        $userRoleDetailObj  = new \App\UserRole;
        $user_roles= $userRoleDetailObj->withoutChurch();
        $userOptionDetailObj = new \App\NotificationOption;
        $churchList = $userDetailObj->adminChurchListuser($id);
        $adminChurchListUserSelect = $userDetailObj->adminChurchListUserSelect($id);
        $userOption = $userOptionDetailObj->all()->where('is_deleted','0');
        $userSettingDetailObj = new \App\UserSetting;
        $userSetting = $userSettingDetailObj->userSetting($id,"NOTIFICATION_OF");
        $useraddressdetails = $UserAddress->get_update_address($id);
      
        
        if($userSetting)
            $notification = $userSetting->field_value;
        else
            $notification = "";

        $userSetting = $userSettingDetailObj->userSetting($id,"VIBRATION");
        if($userSetting)
            $vibration = $userSetting->field_value;
        else
            $vibration = 'disable';

        $userSetting = $userSettingDetailObj->userSetting($id,"COLOURED_BLURED");
        if($userSetting)
            $coloured_blur = $userSetting->field_value;
        else
            $coloured_blur = 'disable';

          $userSetting = $userSettingDetailObj->userSetting($id,"CVV_CARD");
        if($userSetting)
            $cvv_card = $userSetting->field_value;
        else
            $cvv_card = 'disable';
        $user->bod =  date('Y-m-d', strtotime($user->bod));
        return view('user/edituser', compact('title','user_roles', 'notification', 'vibration', 'coloured_blur','cvv_card', 'userOption', 'user', 'churchList','adminChurchListUserSelect','userSetting','useraddressdetails'));
    }

/**
     * Determine method to edit user with validation.
     *
     * @param  \App\Http\actionAddMorechurch  $request
     * @return redirect //add user method
     */
    public static function actionAddMorechurch(Request $request){
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $userDetailObj  = new \App\User;
        $userCardInfo  = new \App\UserCardInfo;
        $input = $request->all();
         $input['primary_church'] = '0';
         $input['new_request'] = 'Y';
        $church_detail = $userDetailObj->find($input['church_id']);
        
        $create_church = $UserMultiChurchObj->create($input);
       if($input['user_role_id'] == '2'){
          $switch_role = '5';
       }else{
        $switch_role = '2';
       }
       if($create_church){
        $adminChurchListUserSelect = $userDetailObj->adminChurchListuser($input['user_id']);
        $return_appanddata = array();
        $append_data  = '<div class="col-md-5">
        <div class="form-group">
        <input type="hidden" class="form-control" id="billingAddress1" value="'.$input['church_id'].'" placeholder="churchid" name="churchids[]" >
        <input type="text" class="form-control" id="billingAddress1" value="'.$church_detail->firstname.'" placeholder="Church Name" name="churchname" disabled="disabled">
        </div>
        </div>';
        $append_data .='<div class="col-md-5">
        <div class="form-group">
        <select class="c-select form-control is_church" id="user_role_id" name="user_role_ids[]" disabled="disabled">
            <option value="">Select User Role</option>
            <option value ="2"';
            if($input['user_role_id'] == '2'){ 
        $append_data .= 'selected';
            }
        $append_data .= '>Member</option>
            <option value ="5"';
            if($input['user_role_id'] == '5'){ 
        $append_data .='selected';
            }
        $append_data .= '>Visitor</option>
        </select>
        </div>
        </div>
        ';
        $append_data .='<div class="col-md-2">
        <div class="form-group">

        <button type="submit" class="btn btn-primary" onclick="switchuserrole('
        .$switch_role.','.$input['user_id'].','.$input['church_id'].')">
        Switch'; if($input['user_role_id'] == '2'){ 
        $append_data .=' Visitor';
        }if($input['user_role_id'] == '5'){ 
        $append_data .=' Member';
        }
        $append_data .='</button>
        </div>
        </div>';

        $churchlist = '<label for="userrole2">Select Church :</label>
        <select class="c-select form-control is_church" id="church_ids" name="church_ids">
        <option value="">Select Church </option>';
        
        foreach($adminChurchListUserSelect as $churchinfo){
        $churchlist .='<option value="'.$churchinfo->id.'" >'.$churchinfo->firstname.'</option>';
        }
       
         $churchlist .= '</select>';
        $return_appanddata['append_data'] = $append_data;
        $return_appanddata['append_church'] = $churchlist;
        $return_appanddata['count_church'] = count($adminChurchListUserSelect);
        return $return_appanddata;
       }else{
        return  '0';
       }

    }

     /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\upate_user_role  $request
     * @return Response
     */

     public function upate_user_role(Request $request){
            $UserMultiChurchObj  = new \App\UserMultiChurch;
            $userGiftDeclarations  = new \App\UserGiftDeclarations;
            $userDetailObj  = new \App\User;
            $userCardInfo  = new \App\UserCardInfo;
            $UserNotificationObj  = new \App\UserNotification;
            $input = $request->all();
            $user_update = $UserMultiChurchObj->update_user_wise_role($input['user_id'],$input['church_id'],$input['user_role_id']);

        
            $request->session()->put('triggeronnext', 'yes');
            if($user_update){
                $userList = $userDetailObj->user_detail_sendnotification($input['user_id'],$input['church_id']);
                /*Send User notification*/
                    $church_detail = array();
                    $church_detail['user_id'] = $userList->id;
                    $church_detail['user_name'] = $userList->firstname;
                    $church_detail['email'] = $userList->email;
                    $church_detail['user_role_id'] = $userList->user_role_id;
                    $church_detail['mobile'] = $userList->mobile;
                    $church_detail['from_user_id'] = $userList->id;
                    $church_detail['church_id'] = $input['church_id'];
                    $UserNotificationObj->send_notification($input['church_id'],$userList->id, "switch_user_role", $church_detail,$input['church_id'],$input['user_role_id']);

                /*send usernotification*/
                $message = 'User switched has been successfully';
                $jsonResponse =  General::jsonResponse(1,$message,[],'','','form');
                return $jsonResponse;
            }else{
              $message = 'User switched has been failed';
                $jsonResponse =  General::jsonResponse(2,$message,[],'','','form');
                return $jsonResponse;
            }
            
     }
    /**
     * Determine method to edit user with validation.
     *
     * @param  \App\Http\Requests\AddUserAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionEditUser(Request $request){
        $userDetailObj = new \App\User;
        $UserAddress = new \App\UserAddress;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $input = $request->all();
        $i = 0;
        
        //try{
            DB::beginTransaction();
            $user = $userDetailObj->find($input['id']);
            $nameofimage = 'user_image' . '_' . 1;
            if($input['image'] != ""){
                $image = General::upload_file($request->file('image'), $nameofimage, "user_images");
                $input['image'] = $image;
            }
            else{
                $input['image'] = $user->image;
            }
            $user->update($input);
       
            if($input['user_role_id'] == 2){
 
            $address = array();
            $address['user_id'] = $input['id']; 
            $address['billingAddress1'] = $input['billingAddress1']; 
            $address['billingAddress2'] = $input['billingAddress2']; 
            $address['billingState'] = $input['billingState']; 
            $address['billingCity'] = $input['billingCity']; 
            $address['billingPostcode'] = $input['billingPostcode']; 

              $useraddressdetail = $UserAddress->update_address($input['id']);
              
               if($useraddressdetail){
                 $useraddressupdate = $UserAddress->user_address_detail($useraddressdetail->id);
                 $useraddressupdate->update($input);
               }else{
                 $useraddress = $UserAddress->create($address); 
               }
            }
            $userSettingArray = array('NOTIFICATION_OF','VIBRATION','COLOURED_BLURED','CVV_CARD');
            
            $userSettingDetailObj = new \App\UserSetting;
            foreach($userSettingArray as $userSettingManual){
                $userSetting = $userSettingDetailObj->userSetting($input['id'], $userSettingManual);
                
                if($userSetting){
                    $userSetting->update(['field_value' => $input[$userSettingManual]] );
                }
                else{
                    $userSettingData['user_id']=$input['id'];
                    $userSettingData['field_name'] = $userSettingManual;
                    $userSettingData['field_value'] = $input[$userSettingManual];
                    $userSettingDetailObj->create($userSettingData);
                }
            }
           /* foreach ($input['churchids'] as $key => $value) {
            $UserMultiChurchObj->update_user_wise_role($input['id'],$value,$input['user_role_ids'][$i]);
            $i++;
            }*/
            // exit;
            DB::commit();
           /* Transaction successful. */
        //}catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        //}
        $request->session()->flash('message', 'User has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('user-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to edit user with validation.
     *
     * @param  \App\Http\Requests\AddUserAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionEditPastor(Request $request){
        $userDetailObj = new \App\User;
        $UserAddress = new \App\UserAddress;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        //try{
            DB::beginTransaction();
            $user = $userDetailObj->find($input['id']);
            $userrecurring = $user->is_primary;
            $nameofimage = 'user_image' . '_' . 1;
            if($input['image'] != ""){
                $image = General::upload_file($request->file('image'), $nameofimage, "user_images");
                $input['image'] = $image;
            }
            else{
                $input['image'] = $user->image;
            }
            $user->update($input);
            
            if($userrecurring != $input['is_primary']){
                
                $user = $userDetailObj->userDetail($input['id']);
                /* send notification user */
                $user_detail = array();
                $user_detail['user_id'] = $user->id;
                $user_detail['is_primary'] = $input['is_primary'];
                $UserNotificationObj->send_notification('1',$user->id, "change_primary_user_status", $user_detail,'','');

            }

            if($input['user_role_id'] == 2){
 
            $address = array();
            $address['user_id'] = $input['id']; 
            $address['billingAddress1'] = $input['billingAddress1']; 
            $address['billingAddress2'] = $input['billingAddress2']; 
            $address['billingState'] = $input['billingState']; 
            $address['billingCity'] = $input['billingCity']; 
            $address['billingPostcode'] = $input['billingPostcode']; 

              $useraddressdetail = $UserAddress->update_address($input['id']);
              
               if($useraddressdetail){
                 $useraddressupdate = $UserAddress->user_address_detail($useraddressdetail->id);
                 $useraddressupdate->update($input);
               }else{
                 $useraddress = $UserAddress->create($address); 
               }
            }
          
            // exit;
            DB::commit();
           /* Transaction successful. */
        //}catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        //}
        $request->session()->flash('message', 'User has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('pastor-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete user.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteUser(Request $request, $id){


        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $UserNotificationObj  = new \App\UserNotification;
        $user->update(['is_deleted' => '1']);
        $eventDetailObj = new \App\Event;
        $eventList = $eventDetailObj->event_list($id);
        if($eventList)
            foreach($eventList as $key => $value){
                $eventDetail = $eventDetailObj->find($value->id);
                $eventDetail->update(['is_deleted' => '1']);
            }

        $churchFundDetailObj = new \App\ChurchFund;
        $churchList = $churchFundDetailObj->church_fund_detail($id);
        if($churchList)
            foreach($churchList as $key => $value){
                $churchFundDetail = $churchFundDetailObj->find($value->id);
                $churchFundDetail->update(['is_deleted' => '1']);
            }
        
        $projectDetailObj = new \App\Project;
        $projectList = $projectDetailObj->project_list($id);
        if($projectList){
            foreach($projectList as $key => $value){
                $projectDetail = $projectDetailObj->find($value->id);
                $projectDetail->update(['is_deleted' => '1']);

                $projectSlabDetailObj = new \App\ProjectDonationSlab;
                $projectSlabList = $projectSlabDetailObj->donation_slab($value->id);
                if($projectSlabList)
                    foreach($projectSlabList as $key => $val){
                        $projectslabDetail = $projectSlabDetailObj->find($val->id);
                        $projectslabDetail->update(['is_deleted' => '1']);
                    }

                $projectDonationPaymentDetailObj = new \App\ProjectDonationPayment;
                $projectDonationPaymentList = $projectDonationPaymentDetailObj->projectDonationPayementList($value->id);
                if($projectDonationPaymentList)
                    foreach($projectDonationPaymentList as $key => $val1){
                        $projectDonationPaymentDetail = $projectDonationPaymentDetailObj->find($val1->id);
                        $projectDonationPaymentDetail->update(['is_deleted' => '1']);
                    }

                $projectImageDetailObj = new \App\ProjectImage;
                $projectImageList = $projectImageDetailObj->projectImages($value->id);
                if($projectImageList)
                    foreach($projectImageList as $key => $val2){
                        $projectImageDetail = $projectImageDetailObj->find($val2->id);
                        $projectImageDetail->update(['is_deleted' => '1']);
                    }
            }
        }

        $taskDetailObj = new \App\Task;
        $taskList = $taskDetailObj->task_list($id);
        if($taskList)
            foreach($taskList as $key => $value){
                $taskDetail = $taskDetailObj->find($value->id);
                $taskDetail->update(['is_deleted' => '1']);
            }
        
        $userInfoDetailObj = new \App\UserCardInfo;
        $userInfoList = $userInfoDetailObj->userWiseCard($id);
        if($userInfoList)
            foreach($userInfoList as $key => $value){
                $userInfoDetail = $userInfoDetailObj->find($value->id);
                $userInfoDetail->update(['is_deleted' => '1']);
            }

        $userSettingDetailObj = new \App\UserSetting;
        $userSettingList = $userSettingDetailObj->userSettingList($id);
        
        if($userSettingList)
            foreach($userSettingList as $key => $value){
                $userSettingDetail = $userSettingDetailObj->find($value->id);
                $userSettingDetail->update(['is_deleted' => '1']);
            }

        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $userdata = $userDetailObj->userTokenCheck($user->email,$user->user_role_id); 
        $token = JWTAuth::fromUser($userdata);  
        
        
        $user= $userDetailObj->find($id);
        /* send notification user */
        $user_detail = array();
        $user_detail['user_id'] = $user->id;
        $user_detail['user_name'] = $user->firstname;
        $user_detail['email'] = $user->email;
        $user_detail['user_role_id'] = $user->user_role_id;
        $user_detail['mobile'] = $user->mobile;
        $user_detail['from_user_id'] = '1';
        $UserNotificationObj->send_notification('1',$user->id, "delete_inactive_account", $user_detail,'','');

        $user->update(['device_token'=>'']);  

        $request->session()->flash('message', 'User has been deleted successfully.');
        return redirect('user');
    }

    /**
     * Determine method to delete user.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deletePastor(Request $request, $id){


        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $UserNotificationObj  = new \App\UserNotification;
        $user->update(['is_deleted' => '1']);
        $eventDetailObj = new \App\Event;
        $eventList = $eventDetailObj->event_list($id);
        if($eventList)
            foreach($eventList as $key => $value){
                $eventDetail = $eventDetailObj->find($value->id);
                $eventDetail->update(['is_deleted' => '1']);
            }

        $churchFundDetailObj = new \App\ChurchFund;
        $churchList = $churchFundDetailObj->church_fund_detail($id);
        if($churchList)
            foreach($churchList as $key => $value){
                $churchFundDetail = $churchFundDetailObj->find($value->id);
                $churchFundDetail->update(['is_deleted' => '1']);
            }
        
        $projectDetailObj = new \App\Project;
        $projectList = $projectDetailObj->project_list($id);
        if($projectList){
            foreach($projectList as $key => $value){
                $projectDetail = $projectDetailObj->find($value->id);
                $projectDetail->update(['is_deleted' => '1']);

                $projectSlabDetailObj = new \App\ProjectDonationSlab;
                $projectSlabList = $projectSlabDetailObj->donation_slab($value->id);
                if($projectSlabList)
                    foreach($projectSlabList as $key => $val){
                        $projectslabDetail = $projectSlabDetailObj->find($val->id);
                        $projectslabDetail->update(['is_deleted' => '1']);
                    }

                $projectDonationPaymentDetailObj = new \App\ProjectDonationPayment;
                $projectDonationPaymentList = $projectDonationPaymentDetailObj->projectDonationPayementList($value->id);
                if($projectDonationPaymentList)
                    foreach($projectDonationPaymentList as $key => $val1){
                        $projectDonationPaymentDetail = $projectDonationPaymentDetailObj->find($val1->id);
                        $projectDonationPaymentDetail->update(['is_deleted' => '1']);
                    }

                $projectImageDetailObj = new \App\ProjectImage;
                $projectImageList = $projectImageDetailObj->projectImages($value->id);
                if($projectImageList)
                    foreach($projectImageList as $key => $val2){
                        $projectImageDetail = $projectImageDetailObj->find($val2->id);
                        $projectImageDetail->update(['is_deleted' => '1']);
                    }
            }
        }

        $taskDetailObj = new \App\Task;
        $taskList = $taskDetailObj->task_list($id);
        if($taskList)
            foreach($taskList as $key => $value){
                $taskDetail = $taskDetailObj->find($value->id);
                $taskDetail->update(['is_deleted' => '1']);
            }
        
        $userInfoDetailObj = new \App\UserCardInfo;
        $userInfoList = $userInfoDetailObj->userWiseCard($id);
        if($userInfoList)
            foreach($userInfoList as $key => $value){
                $userInfoDetail = $userInfoDetailObj->find($value->id);
                $userInfoDetail->update(['is_deleted' => '1']);
            }

        $userSettingDetailObj = new \App\UserSetting;
        $userSettingList = $userSettingDetailObj->userSettingList($id);
        
        if($userSettingList)
            foreach($userSettingList as $key => $value){
                $userSettingDetail = $userSettingDetailObj->find($value->id);
                $userSettingDetail->update(['is_deleted' => '1']);
            }

        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $userdata = $userDetailObj->userTokenCheck($user->email,$user->user_role_id); 
        $token = JWTAuth::fromUser($userdata);  
        
        
        $user= $userDetailObj->find($id);
        /* send notification user */
        $user_detail = array();
        $user_detail['user_id'] = $user->id;
        $user_detail['user_name'] = $user->firstname;
        $user_detail['email'] = $user->email;
        $user_detail['user_role_id'] = $user->user_role_id;
        $user_detail['mobile'] = $user->mobile;
        $user_detail['from_user_id'] = '1';
        $UserNotificationObj->send_notification('1',$user->id, "delete_inactive_account", $user_detail,'','');

        $user->update(['device_token'=>'']);  

        $request->session()->flash('message', 'User has been deleted successfully.');
        return redirect('pastor');
    }
    
    /**
     * Determine update satus.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return //return list of update satus
     */
    public static function updatesatus(Request $request){
        $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        if($input['status'] == 'N'){
         $status = 'inactive';
        }else{
         $status = 'active';
        }
        
         $user = $userDetailObj->find($input['id']);
         $detail = $user->update(['status' => $status]);
        if($input['status'] == 'N'){
        $user= $userDetailObj->find($input['id']);
        /* send notification user */
        $user_detail = array();
        $user_detail['user_id'] = $user->id;
        $user_detail['user_name'] = $user->firstname;
        $user_detail['email'] = $user->email;
        $user_detail['user_role_id'] = $user->user_role_id;
        $user_detail['mobile'] = $user->mobile;
        $user_detail['from_user_id'] = '1';
        $UserNotificationObj->send_notification('1',$user->id, "delete_inactive_account", $user_detail,'','');
        $user->update(['device_token'=>'']);
        }
        /* send notification user */

        //return $detail;
    }

    /**
     * Determine church  list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return //return list of church fund
     */
    public static function churchList(Request $request){
        $userDetailObj = new \App\User;
        $churchList = $userDetailObj->adminChurchList();
        return $churchList;
    }

    /**
     * Determine logout functionality.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return redirect 
     */
    public static function Logout(Request $request){
        $request->session()->forget('ADMIN_SESSION');
        $request->session()->forget('login_detail');
        $request->session()->flush();
        $request->session()->regenerate();  
        session_unset();   
        return redirect('login');   
    }

    /**
     * Determine change Password.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function changePassword(Request $request){
        $title = 'Change Password';
        return view('user/change_password', compact('title'));
    }

    /**
     * Determine method to change password.
     *
     * @param  \App\Http\Requests\ChangePasswordAdminRequest  $request
     * @return redirect 
     */
    public static function actionChangePassword(ChangePasswordAdminRequest $request){
        $title = 'Change Password';
        $input = $request->all();
        $userDetailObj  = new \App\User;
        $user  = $userDetailObj->user_login_check($request->session()->get('login_detail')->email,1);
        $user->update(['password' => bcrypt($input['new_password'])]);
        $request->session()->flash('message', 'Password has been changed successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('dashboard'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine admin profile.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function adminProfile(Request $request){
        $title = 'Edit Profile';
        $userDetailObj  = new \App\User;
        $user  = $userDetailObj->user_login_check($request->session()->get('login_detail')->email,1);
        $user->image = General::get_file_src($user->image,'user_images');
        return view('user/editprofile', compact('title','user'));
    }

    /**
     * Determine method to update admin profile.
     *
     * @param  \App\Http\Requests\EditProfileAdminRequest  $request
     * @return response 
     */
    public static function updateAdminProfile(EditProfileAdminRequest $request){
        $title = 'Edit Profile';
        $input = $request->all();
        $userDetailObj  = new \App\User;
        $user  = $userDetailObj->userDetail($input['id']);
        $nameofimage = 'user_image' . '_' . 1;

        if($input['image'] == "")
            $image = $user->image;
        else
            $image = General::upload_file($request->file('image'), $nameofimage, "user_images");

        $input['image'] = $image;
        $user->update($input);
        $request->session()->flash('message', 'Admin profile has been changed successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('dashboard'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine user management.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public function ChurchManagement(Request $request) {
        $title = 'Church List';
        return view('church/church', compact('title'));
    }

/**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function userViewQrCode(Request $request,$id){
        $title = "View Qr Code";
        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $qrcode_image = General::get_file_src($user->id.'-churchcode.png');
        return view('qrcode/view_user_qrcode', compact('title','user','qrcode_image'));
    }
     /**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function churchViewQrCode(Request $request,$id){
        $title = "View Qr Code";
        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $qrcode_image = General::get_file_src($user->id.'-churchcode.png');
        return view('qrcode/view_church_qrcode', compact('title','user','qrcode_image'));
    }

      /**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function getChurchGenerateQrCode(Request $request){
        $title = "Generate Qr Code";
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->find($input['id']);
        try{
            DB::beginTransaction();
            
            $explode_name = explode(' ',$user->firstname);

            $qrcode = $input['id'].mt_rand(1000000, 9999999).time('Y');
            // QrCode::size(500)->format('png')->generate($qrcode, public_path('storage/public/storage/user_images/'.$input['id'].'-code.png'));
            if($input['qrcode'] == ''){
            $user->update(['qrcode'=>$qrcode]);    
            }
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        return "1" ;
    }

     /**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function getUserGenerateQrCode(Request $request){
        $title = "Generate Qr Code";
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->find($input['id']);
        try{
            DB::beginTransaction();
            
            $explode_name = explode(' ',$user->firstname);

            $qrcode = $input['id'].mt_rand(1000000, 9999999).time('Y');
            // QrCode::size(500)->format('png')->generate($qrcode, public_path('storage/public/storage/user_images/'.$input['id'].'-code.png'));
            if($input['qrcode'] == ''){
            $user->update(['qrcode'=>$qrcode]);    
            }
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        return "1" ;
    }

     /**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function getChurchGenerateQrCodeVisitor(Request $request){
        $title = "Generate Qr Code";
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->find($input['id']);
        try{
            DB::beginTransaction();
            
            $explode_name = explode(' ',$user->firstname);

            $qrcode = $input['id'].mt_rand(1000000, 9999999).time('m');
            // QrCode::size(500)->format('png')->generate($qrcode, public_path('storage/public/storage/user_images/'.$input['id'].'-code.png'));
            if($input['qrcode'] == ''){
            $user->update(['qrcode_visitor'=>$qrcode]);    
            }
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        return "1" ;
    }

    /**
     * Determine user list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function getChurch(Request $request){
        $userDetailObj  = new \App\User;
        $user = $userDetailObj->getAllChurch();
        $page = datatables()->of($user)
                ->editColumn('user_photo', function ($page) {
                    $image_url = General::get_file_src($page->image);
                    return '<img src="' . $image_url . '" alt="" height="50" width="50" />';
                })->addColumn('user_name', function ($page) {
                    return $page->firstname.' '.$page->lastname;
                })->addColumn('email', function ($page) {
                    return $page->email;
                })->addColumn('mobile', function ($page) {
                    return $page->mobile;
                })->addColumn('is_primary', function ($page) {
                   
                    if($page->is_primary == 'Y'){
                      $primary = 'Yes';
                    }else{
                      $primary = 'No';
                    }
                     return $primary;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_church') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_church/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                    })
                ->addColumn('qrcode', function ($page) {
                    return '<a href="'. URL('church_view_qr_code/'.$page->id ) .'" class="btn btn-success" ><i class="la la-cloud-download"></i></a>';
                    })
                ->rawColumns(['action','qrcode','user_photo'])
                    ->addIndexColumn()
                    ->toJson();   
                    return $page;
    }

    /**
     * Determine add user screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public function addChurch(Request $request) {         
        $title = 'Add Church'; 
        return view('church/addchurch', compact('title'));
    }

    /**
     * Determine method to add user with validation.
     *
     * @param  \App\Http\Requests\AddChurchAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionAddChurch(AddChurchAdminRequest $request){
        $userDetailObj = new \App\User;
         $projectImageDetailObj = new \App\ProjectDefaultImages;
        $input = $request->all();
        $mailpassword = $input['password'];
        try{
            DB::beginTransaction();
            $nameofimage = 'user_image' . '_' . 1;
            $input['password'] = bcrypt($input['password']);
            $input['user_role_id'] = 3;
            $image = General::upload_file($request->file('image'), $nameofimage, "user_images");
            $image_url = General::get_file_src($image);
            $input['image'] = $image;
            $input['referral_id'] = 'gtg'.mt_rand(10000, 99999).((int)$userDetailObj->churchCount()+1).'church';
            
            if($input['day'] && $input['month']){
              $finacedaymonth = $input['day'].'/'.$input['month'];
            }else{
              $finacedaymonth = '';
            }
            $user = $userDetailObj->create($input);
            $findproject_default = $projectImageDetailObj->projectImagesFirst();
            $input['BACKGROUND_IMAGE'] = $findproject_default->image;
            $input['TRANSITION_POSITION'] =config("constants.transition_position");
            $input['BACKGROUND_COLOR'] =config("constants.background_color");
            $input['FINACIAL_DAY_MONTH'] =$finacedaymonth;
            $userSettingDetailObj = new \App\UserSetting;
            $userSettingArray = array('BACKGROUND_IMAGE','TRANSITION_POSITION','BACKGROUND_COLOR','FINACIAL_DAY_MONTH');
            $userSettingData['user_id']=$user->id;
            foreach($userSettingArray as $userSettingManual){
            $userSettingData['field_name'] = $userSettingManual;
            $userSettingData['field_value'] = $input[$userSettingManual];
            $userSettingDetailObj->create($userSettingData);
            }
            $qrcode = $user->id.mt_rand(1000000, 9999999).time('Y');
            $qrcodevisitor = $user->id.mt_rand(1000000, 9999999).time('m');
            $useupdate= $userDetailObj->find($user->id);
            $useupdate->update(['qrcode' => $qrcode,'qrcode_visitor' => $qrcodevisitor]);
            $text = 'Hello '.$input['firstname'].',';
            $text .= '<p>Thank you For registration with us.</p>';
            $text .= '<br><br> Your login email is : '.$input['email'];
            $text .= '<br><br> Your login password is : '.$mailpassword;
           $email = $input['email'];
            Mail::send([],[], function($message) use ($email,$text)
            {
            $message->subject('Good to Give Church Register');
            $message->from('hello@gtg.com', 'Good To Give');                
            $message->to($email);
            $message->setBody($text, 'text/html');
            // echo "Thanks! Your request has been sent to ".$user->email;
            });

            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'Church has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('church-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine Edit user screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public function editChurch(Request $request,$id) {         
        $title = 'Edit User';
        $input = $request->all();
        // print_r($id);exit;

        $userDetailObj  = new \App\User;
        $userSettingDetailObj = new \App\UserSetting;

        // print_r($input);exit;
        $user= $userDetailObj->find($id);
        $userSetting = $userSettingDetailObj->userSetting($id,"FINACIAL_DAY_MONTH");
        
        if($userSetting && $userSetting->field_value){
          $yeardata = explode('/',$userSetting->field_value);
          $user->day = $yeardata[0];
          $user->month = $yeardata[1];
        }else{
           $user->day = '';
          $user->month = '';
        }
        $user->image = General::get_file_src($user->image);
      
        return view('church/editchurch', compact('title', 'user'));
    }

    /**
     * Determine method to edit user with validation.
     *
     * @param  \App\Http\Requests\AddUserAdminRequest  $request
     * @return redirect //add user method
     */
    public static function actionEditChurch(Request $request){
        $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();

        try{
            DB::beginTransaction();
            $nameofimage = 'user_image' . '_' . 1;
            $user = $userDetailObj->find($input['id']);
            $userrecurring = $user->is_primary;
            $nameofimage = 'user_image' . '_' . 1;
            if($input['image'] != ""){
                $image = General::upload_file($request->file('image'), $nameofimage, "user_images");
                $input['image'] = $image;
            }
            else{
                $input['image'] = $user->image;
            }
            $updatechurch = $user->update($input);
         
            /*if($userrecurring != $input['is_primary']){*/
                
                $user = $userDetailObj->userDetail($input['id']);
                /* send notification user */
                $user_detail = array();
                $user_detail['user_id'] = $user->id;
                $UserNotificationObj->send_notification('1',$user->id, "change_primary_user_status", $user_detail,'','');

            /*}*/
            if($input['day'] && $input['month']){
              $finacedaymonth = $input['day'].'/'.$input['month'];
            }else{
              $finacedaymonth = '';
            }
            $input['FINACIAL_DAY_MONTH'] =$finacedaymonth;
            $userSettingDetailObj = new \App\UserSetting;
            $userSettingArray = array('FINACIAL_DAY_MONTH');
            $userSettingData['user_id']=$input['id'];
            foreach($userSettingArray as $userSettingManual){
            $userSettingData['field_name'] = $userSettingManual;
            $userSettingData['field_value'] = $input[$userSettingManual];
            $userSettingDetailObj->updateuserSetting($userSettingData['user_id'],$userSettingData['field_name'],$userSettingData['field_value']);
            }

            // exit;
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'Church has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('church-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete user.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteChurch(Request $request, $id){
        $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $user = $userDetailObj->find($id);
        $user->update(['is_deleted' => '1']);
        $eventDetailObj = new \App\Event;
        $eventList = $eventDetailObj->event_list($id);
        if($eventList)
            foreach($eventList as $key => $value){
                $eventDetail = $eventDetailObj->find($value->id);
                $eventDetail->update(['is_deleted' => '1']);
            }
        
        $projectDetailObj = new \App\Project;
        $projectList = $projectDetailObj->project_list($id);
        if($projectList){
            foreach($projectList as $key => $value){
                $projectDetail = $projectDetailObj->find($value->id);
                $projectDetail->update(['is_deleted' => '1']);

                $projectSlabDetailObj = new \App\ProjectDonationSlab;
                $projectSlabList = $projectSlabDetailObj->donation_slab($value->id);
                if($projectSlabList)
                    foreach($projectSlabList as $key => $val){
                        $projectslabDetail = $projectSlabDetailObj->find($val->id);
                        $projectslabDetail->update(['is_deleted' => '1']);
                    }

                $churchFundDetailObj = new \App\ChurchFund;
                $churchList = $churchFundDetailObj->church_fund_detail($id);
                if($churchList)
                    foreach($churchList as $key => $value){
                        $churchFundDetail = $churchFundDetailObj->find($value->id);
                        $churchFundDetail->update(['is_deleted' => '1']);
                    }

                $projectDonationPaymentDetailObj = new \App\ProjectDonationPayment;
                $projectDonationPaymentList = $projectDonationPaymentDetailObj->projectDonationPayementList($value->id);
                if($projectDonationPaymentList)
                    foreach($projectDonationPaymentList as $key => $val1){
                        $projectDonationPaymentDetail = $projectDonationPaymentDetailObj->find($val1->id);
                        $projectDonationPaymentDetail->update(['is_deleted' => '1']);
                    }

                $projectImageDetailObj = new \App\ProjectImage;
                $projectImageList = $projectImageDetailObj->projectImages($value->id);
                if($projectImageList)
                    foreach($projectImageList as $key => $val2){
                        $projectImageDetail = $projectImageDetailObj->find($val2->id);
                        $projectImageDetail->update(['is_deleted' => '1']);
                    }
            }
        }

        $taskDetailObj = new \App\Task;
        $taskList = $taskDetailObj->task_list($id);
        if($taskList)
            foreach($taskList as $key => $value){
                $taskDetail = $taskDetailObj->find($value->id);
                $taskDetail->update(['is_deleted' => '1']);
            }
        
        $userInfoDetailObj = new \App\UserCardInfo;
        $userInfoList = $userInfoDetailObj->userWiseCard($id);
        if($userInfoList)
            foreach($userInfoList as $key => $value){
                $userInfoDetail = $userInfoDetailObj->find($value->id);
                $userInfoDetail->update(['is_deleted' => '1']);
            }

        $userSettingDetailObj = new \App\UserSetting;
        $userSettingList = $userSettingDetailObj->userSettingList($id);
        
        if($userSettingList)
            foreach($userSettingList as $key => $value){
                $userSettingDetail = $userSettingDetailObj->find($value->id);
                $userSettingDetail->update(['is_deleted' => '1']);
            }
        
        $userdetail = $userDetailObj->delete_user_church($id);

         if($userdetail)
            foreach($userdetail as $key => $value){
                $userSettingDetail = $userDetailObj->find($value->id);
                $userSettingDetail->update(['is_deleted' => '1']);
            }
         $UserMultiChurchObj  = new \App\UserMultiChurch;
         $user_detal_multi = $UserMultiChurchObj->delete_church_all_user($id);
          if($user_detal_multi){
            foreach($user_detal_multi as $key => $value){
                $userSettingDetail1 = $userDetailObj->find($value->user_id);
                $userSettingDetail1->update(['is_deleted' => '1']);
            }
          }

        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($id);
        $userdata = $userDetailObj->userTokenCheck($user->email,$user->user_role_id); 
        $token = JWTAuth::fromUser($userdata);  
        
        
        $user = $userDetailObj->find($id);
        /* send notification user */
        $user_detail = array();
        $user_detail['user_id'] = $user->id;
        $user_detail['user_name'] = $user->firstname;
        $user_detail['email'] = $user->email;
        $user_detail['user_role_id'] = $user->user_role_id;
        $user_detail['mobile'] = $user->mobile;
        $user_detail['from_user_id'] = '1';
        $UserNotificationObj->send_notification('1',$user->id, "delete_inactive_account", $user_detail,'','');
        $user->update(['device_token'=>'']);

        $request->session()->flash('message', 'Church has been deleted successfully.');
        return redirect('church');
    }

     /**
     * Determine method to delete user.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function memberRequest(Request $request){
       
        $title = 'Member Request';
        return view('memberrequest/memberuser', compact('title'));

    }


     /**
     * Determine user list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function getmemberUser(Request $request){
            $userDetailObj  = new \App\User;
            $user = $userDetailObj->MemberNewReuqest();
            $page = datatables()->of($user)
                    ->editColumn('user_photo', function ($page) {
                        $image_url = General::get_file_src($page->image);
                        return '<img src="' . $image_url . '" alt="" height="50" width="50" />';
                    })
                    ->editColumn('church_name', function ($page) use($userDetailObj) {
                        $church_name = $userDetailObj->getchutchname($page->church_id);
                        if($church_name){
                        $church_name =  $church_name->firstname;   
                        }else{
                        $church_name =  'N/A';
                        }
                        return $church_name;
                    })->addColumn('user_role', function ($page) {
                        if($page->user_role_id == 2)
                            return "Member";
                        else if($page->user_role_id == 5)
                            return "visitor";
                        else 
                            return "N/A";
                    })->addColumn('user_name', function ($page) {
                        return $page->firstname.' '.$page->lastname;
                    })->addColumn('email', function ($page) {
                        return $page->email;
                    })->addColumn('mobile', function ($page) {
                        return $page->mobile;
                    })->editColumn('status', function ($page) {
                        return '
                            <input type="checkbox" name="status[]" data-id="' . $page->id . '" data-toggle="toggle" data-on="<b>Active</b>" data-off="<b>Inactive</b>" data-size="normal" data-onstyle="success" data-offstyle="danger" ' . (($page->status == 'active') ? "checked=checked" : "") . ' class="status_toggle_class">';
                    })->addColumn('action', function ($page) {
                        return '<div style="display: flex !important;"><button type="button" class="btn btn-success apreject"  data-action="approve" data-id= "'.$page->id.'" style="padding: 7px !important;" >Approve</button> 
                        <button type="submit"  class="btn btn-danger apreject" data-action="reject" data-id= "'.$page->id.'" style="padding: 7px !important;">Reject</button></div>
                        ';
                        /*return '<a style="margin-right:10px" href="' . URL::to('/admin/edit_user') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('admin/delete_user/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';*/
                        })->rawColumns(['action','status','user_photo'])
                        ->addIndexColumn()
                        ->toJson();   
                        return $page;
        }

    /**
    * Determine user list.
    *
    * @param  \App\Http\Requests\Request  $request
    * @return return 
    */
    public static function requestmemberUser(Request $request){

     $userDetailObj  = new \App\User;
     $UserNotificationObj  = new \App\UserNotification;
     $UserMultiChurchObj  = new \App\UserMultiChurch;
     $input = $request->all();
     $users= $UserMultiChurchObj->find($input['id']);
     
     $user= $userDetailObj->find($users->user_id);
      
        if($input['new_request'] == 'Y') {       
          
            $text = 'Hello '.$user->firstname.' '.$user->lastname.',';
            $text .= '<p>This is a system generated email to notify you that admin has accepted your request and replied on same which was made through Good to Give App</p>';

        Mail::send([],[], function($message) use ($user,$text)
        {
        $message->subject('Good to Give Member Request has been approved');
        $message->from('hello@gtg.com', 'Good To Give');                
        $message->to($user->email);
        $message->setBody($text, 'text/html');
        // echo "Thanks! Your request has been sent to ".$user->email;
        });

         $church_details = $userDetailObj->find($users->church_id);

         /* Send Notification church */
            $church_detail = array();
            $church_detail['user_id'] = $church_details->id;
            $church_detail['user_name'] = $church_details->firstname;
            $church_detail['email'] = $church_details->email;
            $church_detail['user_role_id'] = $church_details->user_role_id;
            $church_detail['mobile'] = $church_details->mobile;
            $church_detail['from_user_id'] = '1';
            $UserNotificationObj->send_notification('1',$church_details->id, "approve_member", $church_detail,'','');
        
        /* Send Notification church */

        /* Send Notification user */

            $usersend = $userDetailObj->user_detail_sendnotification($users->user_id,$users->church_id);


            $church_detail = $userDetailObj->find($usersend->church_id);
            $user_detail = array();
            $user_detail['user_id'] = $user->id;
            $user_detail['user_name'] = $user->firstname;
            $user_detail['email'] = $user->email;
            $user_detail['user_role_id'] = $usersend->user_role_id;
            $user_detail['mobile'] = $user->mobile;
            $user_detail['from_user_id'] = '1';
            $user_detail['church_id'] = $usersend->church_id;
            $user_detail['church_name'] = $church_detail->firstname;
            $UserNotificationObj->send_notification('1',$user->id, "approve_user_member", $user_detail,$usersend->church_id,$usersend->user_role_id);
        
        /* Send Notification user */

        }else{

            $text = 'Hello '.$user->firstname.' '.$user->lastname.',';
            $text .= '<p>This is a system generated email to notify you that admin has rejected your request and replied on same which was made through Good to Give App</p>';

            Mail::send([],[], function($message) use ($user,$text)
            {
            $message->subject('Good to Give Member Request has been rejected');
            $message->from('hello@gtg.com', 'Good To Give');                
            $message->to($user->email);
            $message->setBody($text, 'text/html');
            // echo "Thanks! Your request has been sent to ".$user->email;
            });

            $church_details = $userDetailObj->find($users->church_id);
         /* Send Notification church */
            $church_detail = array();
            $church_detail['user_id'] = $church_details->id;
            $church_detail['user_name'] = $church_details->firstname;
            $church_detail['email'] = $church_details->email;
            $church_detail['user_role_id'] = $church_details->user_role_id;
            $church_detail['mobile'] = $church_details->mobile;
            $church_detail['from_user_id'] = '1';
            $UserNotificationObj->send_notification('1',$church_details->id, "reject_member", $church_detail,'','');
        
        /* Send Notification church */

        

            /* send notification user*/
             $usersend = $userDetailObj->user_detail_sendnotification($users->user_id,$users->church_id);
             $church_detail = $userDetailObj->find($usersend->church_id);
            $user_detail = array();
            $user_detail['user_id'] = $user->id;
            $user_detail['user_name'] = $user->firstname;
            $user_detail['email'] = $user->email;
            $user_detail['user_role_id'] = $usersend->user_role_id;
            $user_detail['mobile'] = $user->mobile;
            $user_detail['from_user_id'] = '1';
            $user_detail['church_id'] = $usersend->church_id;
            $user_detail['church_name'] = $church_detail->firstname;
            $UserNotificationObj->send_notification('1',$user->id, "reject_user_member", $user_detail,$usersend->church_id,$usersend->user_role_id);
            /* send notification user*/

        }
        $updatedetail = $users->update($input);
        if($users->primary_church == '1'){
        $updatedetail = $user->update($input);  
        }
    }
}
