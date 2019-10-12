<?php
/**
 * An ApiController Class 
 *
 * The controller class is using to controll all Api.
 *
 * @package    Good To Give
 * @subpackage Common
 * @author     Acquaint Soft Developer 
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use JWTFactory;
use JWTAuth;
use App\User;
use App\ProjectDonationPayments;
use App\UserCardInfo;
use Mail;
use QrCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\GeneralController AS General;
use App\Http\Requests\UserApiRequest;
use App\Http\Requests\RegisterApiRequest;
use App\Http\Requests\UpdateProfileApiRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DeleteNotificationApiRequest;
use App\Http\Requests\UserIdApiRequest;
use App\Http\Requests\ProjectDetailApiRequest;
use App\Http\Requests\ScriptureEditApiRequest;
use App\Http\Requests\NextEventApiRequest;
use App\Http\Requests\QrCodeVerificationApiRequest;
use App\Http\Requests\ChurchQrCodeVerificationApiRequest;
use App\Http\Requests\UserQrCodeVerificationApiRequest;
use App\Http\Requests\GenerateChurchQrcodeVerificationApiRequest;
use App\Http\Requests\GenerateUserQrcodeVerificationApiRequest;
use App\Http\Requests\ChurchDetailApiRequest;
use App\Http\Requests\AddTaskApiRequest;
use App\Http\Requests\DeleteTaskApiRequest;
use App\Http\Requests\AddEventApiRequest;
use App\Http\Requests\DeleteEventApiRequest;
use App\Http\Requests\AddFundApiRequest;
use App\Http\Requests\DeleteFundApiRequest;
use App\Http\Requests\AddProjectApiRequest;
use App\Http\Requests\UserListApiRequest;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ShowScriptureApiRequest;
use App\Http\Requests\ProjectDonationslabsApiRequest;
use App\Http\Requests\NextEventListApiRequest;
use App\Http\Requests\FundNameListApiRequest;
use App\Http\Requests\EditFundApiRequest;
use App\Http\Requests\EditProjectApiRequest;
use App\Http\Requests\EditUserApiRequest;
use App\Http\Requests\UpdateSettingApiRequest;
use App\Http\Requests\EditTaskApiRequest;
use App\Http\Requests\ReferralIdApiRequest;
use App\Http\Requests\TransactionDetailsApiRequest;
use App\Http\Requests\SwitchChurchApiRequest;
use App\Http\Requests\UserAddMultiChurchApiRequest;
use App\Http\Requests\UserUpdateMultiChurchApiRequest;
use App\Http\Requests\LogoutUserApiRequest;
use App\Http\Requests\ChurchExistsRequest;
use App\Http\Requests\TransactionYearApiRequest;
use App\Http\Requests\UserFamilyTreeApiRequest;
use Omnipay\Omnipay;
use Omnipay\Common\Helper;
use Omnipay\Common\CreditCard;
use Form\Server\Direct;
class ApiController extends Controller
{
    /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests\UserApiRequest  $request
     * @return Response
    */
    public function login(UserApiRequest $request){
        $input = $request->all();
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
         $UserMultiChurchObj  = new \App\UserMultiChurch;
        $userCardInfo  = new \App\UserCardInfo;
        if($request->has('device_type'))
            $device_type = $input['device_type'];
        else
            $device_type = '';
        
        if($request->has('device_token'))
            $device_token = $input['device_token'];
        else
            $device_token = '';

        $email  = $input['email'];
        $password  = $input['password'];
        $userDetailObj  = new \App\User;
        $user  = $userDetailObj->userLoginCheck($input['email'],$input['user_role_id']);

        if($user){
           /* if($user->user_role_id == '2'){
            if($user->new_request != 'Y'){
                $message = 'Your account is not yet approved by administrator. Please contact administrator.';
                $jsonResponse =  General::jsonResponse(0,$message); 
                return $jsonResponse;
            }
           }*/
            $user->update(['device_type' => $device_type, 'device_token' => $device_token]);
            if($user->status != 'active'){
                $message = 'Your account has been block.Please contact administrator.';
                $jsonResponse =  General::jsonResponse(0,$message); 
                return $jsonResponse;
            }
        } 

        if ($user) {
            if(Hash::check($password , $user->password))
            {
                $token = JWTAuth::fromUser($user);
                $message = 'Login successfully.';
                $user->image =  General::get_file_src($user->image);
                if($user->user_role_id != 3){
                    $userChurch = $userDetailObj->userDetail($user->church_id);
                    if($userChurch){
                    $user->referral_id = $userChurch->referral_id;
                    }else{
                    $user->referral_id = '';    
                    }
                }
                if($user->user_role_id == 3){
                 $referrarDetailObj  = new \App\Referrar;
                $referraruserList= $referrarDetailObj->referrar_detail($user->id);
                if($referraruserList){
                 $user->sagepay_referrer_Id =  $referraruserList->referrer_Id;
                 $user->sagepay_vendor_id =  $referraruserList->vendor_id;
                }else{
                  $user->sagepay_referrer_Id =  '';
                 $user->sagepay_vendor_id =  '';
                }
                $userdetail = $userDetailObj->userDetail($user->id);
                if($userdetail->qrcode != '')
                $user->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode));
                else
                $user->church_generated_qrcode = '';

                if($userdetail->qrcode_visitor != '')
                $user->church_generated_qrcode_visitor = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode_visitor));
                else
                $user->church_generated_qrcode_visitor = '';
                
                }
                 if($user->user_role_id == 4){
                    $getscripture = $userDetailObj->find($user->church_id);
                    $user->scripture = $getscripture->scripture;
                 }
                if($user->user_role_id == 2){
                $UserAddress = new \App\UserAddress;
               $useraddressdetail = $UserAddress->update_address($user->id);
                if($useraddressdetail){
                 $user->address_detail =  $useraddressdetail;
                }else{
                    $address = array();
                    $address['id'] = '';
                    $address['user_id'] = '';
                    $address['billingAddress1'] = '';
                    $address['billingAddress2'] = '';
                    $address['billingState'] = '';
                    $address['billingCity'] = '';
                    $address['billingPostcode'] = '';
                    $address['billingCountry'] = '';
                    $address['created_at'] = '';
                    $address['updated_at'] = '';

                $user->address_detail =$address;
                }
               }
                
                $count_declaration = $userGiftDeclarations->countdeclaration($user->id);
                if($count_declaration > 0){
                 $user->is_past_project =true;   
                }else{
                $user->is_past_project =false;
                }
                $count_cardinfo = $userCardInfo->countcarddetail($user->id);
                 if($count_cardinfo > 0){
                 $user->is_card_detail =true;   
                }else{
                $user->is_card_detail =false;
                }
               
                if($user->user_role_id == 2 || $user->user_role_id == 5){

                 $userdetails = $userDetailObj->userDetail($user->id);
                if($userdetails->qrcode != '')
                $user->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($userdetails->qrcode));
                else
                $user->church_generated_qrcode = '';

                $UserMultiChurchObj = $UserMultiChurchObj->user_church_list($user->id);
                $church_detail = array();
  
                foreach ($UserMultiChurchObj as $key => $value) {

                $church_detailobj = $userDetailObj->single_church_detail($value->church_id);
                $church_detailobj->image =  General::get_file_src($church_detailobj->image);
                $church_detailobj->new_request = $value->new_request;
                $church_detailobj->user_role_id = $value->user_role_id;
                $church_detailobj->primary_church = $value->primary_church;
                $church_detail[] = $church_detailobj;
                }
                $user->church_id = $church_detail;
                 }
                
                $jsonResponse =  General::jsonResponse(1,$message,$user,'',$token,'form');
            }
            else{
              /*  $message = 'Password does not match.';
                $jsonResponse =  General::jsonResponse(3,$message,'','','','form');*/
                $message = 'Your email address or password is incorrect.';
                $errorData = array();
                $errorData['error'] = array('Your email address or password is incorrect.');
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            }
        }else{
            $message = 'Your email address or password is incorrect.';
            $jsonResponse =  General::jsonResponse(0,$message);
        }
        return $jsonResponse;
    }

    /**
     * Determine church, donor, pastor registration based on user roles.
     *
     * @param  \App\Http\Requests\RegisterApiRequest  $request
     * @return Response
    */
    public function registration(RegisterApiRequest $request ){
        $userDetailObj  = new \App\User;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $userCardInfo  = new \App\UserCardInfo;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        if($input['user_role_id'] != 3){
            if(empty($input['referral_id'])|| $input['referral_id'] == ''){
                $message = 'Church not found.';
                $jsonResponse = General::jsonResponse(2,$message,'','','','form');
                return $jsonResponse;
            }
            $userReferral = $userDetailObj->adminChurchReferral($input['referral_id']);
            if(!$userReferral){
                $message = 'Church not found.';
                $jsonResponse = General::jsonResponse(2,$message,'','','','form');
                return $jsonResponse;
            }
            $input['referral_id'] = '';
            $input['church_id'] = $userReferral->id;

            
            if($request->has('image')){
                $nameofimage = 'user_image';
                $input['image'] = General::upload_file($input['image'], $nameofimage, "user_images");
            }
           
            
        }
        else{
            $input['referral_id'] = 'gtg'.mt_rand(10000, 99999).(int($userDetailObj->churchCount())+1).'church';
            $input['image'] = General::upload_file($request->file('image'), "church_images", "user_images");
        }
        $user = $userDetailObj->create($input);
        $input['user_id'] = $user->id;
        $input['primary_church'] = '1';
        if($input['user_role_id'] == 5){
        $input['new_request'] = 'Y';
        }else{
        $input['new_request'] = 'N';
        }
        if($input['user_role_id'] == 2 || $input['user_role_id'] == 5){
        $qrcode = $user->id.mt_rand(1000000, 9999999).time('Y');
        $useupdates= $userDetailObj->find($user->id);
        $useupdates->update(['qrcode' => $qrcode]);
        }

        $UserMultiChurchObja = $UserMultiChurchObj->create($input);
        $userSettingDetailObj = new \App\UserSetting;
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'NOTIFICATION_OF','field_value'=>'1']);
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'VIBRATION','field_value'=>'disable']);
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'COLOURED_BLURED','field_value'=>'disable']); 
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'CVV_CARD','field_value'=>'enable']); 
        if($input['user_role_id'] == 2){
         
            /* Send Notification church */
            $church_details = $userDetailObj->find($input['church_id']);
            $church_detail = array();
            $church_detail['user_id'] = $user->id;
            $church_detail['user_name'] = $user->firstname;
            $church_detail['email'] = $user->email;
            $church_detail['user_role_id'] = $user->user_role_id;
            $church_detail['mobile'] = $user->mobile;
            $church_detail['from_user_id'] = $user->id;
            $church_detail['church_id'] = $input['church_id'];
            $church_detail['church_name'] = $church_details->firstname;
            $church_detail['referral_id'] = $church_details->referral_id;
            $UserNotificationObj->send_notification($user->id,$input['church_id'], "member_registration", $church_detail,$input['church_id'],$input['user_role_id']);

            /* Send Notification church */

            /* Send Notification pastor */
            $pastor_detail = $userDetailObj->getchurchpastorlist($input['church_id']);
            if($pastor_detail){
             $church_details = $userDetailObj->find($input['church_id']);
            foreach ($pastor_detail as $key => $value) {
                $church_detail = array();
                $church_detail['user_id'] = $user->id;
                $church_detail['user_name'] = $user->firstname;
                $church_detail['email'] = $user->email;
                $church_detail['user_role_id'] = $user->user_role_id;
                $church_detail['mobile'] = $user->mobile;
                $church_detail['from_user_id'] = $user->id;
                $church_detail['church_id'] = $input['church_id'];
                $church_detail['church_name'] = $church_details->firstname;
                $church_detail['referral_id'] = $church_details->referral_id;
                $UserNotificationObj->send_notification($user->id,$value->id, "member_registration", $church_detail,$input['church_id'],$input['user_role_id']);
            }
            }
            

            /* Send Notification pastor */
        }
        $userInfo = $userDetailObj->userDetail($user->id);
        if($userInfo->user_role_id != 3){
            $userChurch = $userDetailObj->userDetail($userInfo->church_id);
            $userInfo->referral_id = $userChurch->referral_id;
        }
        $count_declaration = $userGiftDeclarations->countdeclaration($user->id);
        if($count_declaration > 0){
        $userInfo->is_past_project =true;   
        }else{
        $userInfo->is_past_project =false;
        }
        $count_cardinfo = $userCardInfo->countcarddetail($user->id);
        if($count_cardinfo > 0){
          $userInfo->is_card_detail =true;   
        }else{
          $userInfo->is_card_detail =false;
        }
        if($userInfo->user_role_id == 2 || $userInfo->user_role_id == 5){
        $UserMultiChurchObj = $UserMultiChurchObj->user_church_list($userInfo->id);
        $church_detail = array();
        foreach ($UserMultiChurchObj as $key => $value) {
            $church_detailobj = $userDetailObj->single_church_detail($value->church_id);
            $church_detailobj->image =  General::get_file_src($church_detailobj->image);
            $church_detailobj->new_request = $value->new_request;
            $church_detailobj->user_role_id = $value->user_role_id;
            $church_detailobj->primary_church = $value->primary_church;
            $church_detail[] = $church_detailobj;
        }
        $userInfo->church_id = $church_detail;

        if($userInfo->qrcode != '')
        $userInfo->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($userInfo->qrcode));
        else
        $userInfo->church_generated_qrcode = '';
        }
        $token = JWTAuth::fromUser($userInfo);
        $message = 'Registration has been successfully done.';
        $userInfo->image =  General::get_file_src($userInfo->image);
        $jsonResponse =  General::jsonResponse(1,$message,$userInfo,'',$token,'form');
        return $jsonResponse;
    }
     
      /**
     * Determine change password of user based on user id.
     *
     * @param  \App\Http\Requests\ChangePasswordRequest  $request
     * @return Response
     */
    public function churchUpdateDetail(Request $request){
        $userDetailObj  = new \App\User;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $userCardInfo  = new \App\UserCardInfo;
        
        $input = $request->all();
        $userInfo = $userDetailObj->userDetail($input['user_id']);
        $message = 'User detail.';
        $userInfo->image =  General::get_file_src($userInfo->image);
        $jsonResponse =  General::jsonResponse(1,$message,$userInfo,'','','form');
        return $jsonResponse;
    
    }
    /**
     * Determine change password of user based on user id.
     *
     * @param  \App\Http\Requests\ChangePasswordRequest  $request
     * @return Response
     */
    public function changePassword(ChangePasswordRequest $request ){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['id']);
        if($user){
            if(Hash::check($input['old_password'], $user->password)){
                $user->update(['password' => bcrypt($input['new_password'])]);
                $message = 'Password has been changed successfully.';
                $user->image =  General::get_file_src($user->image);
                $jsonResponse =  General::jsonResponse(1,$message,$user,'','','form');
            }
            else{
                $message = 'Current password is incorrect.';
                $errorData = array();
                $errorData['error'] = array('Current password is incorrect.');
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            }
        }
        else{
            $message = 'User not found.';
            $jsonResponse =  General::jsonResponse(0,$message,'','','','form');
        }
        return $jsonResponse;
    }

    /**
     * Determine user profile to update based on user id.
     *
     * @param  \App\Http\Requests\UpdateProfileApiRequest  $request
     * @return Response
     */
    public function userProfileUpdate(UpdateProfileApiRequest $request ){

        $userDetailObj = new \App\User;
        $userSettingDetailObj = new \App\UserSetting;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $projectImageDetailObj = new \App\ProjectDefaultImages;
        $userCardInfo  = new \App\UserCardInfo;
        $input = $request->all();
        
        if(isset($input['cvv'])){
         $userSettingDetailObj->updateuserSetting($input['id'],'CVV_CARD',$input['cvv']);
        }
        if($input['user_role_id'] == 3 || $input['user_role_id'] == 4){
        if(isset($input['finacial_day_month'])){
        $userSettingDetailObj->updateuserSetting($input['id'],'FINACIAL_DAY_MONTH',$input['finacial_day_month']);
        }

        if(isset($input['transition_position']) AND $input['is_custom'] == '1'){
         $userSettingDetailObj->updateuserSetting($input['id'],'TRANSITION_POSITION',$input['transition_position']);
        }else{
          $userSettingDetailObj->updateuserSetting($input['id'],'TRANSITION_POSITION',config("constants.transition_position"));   
        }
       
        if(isset($input['background_color']) AND $input['is_custom'] == '1'){
         $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_COLOR',$input['background_color']);
        }else{ 
        $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_COLOR', config("constants.background_color"));
           
        }
    
        
        if($request->has('background_image') AND $input['is_custom'] == '1'){
         $nameofimage = 'bguser_image_'.uniqid();
         if($input['is_defaultimage'] == '0'){
          $findproject_default = $projectImageDetailObj->getprojectImages($input['background_image']);
          $images = $findproject_default->image;
         }else{
           $images = General::upload_file($input['background_image'], $nameofimage, "user_images");  
         }
         $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_IMAGE',$images);
        }else{
         $findproject_default = $projectImageDetailObj->getprojectImages($input['background_image']);

        /*$findproject_default = $projectImageDetailObj->projectImagesFirst();*/
        $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_IMAGE',$findproject_default->image);    
        }

        }
        
        $user = $userDetailObj->userDetail($input['id']);

        if($user){
            if($request->has('image')){
                $nameofimage = 'user_image_'.uniqid();
                $input['image'] = General::upload_file($input['image'], $nameofimage, "user_images");
            }
            $user->update($input);
            $message = 'Profile has been updated successfully.';
            
            $user->image =  General::get_file_src($user->image);

            if($user->user_role_id != 3){
                $userChurch = $userDetailObj->userDetail($user->church_id);
                if($userChurch){
                $user->referral_id = $userChurch->referral_id;
                }else{
                 $user->referral_id = '';   
                }
            }

            if($user->user_role_id == 2 || $user->user_role_id == 5){
            $UserMultiChurchObj = $UserMultiChurchObj->user_church_list($user->id);
            $church_detail = array();
            foreach ($UserMultiChurchObj as $key => $value) {
            $church_detailobj = $userDetailObj->single_church_detail($value->church_id);
            $church_detailobj->image =  General::get_file_src($church_detailobj->image);
            $church_detailobj->new_request = $value->new_request;
            $church_detailobj->user_role_id = $value->user_role_id;
            $church_detailobj->primary_church = $value->primary_church;
            $church_detail[] = $church_detailobj;
            }
            $user->church_id = $church_detail;
            }
            $count_cardinfo = $userCardInfo->countcarddetail($user->id);
            if($count_cardinfo > 0){
                $user->is_card_detail =true;   
            }else{
                $user->is_card_detail =false;
            }
            $count_declaration = $userGiftDeclarations->countdeclaration($user->id);
            if($count_declaration > 0){
                $user->is_past_project =true;   
            }else{
                $user->is_past_project =false;
            }
            

            $jsonResponse =  General::jsonResponse(1,$message,$user,'','','form');
        }
        else{
            $message = 'User not found.';
            $jsonResponse =  General::jsonResponse(0,$message,'','','','form');
        }
        return $jsonResponse;
    }

    /**
     * Determine user profile to update based on user id.
     *
     * @param  \App\Http\Requests\UpdateSettingApiRequest  $request
     * @return Response
     */
    public function userProfileSetting(UpdateSettingApiRequest $request ){
        $userDetailObj = new \App\User;
        $userSettingDetailObj = new \App\UserSetting;
        $userCardInfo  = new \App\UserCardInfo;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $projectImageDetailObj = new \App\ProjectDefaultImages;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['id']);

        if($user){
            // $input['lastname'] = '';
            $message = 'Profile has been updated successfully.';
            $image = $user->image;
            if($request->has('image')){
                $nameofimage = 'user_image_'.uniqid();
                $input['image'] = General::upload_file($input['image'], $nameofimage, "user_images");
                $image = $input['image'];
            }

            if(isset($input['transition_position']) AND $input['is_custom'] == '1'){
            $userSettingDetailObj->updateuserSetting($input['id'],'TRANSITION_POSITION',$input['transition_position']);
            }else{
            $userSettingDetailObj->updateuserSetting($input['id'],'TRANSITION_POSITION',config("constants.transition_position"));   
            }

            if(isset($input['background_color']) AND $input['is_custom'] == '1'){
            $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_COLOR',$input['background_color']);
            }else{ 
            $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_COLOR', config("constants.background_color"));

            }

            if($request->has('background_image') AND $input['is_custom'] == '1'){
            $nameofimage = 'bguser_image_'.uniqid();
            if($input['is_defaultimage'] == '0'){
            $findproject_default = $projectImageDetailObj->getprojectImages($input['background_image']);
            $images = $findproject_default->image;
            }else{
            $images = General::upload_file($input['background_image'], $nameofimage, "user_images");  
            }
            $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_IMAGE',$images);
            }else{
            $findproject_default = $projectImageDetailObj->getprojectImages($input['background_image']);

            /*$findproject_default = $projectImageDetailObj->projectImagesFirst();*/
            $userSettingDetailObj->updateuserSetting($input['id'],'BACKGROUND_IMAGE',$findproject_default->image);    
            }

            $user->update($input);

            $users = $userDetailObj->userDetail($input['id']);
            $users->image =  General::get_file_src($users->image);
                $count_declaration = $userGiftDeclarations->countdeclaration($users->id);
                if($count_declaration > 0){
                    $users->is_past_project =true;   
                }else{
                    $users->is_past_project =false;
                }
                $count_cardinfo = $userCardInfo->countcarddetail($users->id);
                if($count_cardinfo > 0){
                    $users->is_card_detail =true;   
                }else{
                    $users->is_card_detail =false;
                }
                if($users->qrcode != '')
                $users->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($users->qrcode));
                else
                $users->church_generated_qrcode = '';

                if($users->qrcode_visitor != '')
                $users->church_generated_qrcode_visitor = base64_encode(QrCode::format('png')->size(300)->generate($users->qrcode_visitor));
                else
                $users->church_generated_qrcode_visitor = '';
            
            $jsonResponse =  General::jsonResponse(1,$message,$users,'','','form');
        }
        else{
            $message = 'User not found.';
            $jsonResponse =  General::jsonResponse(0,$message,'','','','form');
        }
        return $jsonResponse;
    }

    /**
     * Determine logout on user id.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public static function logout(LogoutUserApiRequest $request){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['id']);
        $user->update(['device_token'=>'']);
        $message = 'Successfully logout.';
        $user->image =  General::get_file_src($user->image);
        $jsonResponse =  General::jsonResponse(1,$message,$user,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine function to delete notification of users.
     *
     * @param  \App\Http\Requests\ChurchDetailApiRequest  $request
     * @return Response
     */
    public static function projectList(ChurchDetailApiRequest $request){
        
        $projectDetailObj = new \App\Project;
        $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $project = $projectDetailObj->projectListDonation($start,'0',$input['church_id'],$input['role_id'],$input['is_archive']);
        $projectcount = $projectDetailObj->projectListDonation($start,'1',$input['church_id'],$input['role_id'],$input['is_archive']);

        $next = "false";
        $projectListing = array();
        $message = "No data found";
        $projectCount = json_decode(json_encode($project), true);
        $projectPaymentDonationDetail = new \App\ProjectDonationPayment;
        // print_r($project->project_images);exit;
        if(!empty($projectCount)){
            
            $message = 'Project List.';
            // $user->image =  General::get_file_src($user->image);


            foreach($project as $key => $value){
                foreach($value->project_images as $key => $val){
                    $val->image =  General::get_file_src($val->image);
                }
                foreach ($value->project_settings_detail as $key => $values) {
                    if($values->field_name == 'BACKGROUND_IMAGE'){
                        $values->field_value = General::get_file_src($values->field_value);
                    }
                }
            }
             $totalPages = ceil($projectcount['total'] / $perpage);
            if($totalPages > $page){
                $next = "true";
                foreach($project as $key =>$value){
                     $value->total_amount_count = $projectPaymentDonationDetail->totalFundCollectionprojectamount($value->id);
                    $value->total_amount = $projectPaymentDonationDetail->totalFundCollectionproject($value->id,'2');
                    $value->total_visitor_amount = $projectPaymentDonationDetail->totalFundCollectionproject($value->id,'5');
                    $value->total_donor = $projectPaymentDonationDetail->projectDonationPayementCount($value->id);
                    if($value->qrcode != '')
                        $value->generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($value->qrcode));
                    else
                        $value->generated_qrcode = '';
                    $projectListing[] = json_decode(json_encode($value), true);
                   
                }
                // $userListing = json_decode(json_encode($user), true);
                //array_pop($projectListing);
            }
            else{
                foreach($project as $key =>$value){
                    $value->total_amount_count = $projectPaymentDonationDetail->totalFundCollectionprojectamount($value->id);
                    $value->total_amount = $projectPaymentDonationDetail->totalFundCollectionproject($value->id,'2');
                    $value->total_visitor_amount = $projectPaymentDonationDetail->totalFundCollectionproject($value->id,'5');
                    $value->total_donor = $projectPaymentDonationDetail->projectDonationPayementCount($value->id);
                    if($value->qrcode != '')
                        $value->generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($value->qrcode));
                    else
                        $value->generated_qrcode = '';
                    $projectListing[] = json_decode(json_encode($value), true);
                }
                
                $next = "false";
            }
            
         /*   if($input['role_id'] == '2' || $input['role_id'] == '3'){

                $projectListing = array_values(array_filter($projectListing, function ($value){
                return ($value['goal_amount'] > $value['total_amount_count']);
                }));
            }*/
            
            if($projectListing){
             $jsonResponse = General::jsonResponse(1,$message,$projectListing,$next,'','form');
            }else{
                $message = 'No Data found.';
                $errorData = array();
                $errorData['church_id'] = array($message);
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            }

            return $jsonResponse;
        }
        $jsonResponse = General::jsonResponse(2,$message,$projectListing,$next,'','form');
        return $jsonResponse;
    }

    /**
     * Determine project detail.
     *
     * @param  \App\Http\Requests\ProjectDetailApiRequest  $request
     * @return Response
     */
    public static function projectDetail(ProjectDetailApiRequest $request){
        $projectDetailObj = new \App\Project;
        $projectPaymentDonationDetail = new \App\ProjectDonationPayment;
        $input = $request->all();
        $project = $projectDetailObj->project_detail($input['project_id']);

           foreach ($project->project_settings_detail as $key => $value) {
            if($value->field_name == 'BACKGROUND_IMAGE'){
            $value->field_value = General::get_file_src($value->field_value);
            }
            }
             foreach($project->project_images as $key => $val){
                    $val->image =  General::get_file_src($val->image);
                }
        
            $project->total_amount_count = $projectPaymentDonationDetail->totalFundCollectionprojectamount($project->id);
            $project->total_amount = $projectPaymentDonationDetail->totalFundCollectionproject($project->id,'2');
            $project->total_visitor_amount = $projectPaymentDonationDetail->totalFundCollectionproject($project->id,'5');
            $project->total_donor = $projectPaymentDonationDetail->projectDonationPayementCount($project->id);
            if($project->qrcode != '')
            $project->generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($project->qrcode));
            else
            $project->generated_qrcode = '';
                    
                   
                
        $message = 'Project Detail.';
        $jsonResponse = General::jsonResponse(1,$message,$project,'','','form');
        return $jsonResponse;
    }


        /**
            * Determine qr code verification.
            *
            * @param  \App\Http\Requests\GenerateChurchQrcodeVerificationApiRequest  $request
            * @return Response
        */

   public static function generateChurchQrcode(GenerateChurchQrcodeVerificationApiRequest $request){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->find($input['user_id']);
        if($input['user_role_id'] == '2'){
            $qrcode = $input['user_id'].mt_rand(1000000, 9999999).time('Y');
            if(empty($user->qrcode)){
                $edit_qrcode = $user->update(['qrcode'=>$qrcode]);
             }
       }else{
            $qrcode_visitor = $input['user_id'].mt_rand(1000000, 9999999).time('m');
            if(empty($user->qrcode_visitor)){
                $edit_qrcode = $user->update(['qrcode_visitor'=>$qrcode_visitor]);
             }
       }
        $userdetail = $userDetailObj->userDetail($input['user_id']);
        $userdetail->image =  General::get_file_src($userdetail->image);

        if($userdetail->qrcode != '')
        $userdetail->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode));
        else
        $userdetail->church_generated_qrcode = '';


        if($userdetail->qrcode_visitor != '')
        $userdetail->church_generated_qrcode_visitor = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode_visitor));
        else
        $userdetail->church_generated_qrcode_visitor = '';

        $message = 'Get Qr Code';
        $jsonResponse = General::jsonResponse(1,$message,$userdetail);
        return $jsonResponse;
        
   }   



        /**
            * Determine qr code verification.
            *
            * @param  \App\Http\Requests\GenerateUserQrcodeVerificationApiRequest  $request
            * @return Response
        */

   public static function generateUserQrcode(GenerateUserQrcodeVerificationApiRequest $request){
        $userDetailObj = new \App\User;
         $UserMultiChurchObj  = new \App\UserMultiChurch;
          $userGiftDeclarations  = new \App\UserGiftDeclarations;

        $input = $request->all();
        $user = $userDetailObj->find($input['user_id']);
        $qrcode = $input['user_id'].mt_rand(1000000, 9999999).time('Y');
        if(empty($user->qrcode)){
            $edit_qrcode = $user->update(['qrcode'=>$qrcode]);
        }
       
        $userdetail = $userDetailObj->userDetail($input['user_id']);
        $userdetail->image =  General::get_file_src($userdetail->image);

        if($userdetail->qrcode != '')
        $userdetail->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode));
        else
        $userdetail->church_generated_qrcode = '';


        if($userdetail->qrcode_visitor != '')
        $userdetail->church_generated_qrcode_visitor = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode_visitor));
        else
        $userdetail->church_generated_qrcode_visitor = '';

        if($userdetail->user_role_id == 2){
                $UserAddress = new \App\UserAddress;
               $useraddressdetail = $UserAddress->update_address($input['user_id']);
                if($useraddressdetail){
                 $userdetail->address_detail =  $useraddressdetail;
                }else{
                    $address = array();
                    $address['id'] = '';
                    $address['user_id'] = '';
                    $address['billingAddress1'] = '';
                    $address['billingAddress2'] = '';
                    $address['billingState'] = '';
                    $address['billingCity'] = '';
                    $address['billingPostcode'] = '';
                    $address['billingCountry'] = '';
                    $address['created_at'] = '';
                    $address['updated_at'] = '';

                $userdetail->address_detail =$address;
                }
               }
                
                $count_declaration = $userGiftDeclarations->countdeclaration($input['user_id']);
                if($count_declaration > 0){
                 $userdetail->is_past_project =true;   
                }else{
                $userdetail->is_past_project =false;
                }
                $UserCardInfos  = new \App\UserCardInfo;
                $count_cardinfo = $UserCardInfos->countcarddetail($input['user_id']);
                 if($count_cardinfo > 0){
                 $userdetail->is_card_detail =true;   
                }else{
                $userdetail->is_card_detail =false;
                }

        $UserMultiChurchObj = $UserMultiChurchObj->user_church_list($input['user_id']);
        
        $church_detail = array();

        foreach ($UserMultiChurchObj as $key => $value) {

        $church_detailobj = $userDetailObj->single_church_detail($value->church_id);
        $church_detailobj->image =  General::get_file_src($church_detailobj->image);
        $church_detailobj->new_request = $value->new_request;
        $church_detailobj->user_role_id = $value->user_role_id;
        $church_detailobj->primary_church = $value->primary_church;
        $church_detail[] = $church_detailobj;
        }
        $userdetail->church_id = $church_detail;


        $message = 'Get Qr Code';
        $jsonResponse = General::jsonResponse(1,$message,$userdetail);
        return $jsonResponse;
        
   }   

   /**
        * Determine qr code verification.
        *
        * @param  \App\Http\Requests\UserQrCodeVerificationApiRequest $request
        * @return Response
    */
    
    public static function qrCodeUserVerification(UserQrCodeVerificationApiRequest $request){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $userDetail = $userDetailObj->qrCodeUserList($input['qr_code']);
        if($userDetail){
            if($userDetail->qrcode == $input['qr_code']){
                $message = 'QR code verified.';
                $userDetail->image =  General::get_file_src($userDetail->image); 
                $data = array('id'=>$userDetail->id,'referral_id'=>$userDetail->referral_id,
                'firstname'=>$userDetail->firstname,'lastname'=>$userDetail->lastname,'image'=>$userDetail->image,'status'=>true);

            }else{
                $message = 'QR code is not verified.';
                $errorData = array();
                $errorData['church_id'] = array($message);
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
                return $jsonResponse;

            }
        }else{
            $message = 'QR code is not verified.';
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;

        }
        $jsonResponse = General::jsonResponse(1,$message,$data);
        return $jsonResponse;
    }

    /**
        * Determine qr code verification.
        *
        * @param  \App\Http\Requests\ChurchQrCodeVerificationApiRequest $request
        * @return Response
    */
    
    public static function qrCodeChurchVerification(ChurchQrCodeVerificationApiRequest $request){

        $userDetailObj = new \App\User;
        $input = $request->all();
        $userDetail = $userDetailObj->qrCodeChurchList($input['qr_code'],$input['user_role_id']);
        if($userDetail){
        if($userDetail->qrcode == $input['qr_code']){
            $message = 'QR code verified.';
            $userDetail->image =  General::get_file_src($userDetail->image); 
            $data = array('id'=>$userDetail->id,'referral_id'=>$userDetail->referral_id,
            'firstname'=>$userDetail->firstname,'lastname'=>$userDetail->lastname,'image'=>$userDetail->image,'status'=>true); 
        }else if($userDetail->qrcode_visitor == $input['qr_code']){
            $message = 'QR code verified.';
            $userDetail->image =  General::get_file_src($userDetail->image); 
            $data = array('id'=>$userDetail->id,'referral_id'=>$userDetail->referral_id,
            'firstname'=>$userDetail->firstname,'lastname'=>$userDetail->lastname,'image'=>$userDetail->image,'status'=>true); 
        }else{
            $message = 'QR code is not verified.';
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
            
        }
      }else{
        if($input['user_role_id'] == '2'){
                $message = 'Please scan member QR code.';
                $errorData = array();
                $errorData['church_id'] = array($message);
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
                return $jsonResponse;
            }else{
                $message = 'QR code is not verified.';
                $errorData = array();
                $errorData['church_id'] = array($message);
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
                return $jsonResponse;
            }
            
      }
        
        $jsonResponse = General::jsonResponse(1,$message,$data);
        return $jsonResponse;
    }
    /**
     * Determine qr code verification.
     *
     * @param  \App\Http\Requests\QrCodeVerificationApiRequest  $request
     * @return Response
     */
    public static function qrCodeVerification(QrCodeVerificationApiRequest $request){
        $projectDetailObj = new \App\Project;
        $input = $request->all();
        $project = $projectDetailObj->project_detail($input['project_id']);
        
        if($project->qr_code == $input['qr_code']){ 
            $message = 'QR code verified.';
            $data = (object)array('status'=>true);
        }
        else{
            $message = 'QR code is not verified.';
            $data = (object)array('status'=>false);
        }
        
        $jsonResponse = General::jsonResponse(1,$message,$data);
        return $jsonResponse;
    }

    /**
     * Determine user detail request on user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public static function userDetailWithSetting(UserIdApiRequest $request){
        $projectDetailObj = new \App\User;
        $input = $request->all();
        $project = $projectDetailObj->userDetail($input['user_id']);
        $message = 'User Detail.';
        $jsonResponse = General::jsonResponse(1,$message,$project,'','','form');
        return $jsonResponse;
    }
     
     /**
     * Determine user detail request on user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public static function userAcceptReject(Request $request){
        $userDetailObj  = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $input = $request->all();
        //$users= $UserMultiChurchObj->find($input['user_id']);
        $user= $userDetailObj->find($input['user_id']);


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
        /* Send Notification church */
        /*$church_detail = array();
        $church_detail['user_id'] = $user->id;
        $church_detail['user_name'] = $user->firstname;
        $church_detail['email'] = $user->email;
        $church_detail['user_role_id'] = $user->user_role_id;
        $church_detail['mobile'] = $user->mobile;
        $church_detail['from_user_id'] = '1';
        $UserNotificationObj->send_notification('1',$user->church_id, "approve_member", $church_detail);*/

        /* Send Notification church */

        /* Send Notification users */
        $church_detail = $userDetailObj->find($input['church_id']);
        $userList = $userDetailObj->user_detail_sendnotification($input['user_id'],$input['church_id']);
        $user_detail = array();
        $user_detail['user_id'] = $userList->id;
        $user_detail['user_name'] = $userList->firstname;
        $user_detail['email'] = $userList->email;
        $user_detail['user_role_id'] = $userList->user_role_id;
        $user_detail['mobile'] = $userList->mobile;
        $user_detail['from_user_id'] = '1';
        $user_detail['church_id'] = $input['church_id'];
        $user_detail['church_name'] = $church_detail->firstname;
        $user_detail['referral_id'] = $church_detail->referral_id;
        $UserNotificationObj->send_notification('1',$userList->id, "approve_user_member", $user_detail,$input['church_id'],$userList->user_role_id);

        /* Send Notification users */

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
        /* send notification church*/
       /* $church_detail = array();
        $church_detail['user_id'] = $user->id;
        $church_detail['user_name'] = $user->firstname;
        $church_detail['email'] = $user->email;
        $church_detail['user_role_id'] = $user->user_role_id;
        $church_detail['mobile'] = $user->mobile;
        $church_detail['from_user_id'] = '1';
        $UserNotificationObj->send_notification('1',$user->church_id, "reject_member", $church_detail);*/
        /* send notification church*/

        /* send notification user*/
        $church_detail = $userDetailObj->find($input['church_id']);
        $userList = $userDetailObj->user_detail_sendnotification($input['user_id'],$input['church_id']);
        $user_detail = array();
        $user_detail['user_id'] = $userList->id;
        $user_detail['user_name'] = $userList->firstname;
        $user_detail['email'] = $userList->email;
        $user_detail['user_role_id'] = $userList->user_role_id;
        $user_detail['mobile'] = $userList->mobile;
        $user_detail['from_user_id'] = '1';
        $user_detail['church_id'] = $input['church_id'];
        $user_detail['church_name'] = $church_detail->firstname;
        $user_detail['referral_id'] = $church_detail->referral_id;
        $UserNotificationObj->send_notification('1',$userList->id, "reject_user_member", $user_detail,$input['church_id'],$userList->user_role_id);
        /* send notification user*/

        }
        $update_users  = $UserMultiChurchObj->update_user_request($input['user_id'],$input['new_request'],$input['is_deleted'],$input['church_id']);
        if($input['primary_church'] == '1'){
        $updatedetail = $user->update($input);    
        }
        //$updatedetail = $user->update($input);
        if($input['new_request'] == 'Y'){
         $message = 'User has been approved.';
        }else{
          $message = 'User has been rejected.';
        }
       
        $jsonResponse = General::jsonResponse(1,$message,[],'','','form');
        return $jsonResponse;
    }


     /**
     * Determine user detail request on user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public static function userDetailWithAcceptReject(UserIdApiRequest $request){
        $projectDetailObj = new \App\User;
        $input = $request->all();
        $project = $projectDetailObj->userDetailAccept_reject($input['user_id'],$input['church_id']);
        if($project){
        if($project->dob == null){
           $project->dob = ''; 
        }
        $project->image =  General::get_file_src($project->image);
        }

        $message = 'User Detail.';
        $jsonResponse = General::jsonResponse(1,$message,$project,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine church list based on search church name and referral id.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function churchListWithSearch(Request $request){
        $projectDetailObj = new \App\User;
        $input = $request->all();
        if($request->has('text'))
            $text = $input['text'];
        else
            $text = '';

        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;
        
        if($request->has('referral_id'))
            $referral_id = $input['referral_id'];
        else
            $referral_id = '';    

        if($text == '' && $referral_id == ''){
            $project = $projectDetailObj->churchList($start,'0');
            $projectcounts = $projectDetailObj->churchList($start,'1');
        }else{
            $project = $projectDetailObj->churchList($start,'0',$text,$referral_id);
            $projectcounts = $projectDetailObj->churchList($start,'1',$text,$referral_id);
         }
        $projectCount = json_decode(json_encode($project), true);
        
        if(!empty($projectCount)){
            $message = 'Church Detail.';

            $totalPages = ceil($projectcounts['total'] / $perpage);
        if ($totalPages > $page) {
                $next = "true";
                //array_pop($project);
            }
            else{
                $next = "false";
            }

            $churchListing = array();
            foreach($project as $key=>$value){
                $value->image =  General::get_file_src($value->image); 
                $churchListing[] = array('referral_id'=>$value->referral_id,
                'firstname'=>$value->firstname,'lastname'=>$value->lastname,'image'=>$value->image); 
            }

            $jsonResponse = General::jsonResponse(1,$message,$churchListing,$next,'','form');
            return $jsonResponse;
        }
        else{
            $message = 'Church not found.';
            $jsonResponse = General::jsonResponse(0,$message,'','false','','front_form');
            return $jsonResponse;
        }
    }

    /**
     * Determine function to update scripture.
     *
     * @param  \App\Http\Requests\ScriptureEditApiRequest  $request
     * @return Response
     */
    public static function editScripture(ScriptureEditApiRequest $request){
        $userDetailObj = new \App\User;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
         $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['id']);
        $edit_scripture = $user->update(['scripture'=>$input['scripture']]);

        /* notification send for pastor */
         $pastore_detail  = $userDetailObj->userDetails_scripture($input['id']);
         $church_details = $userDetailObj->find($input['id']);
         $sender_username = $userDetailObj->find($input['user_id']);
         foreach ($pastore_detail as $key => $value) {
             if($input['user_id'] != $value->id){
               $users = $userDetailObj->userDetail($value->id);
               if($users){
                /* pastor send notification user */
                $user_detail = array();
                $user_detail['user_id'] = $users->id;
                $user_detail['email'] = $users->email;
                $user_detail['user_role_id'] = $users->user_role_id;
                $user_detail['mobile'] = $users->mobile;
                $user_detail['image'] = $users->image;
                $user_detail['from_user_id'] = $input['user_id'];
                $user_detail['user_name'] = $sender_username->firstname;
                $user_detail['church_id'] = $input['id'];
                $user_detail['church_name'] = $church_details->firstname;
                $user_detail['referral_id'] = $church_details->referral_id;
                $UserNotificationObj->send_notification($input['user_id'],$users->id, "update_scripture", $user_detail,'','');
                }
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
                $user_detail['from_user_id'] = $input['user_id'];
                $user_detail['user_name'] = $sender_username->firstname;
                $user_detail['church_id'] = $input['id'];
                $user_detail['church_name'] = $church_details->firstname;
                $user_detail['referral_id'] = $church_details->referral_id;
                $UserNotificationObj->send_notification($input['user_id'],$users->id, "update_scripture", $user_detail,'','');
              }
         }
        $message = 'Notice board has been updated successfully.';
        $jsonResponse = General::jsonResponse(1,$message,'','','','form');
        return $jsonResponse;
    }
    
    /**
     * Determine function to update scripture.
     *
     * @param  \App\Http\Requests\ShowScriptureApiRequest  $request
     * @return Response
     */
    public static function showScripture(ShowScriptureApiRequest $request){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['church_id']);
        $message = 'Church Scripture.';
        $user->image =  General::get_file_src($user->image);
        $jsonResponse = General::jsonResponse(1,$message,$user,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine function to show user list.
     *
     * @param  \App\Http\Requests\UserListApiRequest  $request
     * @return Response
     */
    public static function userList(UserListApiRequest $request){
        $userDetailObj = new \App\User;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

         if($request->has('is_register'))
            $is_register = $input['is_register'];
        else
            $is_register = '';

        if($request->has('is_register'))
            $is_visitor = $input['is_visitor'];
        else
            $is_visitor = 'Y';

        /* start sorting by selected task when task edit from app*/
        if($input['task_id']){
          $task_user_list = $UserTaskAssignObj->task_list_with_id($input['task_id']);
          $task_user_select = $task_user_list->member_id;
        }else{
          $task_user_select = null;
        }
        /* end sorting by selected task when task edit from app*/

        /* start sorting by selected group when group edit from app*/
        if($input['group_id']){
          $group_user_list = $taskgroupmemberDetailObj->group_member_task_list($input['group_id']);
          $group_assign_id = array();
          foreach ($group_user_list as $key => $value) {
               $group_assign_id[] = $value->user_id;
          }
          $task_group_select = implode(",",$group_assign_id);
        }else{
          $task_group_select = null;
        }
        /* end sorting by selected group when group edit from app*/
        

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;
        
        $user = $userDetailObj->userListPagination($start,'0',$input['church_id'],$input['user_role_id'],$input['search'],$is_register,$is_visitor,$task_user_select,$task_group_select);
        
        
        $usercounts = $userDetailObj->userListPagination($start,'1',$input['church_id'],$input['user_role_id'],$input['search'],$is_register,$is_visitor);
        $next = "false";
        $userListing = array();
        $message = "No data found";
        $usercount = json_decode(json_encode($user), true);

        // print_r($usercount);exit;
        if(!empty($usercount)){
            if($input['user_role_id'] == 2)
                $message = 'Donor list.';
            else
                $message = 'Pastor list.';
           // $message = 'User List.';
            // $user->image =  General::get_file_src($user->image);
            $totalPages = ceil($usercounts['total'] / $perpage);
        if ($totalPages > $page) {
         
                $next = "true";
                foreach($user as $key =>$value){
                    if($value->dob == null){
                      $value->dob = '';                          
                    }
                    $value->image =  General::get_file_src($value->image);
                    $userListing[] = json_decode(json_encode($value), true);
                }
                // $userListing = json_decode(json_encode($user), true);
                //array_pop($userListing);
            }
            else{
                foreach($user as $key =>$value){
                     if($value->dob == null){
                      $value->dob = '';                          
                    }
                    $value->image =  General::get_file_src($value->image);
                    $userListing[] = json_decode(json_encode($value), true);
                }
                
                $next = "false";
            }
             
            $jsonResponse = General::jsonResponse(1,$message,$userListing,$next,'','form');
            return $jsonResponse;
        }

        $jsonResponse = General::jsonResponse(0,$message,$userListing,$next,'','form');
        return $jsonResponse;
        
        // $jsonResponse = General::jsonResponse(1,$message,$user);
        // return $jsonResponse;
    }

    /**
     * Determine function to add user.
     *
     * @param  \App\Http\Requests\AddUserRequest  $request
     * @return Response
     */
    public function addUser(AddUserRequest $request ){
        $userDetailObj  = new \App\User;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']);
        $nameofimage = 'user_image';
        if($input['mobile'] == '11111111111'){
            $input['mobile'] = '';
        }
        $input['image'] = General::upload_file($input['image'], $nameofimage, "user_images");
        $user = $userDetailObj->create($input); 
        
        $input['user_id'] = $user->id;
        $userInfos = $userDetailObj->userDetail($user->id);
        if($userInfos->user_role_id != 4){
        $input['primary_church'] = '1';
        $input['new_request'] = 'Y';
        $create_church = $UserMultiChurchObj->create($input); 
        }
                
        $userSettingDetailObj = new \App\UserSetting;
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'NOTIFICATION_OF','field_value'=>'1']);
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'VIBRATION','field_value'=>'disable']);
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'COLOURED_BLURED','field_value'=>'disable']);
        $userSetting= $userSettingDetailObj->create(['user_id'=>$user->id,'field_name'=>'CVV_CARD','field_value'=>'enable']);
        $userInfo = $userDetailObj->userDetail($user->id);
        $userInfo->image =  General::get_file_src($userInfo->image);
        if($userInfo->user_role_id == 2)
            $message = 'Donor has been added successfully.';
        else
            $message = 'Pastor has been added successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$userInfo);
        return $jsonResponse;
    }

    /**
     * Determine function to update user.
     *
     * @param  \App\Http\Requests\EditUserApiRequest  $request
     * @return Response
     */
    public function editUser(EditUserApiRequest $request ){
        $userDetailObj  = new \App\User;
        $input = $request->all();
        $user  = $userDetailObj->userDetail($input['id']);
        if($input['mobile'] == '11111111111'){
            $input['mobile'] = '';
        }
        if($request->has('image')){
            $nameofimage = 'user_image';
            $input['image'] = General::upload_file($input['image'], $nameofimage, "user_images");
        }
        $user->update($input);
        $user  = $userDetailObj->userDetail($input['id']);
        $user->image =  General::get_file_src($user->image);
        
        if($user->user_role_id == 2)
            $message = 'Donor has been updated successfully.';
        else
            $message = 'Pastor has been updated successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$user);
        return $jsonResponse;
    }
    /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public function editUserSetting(Request $request ){
        $userSettingDetailObj  = new \App\UserSetting;
        $input = $request->all();
        
        // $userSettingValue = array('notification_of','vibration','coloured_blured');
        // foreach($userSettingValue as $value){
            // if($value == $input['notification_of']){
                $userUpdateSetting = $userSettingDetailObj->userSetting($input['user_id'],'NOTIFICATION_OF');
                $userUpdateSetting->update(['field_value'=>$input['notification_of']]);
            // }
            // if($value == $input['vibration']){
                $userUpdateSetting = $userSettingDetailObj->userSetting($input['user_id'],'VIBRATION');
                $userUpdateSetting->update(['field_value'=>$input['vibration']]);
                $userUpdateSetting = $userSettingDetailObj->userSetting($input['user_id'],'COLOURED_BLURED');
                $userUpdateSetting->update(['field_value'=>$input['coloured_blured']]);
                
              /*  $userUpdateSetting = $userSettingDetailObj->userSetting($input['user_id'],'CVV_CARD');
                $userUpdateSetting->update(['field_value'=>$input['cvv_card']]);*/
            // }
        // }
        $userSetting  = $userSettingDetailObj->userSettingList($input['user_id']);
            $message = 'Notification Setting has been updated successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$userSetting);
        return $jsonResponse;
    }

    /**
     * Determine function to delete user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public function deleteUser(UserIdApiRequest $request ){
        $userDetailObj  = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['user_id']);
       

         /* send notification user */
            $user= $userDetailObj->find($input['user_id']);
           
            $user_detail = array();
            $user_detail['user_id'] = $user->id;
            $user_detail['user_name'] = $user->firstname;
            $user_detail['email'] = $user->email;
            $user_detail['user_role_id'] = $user->user_role_id;
            $user_detail['mobile'] = $user->mobile;
            $user_detail['from_user_id'] = '1';
            $UserNotificationObj->send_notification('1',$user->id, "delete_inactive_account", $user_detail,'','');
            
            /* send notification user */
        $user->update(['is_deleted'=>'1','device_token'=>'']);
       // $user->update(['device_token'=>'']);
        if($user->user_role_id == 2)
            $message = 'Donor has been deleted successfully.';
        else
            $message = 'Pastor has been deleted successfully.';
            
        $jsonResponse =  General::jsonResponse(1,$message,(object)array());
        return $jsonResponse;
    }

     /**
     * Determine function to block user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public function blockUser(UserIdApiRequest $request ){
        $userDetailObj  = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $user = $userDetailObj->userDetail($input['user_id']);
        $user->update(['status'=>$input['status']]);

        if($input['status'] == 'active'){
            if($user->user_role_id == 2)
                $message = 'Donor has been activated successfully.';
            else
                $message = 'Pastor has been activated successfully.';
        }
        else{
            /* send notification user */
            $user= $userDetailObj->find($input['user_id']);
            $user_detail = array();
            $user_detail['user_id'] = $user->id;
            $user_detail['user_name'] = $user->firstname;
            $user_detail['email'] = $user->email;
            $user_detail['user_role_id'] = $user->user_role_id;
            $user_detail['mobile'] = $user->mobile;
            $user_detail['from_user_id'] = '1';
            $UserNotificationObj->send_notification('1',$user->id, "delete_inactive_account", $user_detail,'','');
            
            /* send notification user */
            $user->update(['device_token'=>'']);
            if($user->user_role_id == 2)
                $message = 'Donor has been blocked successfully.';
            else
                $message = 'Pastor has been blocked successfully.';
        }
        $jsonResponse =  General::jsonResponse(1,$message,(object)array());
        return $jsonResponse;
    }

    /**
     * Determine add project.
     *
     * @param  \App\Http\Requests\AddProjectApiRequest  $request
     * @return Response
     */
    public function addProject(AddProjectApiRequest $request ){
        $projectDetailObj  = new \App\Project;
        $input = $request->all();
        $input['qrcode'] = mt_rand(1000000, 9999999);
        
        $project = $projectDetailObj->create($input);
        $explode_name = explode(' ',$project->name);
        $qrcode = mt_rand(1000000, 9999999).$explode_name[0].$project->id;
        $project_detail = $projectDetailObj->project_detail($project->id);
        $project_detail->update(['qrcode' => $qrcode]);
        $project->qrcode = $qrcode;
        $projectslabDetailObj = new \App\ProjectDonationSlab;
        $projectImageDetailObj = new \App\ProjectImage;
        $projectImagedefaultDetailObj = new \App\ProjectDefaultImages;
        $projectSettingDetailObj = new \App\ProjectSetting;
        $userSettingDetailObj = new \App\UserSetting;

        if($request->has('images')){
            $i = 0;
            foreach ($input['images'] as $key => $value) {
                $i++;
                $nameofimage = 'project_image'.'_'.$i;
                $image['image'] = General::upload_file($value, $nameofimage, "user_images");
                $image['project_id'] = $project->id;
                $projectImageDetailObj->create($image);
            }
        }
        if($input['donation_slab']){
            $i = 0;
            $donationSlab = json_decode($input['donation_slab'], true);
            foreach ($donationSlab as $key => $value) {
                $i++;
                $project_slab['project_id'] = $project->id;
                $project_slab['amount'] = $value;
                $projectslabDetailObj->create($project_slab);
            }
        }

         if($input['is_project_custom'] == '0'){

        $get_user_detail =  $userSettingDetailObj->userSettingList($input['church_id']);
        $input['BACKGROUND_COLOR'] =config("constants.background_color");
        $input['TRANSITION_POSITION'] =config("constants.transition_position");
        $findproject_default = $projectImagedefaultDetailObj->projectImagesFirst();
        $input['BACKGROUND_IMAGE'] = $findproject_default->image;
        foreach ($get_user_detail as $key => $value) {
            if($value->field_name == 'BACKGROUND_COLOR'){
                $input['BACKGROUND_COLOR'] =$value->field_value;
            }
            if($value->field_name == 'TRANSITION_POSITION'){
                $input['TRANSITION_POSITION'] =$value->field_value;
            }
            if($value->field_name == 'BACKGROUND_IMAGE'){
                $input['BACKGROUND_IMAGE'] = $value->field_value;
            }
        }

        }else{
            $input['BACKGROUND_COLOR'] =$input['background_color'];
            $input['TRANSITION_POSITION'] =$input['transition_position'];
            if($input['is_defaultimage'] == '0'){
            $findproject_default = $projectImagedefaultDetailObj->getprojectImages($input['background_image']);
            $background_images = $findproject_default->image;
            }else{
            $nameofimage = 'project_image_'.uniqid();
            $background_images = General::upload_file($input['background_image'], $nameofimage, "user_images");  
            }
            $input['BACKGROUND_IMAGE'] = $background_images;
        }
        $projectSettingArray = array('BACKGROUND_IMAGE','TRANSITION_POSITION','BACKGROUND_COLOR');
        $projectSettingData['project_id']=$project->id;
        foreach($projectSettingArray as $projectSettingManual){
        $projectSettingData['field_name'] = $projectSettingManual;
        $projectSettingData['field_value'] = $input[$projectSettingManual];
        $projectSettingDetailObj->create($projectSettingData);
        }
         $project = $projectDetailObj->project_detail($project->id);
            foreach ($project->project_settings_detail as $key => $value) {
            if($value->field_name == 'BACKGROUND_IMAGE'){
            $value->field_value = General::get_file_src($value->field_value);
            }
            }
            foreach($project->project_images as $key => $val){
            $val->image =  General::get_file_src($val->image);
            }
       // $project->project_settings_detail = $projectSettingDetailObj->projectSettingList($project->id);
        $message = 'Project has been added successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$project);
        return $jsonResponse;
    }

    /**
     * Determine update project.
     *
     * @param  \App\Http\Requests\EditProjectApiRequest  $request
     * @return Response
     */
    public function editProject(EditProjectApiRequest $request ){
        $projectDetailObj  = new \App\Project;
        $input = $request->all();
        $project = $projectDetailObj->project_detail($input['project_id']);
        $project->update($input);
        $projectslabDetailObj = new \App\ProjectDonationSlab;
        $projectImageDetailObj = new \App\ProjectImage;

        $projectImagedefaultDetailObj = new \App\ProjectDefaultImages;
        $projectSettingDetailObj = new \App\ProjectSetting;
        $userSettingDetailObj = new \App\UserSetting;
        // print_r($project);exit;
        if($request->has('images')){
            $i = 0;
            $projectImage = $projectImageDetailObj->projectImages($input['project_id']);
            foreach($projectImage as $key => $val){
                $projectImageDelete = $projectImageDetailObj->find($val->id);
                if($projectImageDelete)
                    $projectImageDelete->delete();
            }
            foreach ($input['images'] as $key => $value) {
                $i++;
                $nameofimage = 'project_image'.'_'.$i;
                $image['image'] = General::upload_file($value, $nameofimage, "user_images");
                $image['project_id'] = $project->id;
                $projectImageDetailObj->create($image);
            }
        }
        if($input['donation_slab']){
            $i = 0;
            $projectSlab = $projectslabDetailObj->donation_slab($input['project_id']);
            foreach($projectSlab as $key => $val){
                $projectSlabDelete = $projectslabDetailObj->find($val->id);
                if($projectSlabDelete)
                    $projectSlabDelete->delete();
            }
            $donationSlab = json_decode($input['donation_slab'], true);
            foreach ($donationSlab as $key => $value) {
                $i++;
                
                $project_slab['project_id'] = $project->id;
                $project_slab['amount'] = $value;
                $projectslabDetailObj->create($project_slab);
            }
        }

         if($input['is_project_custom'] == '0'){

        $get_user_detail =  $userSettingDetailObj->userSettingList($project->church_id);
        $input['BACKGROUND_COLOR'] =config("constants.background_color");
        $input['TRANSITION_POSITION'] =config("constants.transition_position");
        $findproject_default = $projectImagedefaultDetailObj->projectImagesFirst();
        $input['BACKGROUND_IMAGE'] = $findproject_default->image;
        foreach ($get_user_detail as $key => $value) {
            if($value->field_name == 'BACKGROUND_COLOR'){
                $input['BACKGROUND_COLOR'] =$value->field_value;
            }
            if($value->field_name == 'TRANSITION_POSITION'){
                $input['TRANSITION_POSITION'] =$value->field_value;
            }
            if($value->field_name == 'BACKGROUND_IMAGE'){
                $input['BACKGROUND_IMAGE'] = $value->field_value;
            }
        }

        }else{
            $input['BACKGROUND_COLOR'] =$input['background_color'];
            $input['TRANSITION_POSITION'] =$input['transition_position'];
            if($input['is_defaultimage'] == '0'){
            $findproject_default = $projectImagedefaultDetailObj->getprojectImages($input['background_image']);
            $background_images = $findproject_default->image;
            }else{
                $nameofimage = 'project_image_'.uniqid();
            $background_images = General::upload_file($input['background_image'], $nameofimage, "user_images");  
            }
            $input['BACKGROUND_IMAGE'] = $background_images;
        }
        
        $projectSettingArray = array('BACKGROUND_IMAGE','TRANSITION_POSITION','BACKGROUND_COLOR');
        
        foreach($projectSettingArray as $projectSettingManual){
        $projectSettingData['project_id']=$project->id;
        $projectSettingData['field_name'] = $projectSettingManual;
        $projectSettingData['field_value'] = $input[$projectSettingManual];

        $projectSettingDetailObj->updateprojectSetting($projectSettingData['project_id'],$projectSettingData['field_name'],$projectSettingData['field_value']);
        }

        $project = $projectDetailObj->project_detail($input['project_id']);
        foreach ($project->project_settings_detail as $key => $value) {
            if($value->field_name == 'BACKGROUND_IMAGE'){
                $value->field_value = General::get_file_src($value->field_value);
            }
        }
        foreach($project->project_images as $key => $val){
            $val->image =  General::get_file_src($val->image);
        }
        $message = 'Project has been updated successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$project);
        return $jsonResponse;
    }

    
    /**
     * Determine update project.
     *
     * @param  \App\Http\Requests\ProjectDetailApiRequest  $request
     * @return Response
     */
    public function deleteProject(ProjectDetailApiRequest $request ){
        $projectDetailObj  = new \App\Project;
        $input = $request->all();
        $project = $projectDetailObj->project_detail($input['project_id']);
        $project->update(['is_deleted'=>'1']);
        $donationslabDetailObj = new \App\ProjectDonationSlab;
        $donationSlab = $donationslabDetailObj->donation_slab($project->id);
        if($donationSlab)
            foreach($donationSlab as $donation)
                $donation->update(['is_deleted' => '1']);
        $projectImageDetailObj = new \App\ProjectImage;
        $projectImage = $projectImageDetailObj->projectImages($project->id);
        if($projectImage)
            foreach($projectImage as $imageloop)
                $imageloop->update(['is_deleted' => '1']);
        $projectDonationPaymentDetailObj = new \App\ProjectDonationPayment;
        $projectDonationPaymentList = $projectDonationPaymentDetailObj->projectDonationPayementList($project->id);
        if($projectDonationPaymentList)
            foreach($projectDonationPaymentList as $key => $val1){
                $projectDonationPaymentDetail = $projectDonationPaymentDetailObj->find($val1->id);
                $projectDonationPaymentDetail->update(['is_deleted' => '1']);
            }

        $message = 'Project has been deleted successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$project);
        return $jsonResponse;
    }

    /**
     * Determine fundname list.
     *
     * @param  \App\Http\Requests\FundNameListApiRequest  $request
     * @return Response
     */
    public function fundNameList(FundNameListApiRequest $request ){
        $fundDetailObj  = new \App\ChurchFund;
        $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 0;
        
        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;
        
        $fund_list = $fundDetailObj->churchFundDetailPagination($start,'0',$input['church_id']);
        $fund_listcounts = $fundDetailObj->churchFundDetailPagination($start,'1',$input['church_id']);
        // $user = $userDetailObj->userListPagination($start,$input['church_id'],$input['user_role_id']);
        $next = "false";
        $fundNameListing = array();
        $message = "No data found";
        $fundCount = json_decode(json_encode($fund_list), true);
        if($page == '0'){
            if(!empty($fundCount)){
                $message = 'Fund Name List.';
                $fundNameListing = json_decode(json_encode($fund_list), true);
                $jsonResponse = General::jsonResponse(1,$message,$fundNameListing,$next,'','form');
                return $jsonResponse;
            }else{
                  $message = 'Fund Name List.';
                $fundNameListing = json_decode(json_encode($fund_list), true);
                $jsonResponse = General::jsonResponse(2,$message,$fundNameListing,$next,'','form');
                return $jsonResponse;  
            }
        }
        if(!empty($fundCount)){
            $message = 'Fund Name List.';
             $totalPages = ceil($fund_listcounts['total'] / $perpage);
            if ($totalPages > $page) {
                $next = "true";
                $fundNameListing = json_decode(json_encode($fund_list), true);
                //array_pop($fundNameListing);
            }
            else{
                $fundNameListing = json_decode(json_encode($fund_list), true);
                $next = "false";
            }
            $jsonResponse = General::jsonResponse(1,$message,$fundNameListing,$next,'','form');
            return $jsonResponse;
        }
            $jsonResponse = General::jsonResponse(0,$message,$fundNameListing,$next,'','front_form');
            return $jsonResponse;
    }

    /**
     * Determine add fundname.
     *
     * @param  \App\Http\Requests\AddFundApiRequest  $request
     * @return Response
     */
    public function addFundName(AddFundApiRequest $request ){
        $fundDetailObj  = new \App\ChurchFund;
        $input = $request->all();
        $fund_list = $fundDetailObj->create($input);
        $message = 'Fund has been added successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$fund_list);
        return $jsonResponse;
    }

    /**
     * Determine update fundname.
     *
     * @param  \App\Http\Requests\EditFundApiRequest  $request
     * @return Response
     */
    public function updateFundName(EditFundApiRequest $request ){
        $fundDetailObj  = new \App\ChurchFund;
        $input = $request->all();
        $fund_list = $fundDetailObj->single_fund_detail($input['id']);
        $fund_edit = $fund_list->update($input);
        $fund_list = $fundDetailObj->single_fund_detail($input['id']);
        
        $message = 'Fund has been updated successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,$fund_list);
        return $jsonResponse;
    }

    /**
     * Determine update fundname.
     *
     * @param  \App\Http\Requests\DeleteFundApiRequest  $request
     * @return Response
     */
    public function deleteFundName(DeleteFundApiRequest $request ){
        $fundNameDetailObj  = new \App\ChurchFund;
        $input = $request->all();
        $fundname = $fundNameDetailObj->single_fund_detail($input['id']);
        if($fundname){
            $fundname->update(['is_deleted'=>'1']); 
            $message = 'Fund name has been deleted successfully.';
            $status = 1;
        }
        else{
            $fundname = (object)array();
            $message = 'Fund name is not found.';
            $status = 0;
        }
        $jsonResponse =  General::jsonResponse($status,$message,$fundname);
        return $jsonResponse;
    }

    /**
     * Determine function to show upcoming event lists.
     *
     * @param  \App\Http\Requests\NextEventListApiRequest  $request
     * @return Response
     */
    public function nextEventList(NextEventListApiRequest $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $event = $eventDetailObj->event_detail_datewise($input['church_id'],$input['date']);
        $message = "Event list.";
        $jsonResponse =  General::jsonResponse(1,$message,$event);
        return $jsonResponse;
    }
     /**
     * Determine function to show upcoming event lists.
     *
     * @param  \App\Http\Requests\NextEventListApiRequest  $request
     * @return Response
     */
    public function user_dob_detail(Request $request ){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $today = $input['date'];
        $user_detail = $userDetailObj->dob_detail_datewise($input['church_id'],$input['month']);
        $month_of_date =[];
        $listofbirthdate =(object)[];
        foreach ($user_detail as $key => $value) {
        $value->image =  General::get_file_src($value->image);
        $sec = strtotime($value->dob); 
        $value->with_current_dob = date("Y").'-'.date("m-d", $sec); 
        $month_of_date[] = date("Y").'-'.date("m-d", $sec); 
        }
        
        $month_of_date = array_unique($month_of_date);
        $user_detail = array_values(array_filter($user_detail->toArray(), 
        function ($value) use($today) {  
         return ($value['with_current_dob'] == $today);
        }));
        $listofbirthdate->date = $month_of_date;
        $listofbirthdate->user_detail = $user_detail;

        $message = "Date of birth detail.";
        $jsonResponse =  General::jsonResponse(1,$message,$listofbirthdate,'','','form');
        return $jsonResponse;

       
    }

    /**
     * Determine function to show upcoming event lists.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public function dateEventList(Request $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        if($request->has('start_date')){
            $start_date = $input['start_date'];
            $inStartDate = explode(' ',$start_date);
            $start_date = $inStartDate[0];
        }
        else
            $start_date = '';
        
        if($request->has('end_date')){
            $end_date = $input['end_date'];
            $inEndDate = explode(' ',$end_date);
            $end_date = $inEndDate[0];
        }
        else
            $end_date = '';
        $event = $eventDetailObj->eventDateList($input['church_id'],$start_date,$end_date);
        if($event){
            
            $message = "Event date list.";
            $jsonResponse =  General::jsonResponse(1,$message,$event,'','','form');
            return $jsonResponse;
        }
        else{
            $message = "No data found.";
            $jsonResponse =  General::jsonResponse(0,$message,'','','','form');
            return $jsonResponse;
        }
    }
    /**
     * Determine function to show all events.
     *
     * @param  \App\Http\Requests\NextEventApiRequest  $request
     * @return Response
     */
    public function allEventList(Request $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $date = '';

        
        $event = $eventDetailObj->next_event_detail($input['church_id'],$date);
        $message = "Event list.";
        $jsonResponse =  General::jsonResponse(1,$message,$event,'','','form');
        return $jsonResponse;
    }
    /**
     * Determine function to show upcoming events.
     *
     * @param  \App\Http\Requests\NextEventApiRequest  $request
     * @return Response
     */
    public function nextEvent(NextEventApiRequest $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        if($request->has('date')){
            $date = $input['date'];
            $inDate = explode(' ',$date);
            $date = $inDate[0];
        }
        else
            $date = '';

        
        $event = $eventDetailObj->next_event_detail($input['church_id'],$date);
        $message = "Event list.";
        $jsonResponse =  General::jsonResponse(1,$message,$event,'','','form');
        return $jsonResponse;
    }
    /**
     * Determine add event of church.
     *
     * @param  \App\Http\Requests\AddEventApiRequest  $request
     * @return Response
     */
    public function addEvent(AddEventApiRequest $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $event = $eventDetailObj->create($input);
        $message = "Event has been added successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$event);
        return $jsonResponse;
    }

      /**
     * Determine add event of church.
     *
     * @param  \App\Http\Requests\AddEventApiRequest  $request
     * @return Response
     */
    public function singleEventDetail(Request $request ){
         $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $event = $eventDetailObj->event_detail($input['id']);
        $message = "Event Detail.";
        $jsonResponse =  General::jsonResponse(1,$message,$event);
        return $jsonResponse;
    }

    /**
     * Determine add event of church.
     *
     * @param  \App\Http\Requests\AddEventApiRequest  $request
     * @return Response
     */
    public function updateEvent(AddEventApiRequest $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $event = $eventDetailObj->event_detail($input['id']);
        $event->update($input);
        $event = $eventDetailObj->event_detail($input['id']);
        $message = "Event has been updated successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$event);
        return $jsonResponse;
    }

    /**
     * Determine add event of church.
     *
     * @param  \App\Http\Requests\DeleteEventApiRequest  $request
     * @return Response
     */
    public function deleteEvent(DeleteEventApiRequest $request ){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $event = $eventDetailObj->event_detail($input['id']);
        if($event){
            $event->update(['is_deleted'=>'1']); 
            $message = 'Event has been deleted successfully.';
            $status = 1;
        }
        else{
            $event = (object)array();
            $message = 'Event is not found.';
            $status = 0;
        }
        $jsonResponse =  General::jsonResponse($status,$message,$event);
        return $jsonResponse;
    }

    /**
     * Determine add task to user.
     *
     * @param  \App\Http\Requests\AddTaskApiRequest  $request
     * @return Response
     */
    public function addTask(AddTaskApiRequest $request ){
        $userDetailObj = new \App\User;
        $taskDetailObj  = new \App\Task;
        $taskRecurringDetails  = new \App\TaskRecurring;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $input['start_date'] = $input['date'];
        $task = $taskDetailObj->create($input);
        $input['task_id'] = $task->id;
        $input['recurring_date'] = $input['date'];
        $taskrecurringto = $taskRecurringDetails->create($input);
         
         $member = explode(",",$input['member_id']);
        foreach ($member as $key => $value) {
            $get_task_assign = $UserTaskAssignObj->get_task_assign($value,$task->id);
            if($get_task_assign == 0){
                $input['recurring_id'] = $taskrecurringto->id;
                $input['assign_user_id'] = $value;
                $input['task_id'] = $task->id;
            $taskassign = $UserTaskAssignObj->create($input);
            }
        }

         $group_id = explode(",",$input['group_id']);
         $group = [];
                foreach ($group_id as $key => $values) {
                     $taskgroupmemberDetail = $taskgroupmemberDetailObj->group_member_task_list($values);
                     
                     $valueesds = array();
                     foreach ($taskgroupmemberDetail as $key => $valuees) {
                        $group[] = $valuees->user_id;
                         $get_task_assign = $UserTaskAssignObj->get_task_assign($valuees->user_id,$task->id);
                        if($get_task_assign == 0){
                            $input['recurring_id'] = $taskrecurringto->id;
                            $input['assign_user_id'] = $valuees->user_id;
                            $input['task_id'] = $task->id;
                            $taskassign = $UserTaskAssignObj->create($input);
                        }
                     }
                 
                }
            if($input['is_admin_user']){   
            $is_admin = explode(",",$input['is_admin_user']); 
            foreach ($is_admin as $key => $values) {
            $task_assign_update = $UserTaskAssignObj->get_task_assign_withkey_data($values,$task->id,$taskrecurringto->id);
            $update_detail = $UserTaskAssignObj->find($task_assign_update->id);
            $detail = $update_detail->update(['is_admin'=>'Y']);
            }
           }

            $usersend_notification = array_unique(array_merge($group,$member));
 
            if($usersend_notification[0]){
            
            //$usersend_notification = array_unique($member);
           foreach ($usersend_notification as $key => $value) {
              $userList = $userDetailObj->where('id',$input['user_id'])->where('is_deleted','0')->where('status','active')->first();
               $usersend = $userDetailObj->user_detail_sendnotification($value,$input['church_id']);
               $church_details = $userDetailObj->find($input['church_id']);
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
                $church_detail['from_user_id'] = $input['user_id'];
                $church_detail['church_id'] = $input['church_id'];
                $church_detail['church_name'] = $church_details->firstname;
                $church_detail['referral_id'] = $church_details->referral_id;
              $UserNotificationObj->send_notification($input['user_id'], $value, "create_task_by_church", $church_detail,$input['church_id'],$usersend->user_role_id);
             }
           }
          }
            
        $message = "Task has been added successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$task,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine update task to user.
     *
     * @param  \App\Http\Requests\EditTaskApiRequest  $request
     * @return Response
     */
    public function updateTask(EditTaskApiRequest $request ){
        $taskDetailObj  = new \App\Task;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj = new \App\User;
        $UserNotificationObj  = new \App\UserNotification;
        $taskRecurringDetails  = new \App\TaskRecurring;
        $input = $request->all();
        $input['start_date'] = $input['date'];
        $task = $taskDetailObj->task_detail($input['id']);
        $task->update($input);

        $task_recurring_detailobj = $taskRecurringDetails->task_recurring_detail($input['recurring_id']);
        $input['task_id'] = $input['id'];
        $input['recurring_date'] = $input['date'];
        $task_recurring_detailobj->update($input);

         //$taskgroupmemberDetail = $UserTaskAssignObj->delete_member($input['id']);
        $group = [];
         $group_id = explode(",",$input['group_id']);
        foreach ($group_id as $key => $values) {
             $taskgroupmemberDetail = $taskgroupmemberDetailObj->group_member_task_list($values);
             $valueesds = array();

             foreach ($taskgroupmemberDetail as $key => $valuees) {
                 $get_task_assign = $UserTaskAssignObj->get_task_assign($valuees->user_id,$input['id']);
                 $group[] = $valuees->user_id;
                if($get_task_assign == 0){
                    $input['assign_user_id'] = $valuees->user_id;
                    $input['task_id'] = $input['id'];
                    $taskassign = $UserTaskAssignObj->create($input);
                }
             }
         
        }
               
        $member = explode(",",$input['member_id']);
        foreach ($member as $key => $value) {
            $get_task_assign = $UserTaskAssignObj->get_task_assign($value,$input['id']);
            if($get_task_assign == 0){
                $input['assign_user_id'] = $value;
                $input['task_id'] = $input['id'];
            $taskassign = $UserTaskAssignObj->create($input);
            }
        }
        $task_change = $UserTaskAssignObj->update_task_assign_admin_status($input['id'],$input['recurring_id']);
        if($input['is_admin_user']){
            $is_admin = explode(",",$input['is_admin_user']); 
            foreach ($is_admin as $key => $values) {
                $task_assign_update = $UserTaskAssignObj->get_task_assign_withkey_data($values,$input['id'],$input['recurring_id']);
                $update_detail = $UserTaskAssignObj->find($task_assign_update->id);
                $detail = $update_detail->update(['is_admin'=>'Y']);
            }
        }

        $usersend_notification = array_unique(array_merge($group,$member));
        $taskgroupmemberDetails = $UserTaskAssignObj->delete_member_user($usersend_notification,$input['id']);
         if($usersend_notification[0]){
            foreach ($usersend_notification as $key => $value) {
              $userList = $userDetailObj->where('id',$input['user_id'])->where('is_deleted','0')->where('status','active')->first();
          
          $get_task_assigns = $UserTaskAssignObj->get_task_assign_notification($value,$input['id']);

              if($get_task_assigns->status != 'reject'){
                 
              $usersend = $userDetailObj->user_detail_sendnotification($value,$input['church_id']);
              if($usersend){
                  $church_details = $userDetailObj->find($input['church_id']);
                  $church_detail = array();
                  $church_detail['task_id'] = $task->id;
                  $church_detail['recurring_id'] = $input['recurring_id'];
                  $church_detail['task_title'] = $task->title;
                  $church_detail['task_description'] = $task->description;
                  $church_detail['from_user_id'] = $input['user_id'];
                  $church_detail['church_name'] = ucfirst($userList->firstname);
                  $church_detail['email'] = $userList->email;
                  $church_detail['user_role_id'] = $usersend->user_role_id;
                  $church_detail['mobile'] = $userList->mobile;
                  $church_detail['image'] = $userList->image;
                  $church_detail['church_id'] = $input['church_id'];
                  $church_detail['church_name'] = $church_details->firstname;
                  $church_detail['referral_id'] = $church_details->referral_id;
                  $UserNotificationObj->send_notification($input['user_id'], $value, "update_task_by_church", $church_detail,$input['church_id'],$usersend->user_role_id);
             }
         }
           }
        }
        $message = "Task has been updated successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$task,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine delete task to user.
     *
     * @param  \App\Http\Requests\DeleteTaskApiRequest  $request
     * @return Response
     */
    public function deleteTask(DeleteTaskApiRequest $request ){
        $taskDetailObj  = new \App\Task;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $notificationDetailObj = new \App\UserNotification;
        $taskRecurringDetails  = new \App\TaskRecurring;
        $input = $request->all();
        $task = $taskDetailObj->task_detail($input['id']);
        if($task){
            $task->update(['is_deleted'=>'1']); 
            $taskRecurringDetails->task_recurring_delete($input['id']);
            $UserTaskAssignObj->delete_member($input['id']);
            $notificationDetailObj->delete_notification($input['id']);
            
            $message = 'Task has been deleted successfully.';
            $status = 1;
        }
        else{
            $task = (object)array();
            $message = 'Task is not found.';
            $status = 0;
        }
        $jsonResponse =  General::jsonResponse($status,$message,$task);
        return $jsonResponse;
    }

    /**
     * Determine task list of user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public static function taskList(UserIdApiRequest $request){
        $taskDetailObj = new \App\Task;
        $userDetailObj = new \App\User;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $input = $request->all();
        $task = $taskDetailObj->task_list($input['user_id']);

        foreach ($task as $key => $value) {
            foreach ($value->user_task_detail as $key => $values) {
                $find_status = $UserTaskAssignObj->count_complition($value->id,$values->id);
                  $countotal  = $UserTaskAssignObj->count_totalassign('1',$value->id,$values->id);
            $outcountotal  = $UserTaskAssignObj->count_totalassign('2',$value->id,$values->id);
            $values->outof = $outcountotal.' of '.$countotal;
                if($find_status > 0){
                    $values->status = 'pending';
                }else{
                    $values->status = 'complete';
                }
            }
            
            $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }


            $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                    $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }

            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
                foreach ($member_id as $key => $valuess) {
                    $memberdata[] =  intval($valuess);
                }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }

            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                    $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }
        }
        $message = 'Task Listing.';
        $jsonResponse =  General::jsonResponse(1,$message,$task,'','','form');
        return $jsonResponse;
    }
      /**
     * Determine task list of user.
     *
     * @param  \App\Http\Requests\UserIdApiRequest  $request
     * @return Response
     */
    public static function taskListDatewise(Request $request){
        $taskDetailObj = new \App\Task;
        $userDetailObj = new \App\User;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $input = $request->all();
        $task = $taskDetailObj->taskListUpcoming($input['church_id']);
        $dashboardDetail = array();
        foreach($task as $key=>$value){
             foreach ($value->user_task_detail as $key => $values) {
                $find_status = $UserTaskAssignObj->count_complition($value->id,$values->id);
                $countotal  = $UserTaskAssignObj->count_totalassign('1',$value->id,$values->id);
                $outcountotal  = $UserTaskAssignObj->count_totalassign('2',$value->id,$values->id);
                $values->outof = $outcountotal.' of '.$countotal;
                if($find_status > 0){
                    $values->status = 'pending';
                }else{
                    $values->status = 'complete';
                }
                
             }
            
            $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }
            $date = explode(' ',$value->date);
            //echo ''.date('Y-m-d',strtotime($date[0])).' ==='. date('Y-m-d');
            if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d'))
                $dashboardDetail['today'][]=$value;
            else if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d', strtotime(' +1 day')))
                $dashboardDetail['tomorrow'][]=$value;
            else if(date('Y-m-d',strtotime($date[0])) > date('Y-m-d', strtotime(' +1 day')))
                $dashboardDetail['upcoming'][]=$value;
   

            $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                    $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }

            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
                foreach ($member_id as $key => $valuess) {
                    $memberdata[] =  intval($valuess);
                }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }

            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                    $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }
           
        }

        $message = 'Task Listing.';
        $jsonResponse =  General::jsonResponse(1,$message,$dashboardDetail,'','','form');
        return $jsonResponse;
    }   

    /**
     * Calculating total fund of project related church.
     *
     * @param  \App\Http\Requests\ProjectDetailApiRequest  $request
     * @return Response
     */
    public static function totalFundCollected(ProjectDetailApiRequest $request){
        $fundDetailObj = new \App\ProjectDonationPayment;
        $input = $request->all();
        $fund = $fundDetailObj->total_fund_collection($input['project_id']);
        $message = 'Total fund collected.';
        $jsonResponse =  General::jsonResponse(1,$message,$fund,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine donation slabs to Project donation.
     *
     * @param  \App\Http\Requests\ProjectDonationslabsApiRequest  $request
     * @return Response
     */
    public static function donationSlabsList(Request $request){
        $donationDetailObj = new \App\ProjectDonationSlab;
        $input = $request->all();
        $donation = $donationDetailObj->donation_slab($input['project_id']);
        $message = 'Donation list.';
        $jsonResponse =  General::jsonResponse(1,$message,$donation,'','','form');
        return $jsonResponse;
    }

    public static function update_read_notification(Request $request){
        $notificationDetailObj = new \App\UserNotification;
        $input = $request->all();
        $notification = $notificationDetailObj->update_notification_read($input['id']);
          
        $message = 'Update Notification Successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,[],'','','form');
        return $jsonResponse;
        
    }
    public static function notificationList(Request $request){
        $notificationDetailObj = new \App\UserNotification;
        $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;  

        $notification = $notificationDetailObj->notification_list($start,'0',$input['user_id']);
        $notificationcounts = $notificationDetailObj->notification_list($start,'1',$input['user_id']);
         $totalPages = ceil($notificationcounts['total'] / $perpage);
        if ($totalPages > $page) {
            $next = "true";
            //array_pop($notification);
        }else{
            $next = "false";
        }
         $notification_type_data = config("constants.notification_type_data");

        foreach ($notification as $value) {
             $value->extra_data = json_decode($value->extra_data);
              $push_notification_data = collect($notification_type_data[$value->type])
            ->put(
            "extra_data",
            collect($value->extra_data)
            ->mapWithKeys(function ($value, $key) { 
            return ["##" . strtoupper($key) . "##" => $value];
            })
            ->all()
            )
            ->all();
            $search = array_keys($push_notification_data["extra_data"]);
            $replace = array_values($push_notification_data["extra_data"]);
            $value->message = str_replace($search, $replace, $push_notification_data["body"]);
        }

       if(!empty($notification[0])){
            $message = 'Notification list.';
            $jsonResponse =  General::jsonResponse(1,$message,$notification,$next,'','form');
            return $jsonResponse;
        }else{
            $message = 'Notification history not found.';
            $errorData = array();
            $errorData['church_id'] = array('Notification history not found.');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
        }
    }

    /**
     * Determine function to delete notification of users.
     *
     * @param  \App\Http\Requests\DeleteNotificationApiRequest  $request
     * @return Response
     */
    public static function deleteNotification(DeleteNotificationApiRequest $request){
        $notificationDetailObj = new \App\UserNotification;
        $input = $request->all();
        $notification = $notificationDetailObj->notification_detail($input['id']);
        if($notification){
            $notification->delete();
            $message = 'Notification has been deleted successfully.';
        }
        else{
            $notification = (object)array();
            $message = "Notification is not available";
        }
        $jsonResponse =  General::jsonResponse(1,$message,$notification,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public static function notificationOptionList(Request $request){
        $notificationDetailObj = new \App\NotificationOption;
        $notificationOption = $notificationDetailObj->notification_option_list();
        $message = 'Notification option list.';
        $jsonResponse =  General::jsonResponse(1,$message,$notificationOption);
        return $jsonResponse;
    }

    /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\ForgetPasswordRequest  $request
     * @return Response
     */
    public static function forgetPassword(ForgetPasswordRequest $request){
        $userDetailObj = new \App\User;
        $input = $request->all();
        $user = $userDetailObj->userCheckEmailForgot($input['email'],$input['user_role_id']);
        if($user){
            if($user->status != 'active'){
                $message = 'Your account has been block.Please contact administrator.';
                $jsonResponse =  General::jsonResponse(0,$message); 
                return $jsonResponse;
            }
          
            $new_password = 'gtg'.mt_rand(100000, 999999);
            $user->update(['password' => bcrypt($new_password)]);
            $user->image =  General::get_file_src($user->image);
            $text = 'Hello ' . $user->firstname . ' ' . $user->lastname . ',';
            $text .= '<br><br> Your login password is : ' . $new_password;
            $text .= '<p>We advise that you change your password immediately for security purposes.</p>'; 
            Mail::send([], [], function($message) use ($user, $text) {
                $message->subject('Good to Give Password Reset Request');
                $message->from('admin@yopmail.com', 'GTG');
                $message->to($user->email);
                $message->setBody($text, 'text/html');
                //echo "Thanks! Your request has been sent to " . $user->email;
            });
            $message = 'Password has been sent in your email address.';
        }
        else{
            $user = (object)array();
            $message = "Your email address is incorrect.";
            $jsonResponse =  General::jsonResponse(2,$message,$user,'','','form');
            return $jsonResponse;
        }
        $jsonResponse =  General::jsonResponse(1,$message,$user,'','','form');
        
        return $jsonResponse;
    }

    /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function taskPriority(Request $request){
        $taskPriorityDetailObj = new \App\TaskPriority;
        
        $taskPriority = $taskPriorityDetailObj->all();
        $message = 'Task Priority List.';
        $jsonResponse =  General::jsonResponse(1,$message,$taskPriority,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine church dashboard .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function churchDashboard(Request $request){        
        $input = $request->all();        
        $dashboardDetail = array();
        $userDetailObj = new \App\User;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $globalSettingDetailObj = new \App\SiteGeneralSetting;
        $globalSetting = $globalSettingDetailObj->SiteGeneralSetting('member');
        $extraDetail = array();
        if($globalSetting->option_value != '' && $globalSetting->option_value != 'disable'){
            $user = $userDetailObj->userListDashboard($input['church_id']);
            $usercount = json_decode(json_encode($user), true);
            $extraDetail[] = array('widget_name'=>'Members','widget_value'=>count($usercount));
        }
        $globalSetting = $globalSettingDetailObj->SiteGeneralSetting('total_project');
        if($globalSetting->option_value != '' && $globalSetting->option_value != 'disable'){
            $projectDetailObj = new \App\Project;
            $project = $projectDetailObj->project_list($input['church_id']);
            $projectcount = json_decode(json_encode($project), true);
            $extraDetail[] = array('widget_name'=>'Total Projects','widget_value'=>count($projectcount));
        }
        $dashboardDetail['widget'] = $extraDetail;

        $userNotificationDetailObj = new \App\UserNotification;
         $notificationDetailUserCount = $userNotificationDetailObj->notificationDetailUserCount($input['user_id']);
        $dashboardDetail['notification_count'] = $notificationDetailUserCount;


        $userNotification = $userNotificationDetailObj->notificationDetailUser($input['user_id']);
        $notification_type_data = config("constants.notification_type_data");
        foreach ($userNotification as $value) {
             $value->extra_data = json_decode($value->extra_data);
              $push_notification_data = collect($notification_type_data[$value->type])
            ->put(
            "extra_data",
            collect($value->extra_data)
            ->mapWithKeys(function ($value, $key) { 
            return ["##" . strtoupper($key) . "##" => $value];
            })
            ->all()
            )
            ->all();
            $search = array_keys($push_notification_data["extra_data"]);
            $replace = array_values($push_notification_data["extra_data"]);
            $value->message = str_replace($search, $replace, $push_notification_data["body"]);
        }
        $dashboardDetail['notifications'] = $userNotification;


        
        $eventDetailObj = new \App\Event;
        $event = $eventDetailObj->upcoming_event($input['church_id'],date('Y-m-d'));
        $dashboardDetail['next_event'] = $event;
        
        $projectDonationPaymentDetailObj = new \App\ProjectDonationPayment;
        $projectDonationPayment = $projectDonationPaymentDetailObj->fundAmount($input['church_id']);
        $dashboardDetail['total_donation'] = $projectDonationPayment;

        $taskDetailObj = new \App\Task;

        if($input['role_id'] == '4'){
             $task_list = $taskDetailObj->get_task_list_dashboard(0,$input['user_id']);

             foreach ($task_list as $key => $value) {
         $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }
         $date = explode(' ',$value->date);
        if($value->user_creater_id != $input['user_id']){
            $get_task_assign = $UserTaskAssignObj->single_get_task_assign_list($value->id,$input['user_id']);
            $value->user_task_detail = $get_task_assign; 
        }else{
        $task = $taskDetailObj->taskListDisplaypastor($value->id,$input['user_id']);
        foreach ($task->user_task_detail as $key => $values) {
            $find_status = $UserTaskAssignObj->count_complition($value->id,$values->id);
            $countotal  = $UserTaskAssignObj->count_totalassign('1',$value->id,$values->id);
            $outcountotal  = $UserTaskAssignObj->count_totalassign('2',$value->id,$values->id);
            $values->outof = $outcountotal.' of '.$countotal;
            if($find_status > 0){
                $values->status = 'pending';
            }else{
                $values->status = 'complete';
            }
        }
        $value->user_task_detail = $task->user_task_detail; 
        }
        
         $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                    $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }

            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
                foreach ($member_id as $key => $valuess) {
                    $memberdata[] =  intval($valuess);
                }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }

            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                    $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }
  
         if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d') && $value->user_status != 'reject')
            $dashboardDetail['tasks']['today'][]=$value;
        /*else if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d', strtotime(' +1 day')))
        $dashboardDetail['tasks']['tomorrow'][]=$value;
          else if(date('Y-m-d',strtotime($date[0])) > date('Y-m-d', strtotime(' +1 day')))
        $dashboardDetail['tasks']['upcoming'][]=$value;*/
          }

        }else{

        $task = $taskDetailObj->taskListDisplay($input['church_id']);
        $i = 0;
        foreach($task as $key=>$value){
            $i ++;
            // if($i <= 4){
            foreach ($value->user_task_detail as $key => $values) {
                $find_status = $UserTaskAssignObj->count_complition($value->id,$values->id);
                $countotal  = $UserTaskAssignObj->count_totalassign('1',$value->id,$values->id);
                $outcountotal  = $UserTaskAssignObj->count_totalassign('2',$value->id,$values->id);
                $values->outof = $outcountotal.' of '.$countotal;
                if($find_status > 0){
                    $values->status = 'pending';
                }else{
                    $values->status = 'complete';
                }
            }
            $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }
            $date = explode(' ',$value->date);
            // echo ''.date('Y-m-d',strtotime($date[0])).' ==='. date('Y-m-d');
            if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d'))
                $dashboardDetail['tasks']['today'][]=$value;
            //else if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d', strtotime(' +1 day')))
               // $dashboardDetail['tasks']['tomorrow'][]=$value;
            //else if(date('Y-m-d',strtotime($date[0])) > date('Y-m-d', strtotime(' +1 day')))
             //   $dashboardDetail['tasks']['upcoming'][]=$value;

            $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                    $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }

            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
                foreach ($member_id as $key => $valuess) {
                    $memberdata[] =  intval($valuess);
                }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }

            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                    $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }
        // }
        }
    }
        $message = 'Church Dashboard.';
        $jsonResponse =  General::jsonResponse(1,$message,$dashboardDetail,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine donor dashboard .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function donorDashboard(Request $request){   
        $input = $request->all();        
        $dashboardDetail = array();
        $userDetailObj = new \App\User;
        $taskDetailObj  = new \App\Task;
         $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $user = $userDetailObj->find($input['id']);
        $userNotificationDetailObj = new \App\UserNotification;
        $userNotification = $userNotificationDetailObj->notificationDetailUser($input['id']);

        $notification_type_data = config("constants.notification_type_data");
        foreach ($userNotification as $value) {
            $value->extra_data = json_decode($value->extra_data);
            $push_notification_data = collect($notification_type_data[$value->type])
            ->put(
            "extra_data",
            collect($value->extra_data)
            ->mapWithKeys(function ($value, $key) { 
            return ["##" . strtoupper($key) . "##" => $value];
            })
            ->all()
            )
            ->all();
            $search = array_keys($push_notification_data["extra_data"]);
            $replace = array_values($push_notification_data["extra_data"]);
            $value->message = str_replace($search, $replace, $push_notification_data["body"]);
        }
        $notificationDetailUserCount = $userNotificationDetailObj->notificationDetailUserCount($input['id']);
        $dashboardDetail['notification_count'] = $notificationDetailUserCount;
        if($userNotification)
            $dashboardDetail['notifications'] = $userNotification;
        else
            $dashboardDetail['notifications'] = '';

        $eventDetailObj = new \App\Event;
        //$event = $eventDetailObj->upcoming_event($user->church_id,date('Y-m-d'));
        $event = $eventDetailObj->upcoming_event($input['church_id'],date('Y-m-d'));
        $dashboardDetail['next_event'] = $event;
        //$churchScripture = $userDetailObj->userDetail($user->church_id);
        $churchScripture = $userDetailObj->userDetail($input['church_id']);
        $dashboardDetail['scripture'] = $churchScripture->scripture;
        $task_list = $taskDetailObj->get_task_list_dashboard(0,$input['id']);

    foreach ($task_list as $key => $value) {
         $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }
         $date = explode(' ',$value->date);
        
        $get_task_assign = $UserTaskAssignObj->single_get_task_assign_list($value->id,$input['id']);
        $value->user_task_detail = $get_task_assign; 
         $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                    $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }

            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
                foreach ($member_id as $key => $valuess) {
                    $memberdata[] =  intval($valuess);
                }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }

            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                    $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }
  
         if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d') && count($get_task_assign) > 0)
            $dashboardDetail['task']['today'][]=$value;
        /*else if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d', strtotime(' +1 day')))
        $dashboardDetail['task']['tomorrow'][]=$value;
          else if(date('Y-m-d',strtotime($date[0])) > date('Y-m-d', strtotime(' +1 day')))
        $dashboardDetail['task']['upcoming'][]=$value;*/
    }
        $message = 'User Dashboard.';
        $jsonResponse =  General::jsonResponse(1,$message,$dashboardDetail,'','','form');
        return $jsonResponse;
    }

    /**
     * Determine donor dashboard .
     *
     * @param  \App\Http\Requests\ReferralIdRequest  $request
     * @return Response
     */
    public static function churchReferral(ReferralIdApiRequest $request){        
        $input = $request->all();
        $userDetailObj = new \App\User;
        $user = $userDetailObj->adminChurchReferral($input['referral_id']);
        $referralArray = array();
        $message = 'church referral.';
        if($user){
            $referralArray['referral'] = true;
            $jsonResponse =  General::jsonResponse(1,$message,$referralArray,'','','form');
        }
        else{
            $referralArray['referral'] = false;
            $jsonResponse =  General::jsonResponse(2,$message,$referralArray,'','','form');

        }
        return $jsonResponse;
    }
     /**
     * Add Update event Calendar Id.
     *
     * @param  \App\Http\Requests\AddEventApiRequest  $request
     * @return Response
     */
    public function updateEventCalendarId(Request $request){
        $eventDetailObj  = new \App\Event;
        $input = $request->all();
        $event = $eventDetailObj->event_detail($input['id']);
        $event->update($input);
        $event = $eventDetailObj->event_detail($input['id']);
        $message = "Event Calendar has been updated successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$event);
        return $jsonResponse;
    }

     /**
     * Add Update sage pay save card.
     *
     * @param  \App\Http\Requests\SavecardApiRequest  $request
     * @return Response
     */

    public function savecard(Request $request){

        $input = $request->all();
        $card_holder_name = (!empty($input['card_holder_name']) ? $input['card_holder_name'] : '');
        $userDetailObj  = new \App\User;
        $userdetail = $userDetailObj->userDetail($input['user_id']);
        $gateway = OmniPay::create(config("constants.sagepay_gateway_server"));
        $gateway->setVendor(config("constants.sagepay_vendor_id"));
        $gateway->setTestMode(config("constants.sagepay_test_mode"));
        $UserCardInfo  = new \App\UserCardInfo;
        $userSettingDetailObj = new \App\UserSetting;
        $userUpdateSetting = $userSettingDetailObj->userSetting($input['user_id'],'CVV_CARD');
        
        $security_card = 'XXXX-XXXX-XXXX-'.substr($input['number'],-4);
        $checkcard = $UserCardInfo->checkcard_detail_with_card($input['user_id'],$security_card); 
        
          if($checkcard){
                $message = 'You can not update same card.';
                $errorData = array();
                $errorData['church_id'] = array($message);
                $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
                return $jsonResponse;
          }  
        
        $UserCardInfo_detail = $UserCardInfo->checkcard_detail($input['user_id']); 
         

        if(!empty($UserCardInfo_detail)){
            $getreferrer = $UserCardInfo->find($UserCardInfo_detail->id);
            $getreferrer->delete();
        }

        $card = new CreditCard([
            'firstName' => $userdetail->firstname,
            'lastName' => $userdetail->lastname,
            'number' => $input['number'],
            'expiryMonth' => $input['expiryMonth'],
            'expiryYear' => $input['expiryYear'],
            'cvv' => $input['cvv']
        ]);
       
        $request = $gateway->createCard([
            'currency' => 'GBP',
            'card' => $card,
            'txtypes'        => array(
                'txtype' => 'DEFERRED',
            ),
        ]);
        $response = $request->send();

         if ($response->isSuccessful()) {
                
            $cardReference = $response->getCardReference();
            $token = $response->getToken();
            $carddetail['user_id'] = $input['user_id'];
            $carddetail['card_holder'] = $card_holder_name;
            $carddetail['field_name'] = 'Token';
            $carddetail['field_value'] =$cardReference;
            $carddetail['card_number'] =$security_card;
            if($userUpdateSetting && $userUpdateSetting->field_value == 'enable'){
                $carddetail['cvv'] =$input['cvv']; 
            }
            $carddetail['created_at'] = NOW();
            $UserCardInfo = $UserCardInfo->create($carddetail); 

            $UserAddress = new \App\UserAddress;
            $address = array();
            $address['user_id'] = $input['user_id']; 
            $address['billingAddress1'] = $input['billingAddress1']; 
            $address['billingAddress2'] = $input['billingAddress2']; 
            $address['billingState'] = $input['billingState']; 
            $address['billingCity'] = $input['billingCity']; 
            $address['billingPostcode'] = $input['billingPostcode']; 

            $useraddressdetail = $UserAddress->update_address($input['user_id']);
            if($useraddressdetail){
                $useraddressupdate = $UserAddress->user_address_detail($useraddressdetail->id);
                $useraddressupdate->update($input);
            }else{
                $useraddress = $UserAddress->create($address); 
            }
            
            $UserCardInfoDetailObj  = new \App\UserCardInfo;
            $userDetailObj  = new \App\User;
            $usercardinfo_detail = $UserCardInfoDetailObj->userWiseCard($input['user_id']);
            foreach ($usercardinfo_detail as $key => $value) {
                if($value->cvv){
                    $value->cvv = true;
                }else{
                    $value->cvv = false;
                }
                $userChurch = $userDetailObj->userDetail($value->user_id);
            }

            $message = "Card has been saved successfully.";
            $jsonResponse =  General::jsonResponse(1,$message,$usercardinfo_detail);
            return $jsonResponse;

        } elseif ($response->isRedirect()) {
            $message = "Invalid card detail";
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
        } else {
            //$message = explode(": ", $response->getMessage());
            $message = "Invalid card detail";
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
        }
       
    }

     /**
     * Add Update sage pay get card.
     *
     * @param  \App\Http\Requests\GetApiRequest  $request
     * @return Response
     */

    public function getcarddetail(Request $request){
            $input = $request->all();
            $UserCardInfoDetailObj  = new \App\UserCardInfo;
            $userDetailObj  = new \App\User;
            $UserAddress = new \App\UserAddress;
           
            $usercardinfo_detail = $UserCardInfoDetailObj->userWiseCard($input['user_id']);
            foreach ($usercardinfo_detail as $key => $value) {
                $userChurch = $userDetailObj->userDetail($value->user_id);
                $value->address_detail = $UserAddress->get_update_address($value->user_id);
                if($value->cvv){
                    $value->cvv = true;
                }else{
                    $value->cvv = false;
                }
            }
            $message = "Get card detail successfully.";
            $jsonResponse =  General::jsonResponse(1,$message,$usercardinfo_detail);
            return $jsonResponse;
            
    }

      /**
     * Add Update sage Payment process.
     *
     * @param  \App\Http\Requests\PaymentprocessApiRequest  $request
     * @return Response
     */

    public function paymentprocess(Request $request){

        $userDetailObj  = new \App\User;
        $UserAddress = new \App\UserAddress;
        $UserCardInfo  = new \App\UserCardInfo;
        $projectDetailObj = new \App\Project;
        $referrarDetailObj  = new \App\Referrar;
        $projectDonationPayments  = new \App\ProjectDonationPayment;
        $input = $request->all();
       

        $gateway_server = config("constants.sagepay_gateway_server");
        $gateway = OmniPay::create($gateway_server);
        $transactionId = 'GTG-' . rand(10000000, 99999999);
        $admintransactionId = 'ADM-' . rand(10000000, 99999999);
        $_SESSION['transactionId'] = $transactionId;
        $userChurch = $userDetailObj->userDetail($input['user_id']);
        $useraddressdetail = $UserAddress->update_address($input['user_id']);
         $userSettingDetailObj = new \App\UserSetting;
        $userUpdateSetting = $userSettingDetailObj->userSetting($input['user_id'],'CVV_CARD');
        $checkcard = $UserCardInfo->get_card_cvv($input['user_id'],$input['cardReference']); 
        
              /* Find Percentage*/

        $admin_percentage = config("constants.sagepay_admin_percentage");
        $donation_amount = $input['amount'];
        $pesantage_catlog = $admin_percentage / 100;
        $admin_percentage_retrieve = $pesantage_catlog * $donation_amount;
        $church_donation = $donation_amount - $admin_percentage_retrieve;

               /*Get church detail */
        
        $project = $projectDetailObj->project_detail($input['project_id']);

        if($project){

            $referrarDetail= $referrarDetailObj->referrar_detail($project->church_id);

        if($referrarDetail){

            $church_referrer_Id = $referrarDetail->referrer_Id;
            $church_vendor_id = $referrarDetail->vendor_id;

        }else{

            $church_referrer_Id = '';
            $church_vendor_id = '';    

        }
        }else{

            $church_referrer_Id = '';
            $church_vendor_id = '';     

        }
            /* User address detail */

        if($useraddressdetail){

            $billingAddress1 = $useraddressdetail->billingAddress1;
            $billingAddress2 =     $useraddressdetail->billingAddress2;
            $billingState =     $useraddressdetail->billingState;
            $billingCity =     $useraddressdetail->billingCity;
            $billingPostcode =     $useraddressdetail->billingPostcode;
            $billingCountry =     $useraddressdetail->billingCountry;

        }else{

            $billingAddress1 = '';
            $billingAddress2 =  '';
            $billingState =   '';
            $billingCity =   '';
            $billingPostcode =  '';
            $billingCountry =  'GB';

        }
        

        if($input['cardReference']){
           if($userUpdateSetting && $userUpdateSetting->field_value == 'enable'){
            if($checkcard->cvv){
               $cvv = $checkcard->cvv;
            }else{
               $cvv = $input['cvv'];     
            }
            }else{
            $cvv = $input['cvv'];
            }
            $card = new CreditCard([
            'firstName' => $userChurch->firstname,
            'lastName' => $userChurch->lastname,
            'email' => $userChurch->email,
            'CVV' => $cvv,
            'billingAddress1' => $billingAddress1,
            'billingAddress2' => $billingAddress2,
            'billingState' => $billingState,
            'billingCity' => $billingCity,
            'billingPostcode' => $billingPostcode,
            'billingCountry' => $billingCountry,
            'shippingAddress1' => $billingAddress1,
            'shippingAddress2' => $billingAddress2,
            'billingState' => $billingState,
            'shippingCity' => $billingCity,
            'shippingPostcode' => $billingPostcode,
            'shippingCountry' => $billingCountry,
            ]);

        }else{

            $card = new CreditCard([
            'firstName' => $userChurch->firstname,
            'lastName' => $userChurch->lastname,
            'email' => $userChurch->email,
            'number' => $input['number'],
            'expiryMonth' => $input['expiryMonth'],
            'expiryYear' => $input['expiryYear'],
            'CVV' => $input['cvv'],
            'billingAddress1' => $billingAddress1,
            'billingAddress2' => $billingAddress2,
            'billingState' => $billingState,
            'billingCity' => $billingCity,
            'billingPostcode' => $billingPostcode,
            'billingCountry' => $billingCountry,
            'shippingAddress1' => $billingAddress1,
            'shippingAddress2' => $billingAddress2,
            'billingState' => $billingState,
            'shippingCity' => $billingCity,
            'shippingPostcode' => $billingPostcode,
            'shippingCountry' => $billingCountry,
            ]);

        }

        if ($gateway_server == 'SagePay\Direct' || $gateway_server == 'SagePay\Server') {

            $gateway = OmniPay::create($gateway_server)
            ->setVendor(config("constants.sagepay_vendor_id"))
            ->setTestMode(config("constants.sagepay_test_mode"))
            ->setReferrerId(config("constants.sagepay_Referrer_id"));

            $church_gateway = OmniPay::create($gateway_server)
            ->setVendor($church_vendor_id)
            ->setTestMode(config("constants.sagepay_test_mode"))
            ->setReferrerId($church_referrer_Id);

        } elseif ($gateway_server == 'AuthorizeNet_SIM' || $gateway_server == 'AuthorizeNet_DPM') {

            $gateway = OmniPay::create($gateway_server)
            ->setApiLoginId(getenv('API_LOGIN_ID'))
            ->setTransactionKey(getenv('TRANSACTION_KEY'))
            ->setHashSecret(getenv('HASH_SECRET'))
            ->setTestMode(true)
            ->setDeveloperMode(true);

        }
        $number = $card->getNumber();
        $detail = Helper::validateLuhn($number);
        if(empty($detail)){
            $message = 'Card number is invalid.';
            $errorData = array();
            $errorData['card_id'] = array('Card number is invalid.');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
            
        }

        if($input['cardReference']){

            $requestMessage = $church_gateway->purchase([
            'amount' => $church_donation,
            'currency' => $input['currency'],
            'card' => $card,
            'transactionId' => $transactionId,
            'description' => $input['description'],
            'cardReference' => $input['cardReference'],
            'token' => $input['cardReference'],
            'useAuthenticate' => false,
            'returnUrl' => 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
            'notifyUrl' => 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
            'redirectUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
            'redirectionUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
            'notificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
            'serverNotificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
            ]);

        }else{

            $requestMessage = $church_gateway->purchase([
            'amount' => $church_donation,
            'currency' => $input['currency'],
            'card' => $card,
            'transactionId' => $transactionId,
            'description' => $input['description'],
            'cardReference' => $input['cardReference'],
            'token' => $input['cardReference'],
            'useAuthenticate' => false,
            'returnUrl' => 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
            'notifyUrl' => 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
            'redirectUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
            'redirectionUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
            'notificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
            'serverNotificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
            ]);
        }
         $responseMessage = $requestMessage->send();

        if ($responseMessage->isSuccessful()) {
           
            if($input['cardReference']){

                $requestMessagechurch = $gateway->purchase([
                'amount' => $admin_percentage_retrieve,
                'currency' => $input['currency'],
                'card' => $card,
                'transactionId' => $admintransactionId,
                'description' => $input['description'],
                'cardReference' => $input['cardReference'],
                'token' => $input['cardReference'],
                'useAuthenticate' => false,
                'returnUrl' => 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
                'notifyUrl' => 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
                'redirectUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
                'redirectionUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
                'notificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
                'serverNotificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
                ]);

            }else{

                $requestMessagechurch = $gateway->purchase([
                'amount' => $admin_percentage_retrieve,
                'currency' => $input['currency'],
                'card' => $card,
                'transactionId' => $transactionId,
                'description' => $input['description'],
                'returnUrl' => 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
                'notifyUrl' => 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
                'redirectUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
                'redirectionUrl'=> 'https://churchapp.goodtogive.co.uk/sagepay_confirm2',
                'notificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
                'serverNotificationUrl'=> 'https://churchapp.goodtogive.co.uk/authorizenet_confirm2',
                ]);

            }
            $responseMessagechurch = $requestMessagechurch->send();

            $ProjectDonationPayments  = new \App\ProjectDonationPayment;
            $projectDetailObj  = new \App\Project;
            $project = $projectDetailObj->find($input['project_id']);
            
            $paymentdetail['church_id'] = $project->church_id;
            $paymentdetail['user_id'] = $input['user_id'];
            $paymentdetail['project_id'] = $input['project_id'];
            $paymentdetail['amount'] = $church_donation;
            $paymentdetail['payment_status'] = 'completed';
            $paymentdetail['payment_gateway_type'] = 'sagepay';
            $paymentdetail['payment_gateway_response'] = $responseMessage->getTransactionReference();
            $paymentdetail['payment_transaction_id'] = $transactionId;
            $paymentdetail['qr_scanned_verified'] = $input['qr_scanned_verified'];
            $paymentdetail['admin_commission'] = $admin_percentage_retrieve;
            $paymentdetail['transaction_date'] = date('Y-m-d');
            $paymentdetail['user_role_id'] = $input['user_role_id'];
            $paymentdetail['created_at'] = NOW();
            
            $ProjectDonationPayments = $ProjectDonationPayments->create($paymentdetail); 

            if($input['declaration_id'] == '1'){
                $donation = $projectDonationPayments->finduserProject($input['user_id']);
            }else if($input['declaration_id'] == '2' || $input['declaration_id'] == '3'){
                $donation = $projectDonationPayments->finduserProject($input['user_id']);
                $donation = $projectDonationPayments->addgiftdeclaration($input['user_id'],$input['project_id']);
            }

            if($input['is_repeated'] == 'Y'){
                $projectRepeatDonation  = new \App\ProjectRepeatDonation;
                if($request->has('repeat_type')){
                $input['repeat_type'] = $input['repeat_type'];
                }else{
                $input['repeat_type'] = 1;
                }
                $input['transactionReference'] = $responseMessage->getTransactionReference();
                $input['repeat_start_date'] = NOW();
                $input['repeat_date'] = NOW();
                $projectRepeatDonation = $projectRepeatDonation->create($input);

            }

            $message = "Transaction has been successfully.";
            $jsonResponse =  General::jsonResponse(1,$message,[]);
            return $jsonResponse;

        } elseif ($responseMessage->isRedirect()) {

            $message = "Transaction has been failed.";
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;

        } else {
            $message = $responseMessage->getMessage();
            $errorData = array();
            $errorData['church_id'] = array($responseMessage->getMessage());
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
        }

    }
    

      /**
     * Add Update sage transaction history.
     *
     * @param  \App\Http\Requests\trasactiondetailApiRequest  $request
     * @return Response
     */
    public function trasactiondetail(Request $request){
        $input = $request->all();

        $user_id = $input['user_id'];
        $projectPaymentDonationDetailObj = new \App\ProjectDonationPayment;
        $projectPaymentDonation  = $projectPaymentDonationDetailObj->ProjectDonationPaymentDetail($user_id);

        // print_r($projectPaymentDonation);exit;
        $userDetailObj = new \App\User;
        $projectDetailObj = new \App\Project;
        $projectPaymentDonationDetail = new \App\ProjectDonationPayment;
        $response = [];
        foreach ($projectPaymentDonation as $key => $value) {

            $project = $projectDetailObj->find($value->project_id);
            $project->startdate = date('d-m-Y', strtotime($project->startdate));
            $project->enddate = date('d-m-Y', strtotime($project->enddate));
            $project->total_amount = $projectPaymentDonationDetail->totalFundCollection($project->id);
            $project->total_donor = $projectPaymentDonationDetail->projectDonationPayementCount($project->id);
            $project->user_role_id = $value->user_role_id;
            $response[] = $project;
            // $value->church_detail = $userDetailObj->find($project->church_id);

        }

        if($response){
            $responseArray = (object)[];
            // $responseArray->project_details = $response;
            $message = "Transaction history successfully.";
            $jsonResponse =  General::jsonResponse(1,$message,$response);
            return $jsonResponse;
        }else{
            $message = 'Transaction history not found.';
            $errorData = array();
            $errorData['church_id'] = array('Transaction history not found.');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;
        }
    }


      /**
     * Add Update sage sagepay detail.
     *
     * @param  \App\Http\Requests\sagepay_detailApiRequest  $request
     * @return Response
     */

    public function sagepay_detail(Request $request){
        $referrarDetailObj  = new \App\Referrar;
        $input = $request->all();
        $userList= $referrarDetailObj->referrar_detail($input['user_id']);
        $message = "Sagepay detail.";
        if($userList){
            $jsonResponse =  General::jsonResponse(1,$message,$userList);
            return $jsonResponse;
       }else{
            $jsonResponse =  General::jsonResponse(2,$message,$userList);
            return $jsonResponse;
       }
    }


      /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\action_sagepayApiRequest  $request
     * @return Response
     */
    public function action_sagepay(Request $request){
     
        $referrerDetailObj = new \App\Referrar;
        $input = $request->all();
        $referrer = $referrerDetailObj->referrar_detail($input['church_id']);
        if($referrer){
            $getreferrer = $referrerDetailObj->find($referrer->id);
            $getreferrer->delete();
        }


        if($input['type'] == 'add'){
            $referrerDetailObj->create($input);
            $message = "Sagepay detail has been added successfully.";
        }else{
            $referrerDetailObj->create($input);   
            $message = "Sagepay detail has been updated successfully.";
        }
        $userDetailObj  = new \App\User;
        $user  = $userDetailObj->userDetail($input['church_id']);
        $referrarDetailObj  = new \App\Referrar;
        $referraruserList= $referrarDetailObj->referrar_detail($user->id);
        if($referraruserList){
            $user->sagepay_referrer_Id =  $referraruserList->referrer_Id;
            $user->sagepay_vendor_id =  $referraruserList->vendor_id;
        }else{
            $user->sagepay_referrer_Id =  '';
            $user->sagepay_vendor_id =  '';
        }
        $jsonResponse =  General::jsonResponse(1,$message,$user);
        return $jsonResponse;
    }

/**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\get_payment_list  $request
     * @return Response
     */
    public function get_payment_list(Request $request){
       $input = $request->all();
       $filename = 'GTG-'.$input['payment_date'].'.csv';
       $date = General::date_mysql_format($input['payment_date']);

        $projectDonationPaymentDetailObj  = new \App\ProjectDonationPayment;
        $projectDonationPayment = $projectDonationPaymentDetailObj->all()->where('is_deleted','0')->where('transaction_date',$date);
        $res = [];
        foreach ($projectDonationPayment as $key => $value) {
                    $userDetailObj = new \App\User;
                    $user = $userDetailObj->find($value->user_id);
                    $value->donorname = $user->firstname." ".$user->lastname;
                    $value->donoremail = $user->email;
                    $value->donormobile = $user->mobile;
                    $projectDetailObj = new \App\Project;
            $project = $projectDetailObj->find($value->project_id);
            $church_detail = $userDetailObj->find($project->church_id);
            $value->church_name = $church_detail->firstname;
            $value->church_email = $church_detail->email;
            $value->church_mobile = $church_detail->mobile;
            $value->church_reference_id = $church_detail->church_reference_id;
            $value->projectname = $project->name;
            $value->projectgoal_amount = $project->goal_amount;
            $value->projectstartdate = $project->startdate;
            $value->projectenddate = $project->enddate;
            if (!empty($filename) && file_exists(public_path('storage/csv') .'/'. $filename)) {
                $value->downloadcsv = asset('public/storage/csv/'. $filename);
            } else  {
                $value->downloadcsv = asset('public/storage/public/storage/csv/default.png');
            }
                $res[] = $value;
        }
        $message = "Transaction Detail List.";
            $jsonResponse =  General::jsonResponse(1,$message,$res);
        return $jsonResponse;
      
    }
     /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_list  $request
     * @return Response
     */
     public function church_join_group_list(Request $request){
        $input = $request->all();
         if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj  = new \App\User;
        $churchList = $taskgroupmemberDetailObj->join_group_member_task_list($start,'0',$input['user_id']);
        $churchListcounts = $taskgroupmemberDetailObj->join_group_member_task_list($start,'1',$input['user_id']);
       
        $totalPages = ceil($churchListcounts['total'] / $perpage);
        if ($totalPages > $page) {
        
            $next = "true";
        }
        else{
            $next = "false";
        }

        foreach ($churchList as $key => $value) {

            $creator_name = $userDetailObj->find($value->creater_id);
            if($creator_name){
                $value->creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->creater_name = 'No Name';
            }

            $taskgroup = $taskgroupmemberDetailObj->group_member_task_list($value->id);
            $member_id = array();
            foreach ($taskgroup as $key => $values) {
                 $member_id[]  = $values->user_id;
            }
            $value->group_member = $member_id;
        } 


        if(count($churchList) > 0){
            $message = "Join Group List.";
            $jsonResponse =  General::jsonResponse(1,$message,$churchList,$next,'','form');
            return $jsonResponse;
        }else{
            $message = 'No data found in join group list.';
            $errorData = array();
            $errorData['error'] = array('No data found in join group list,');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');  
            return $jsonResponse;
        }
        
      
    }

     /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_list  $request
     * @return Response
     */
     public function user_join_group_list(Request $request){
        $input = $request->all();
         if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj  = new \App\User;
        $churchList = $taskgroupmemberDetailObj->join_user_group_member_task_list($start,'0',$input['user_id'],$input['church_id']);
        $churchListcounts = $taskgroupmemberDetailObj->join_user_group_member_task_list($start,'1',$input['user_id'],$input['church_id']);
         $totalPages = ceil($churchListcounts['total'] / $perpage);
        if ($totalPages > $page) {
            $next = "true";
         }
        else{
            $next = "false";
        }
        foreach ($churchList as $key => $value) {

            $creator_name = $userDetailObj->find($value->creater_id);
            if($creator_name){
                $value->creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->creater_name = 'No Name';
            }
             $taskgroup = $taskgroupmemberDetailObj->group_member_task_list($value->id);
             $member_id = array();
            foreach ($taskgroup as $key => $values) {
                $user = $userDetailObj->find($values->user_id);
                if($user){
                    $member_id[]  = $user->firstname.' '.$user->lastname;
                }else{
                    $member_id[]  = 'No Name';
                }
            }
            $value->group_member = $member_id;
        } 


        if(count($churchList) > 0){
            $message = "Join Group List.";
            $jsonResponse =  General::jsonResponse(1,$message,$churchList,$next,'','form');
            return $jsonResponse;
        }else{
            $message = 'No data found in join group list.';
            $errorData = array();
            $errorData['error'] = array('No data found in join group list,');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');  
            return $jsonResponse;
        }
      
    }
    /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_list  $request
     * @return Response
     */
     public function church_group_list(Request $request){
        $input = $request->all();
        if($request->has('page'))
        $page = $input['page'];
        else
        $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj  = new \App\User;
        $taskDetailObj  = new \App\Task;
        if($input['task_id']){
            $task_group_list = $taskDetailObj->find($input['task_id']);
            $task_group_select = $task_group_list->group_id;
        }else{
            $task_group_select = null;
        }
        $churchList = $taskgroupDetailObj->taskgroup_list_pagination($start,'0',$input['user_id'],$task_group_select);
        $churchListDatacounts = $taskgroupDetailObj->taskgroup_list_pagination($start,'1',$input['user_id']);

        
       
        /*if($input['role_id'] == 3){
         $churchList = $taskgroupDetailObj->taskgroup_list_pagination($start,$input['user_id']);
        }else if($input['role_id'] == 4){
         $churchList = $taskgroupDetailObj->pastor_taskgroup_list($start,$input['user_id']);   
        }
*/
       
        $churchListarr = array();
        foreach ($churchList as $key => $value) {
           
            $creator_name = $userDetailObj->find($value->creater_id);
             if($creator_name){
                $value->creater_name = $creator_name->firstname.' '.$creator_name->lastname;
             }else{
                $value->creater_name = 'No Name';
             }


            $taskgroup = $taskgroupmemberDetailObj->group_member_task_list($value->id);
            $member_id = array();

            foreach ($taskgroup as $key => $values) {
                 $member_id[]  = $values->user_id;
            }
            $value->group_member = $member_id;
            $churchListarr[] = json_decode(json_encode($value), true);
        }
        $login = $input['login_id'];
       
        if($input['role_id'] == 4){
            $churchList = array_values(array_filter($churchListarr,function($value) use ($login){
                  return(($value['creater_id'] != $login AND $value['group_privacy_status'] == '0') || ($value['creater_id'] == $login) AND ($value['group_privacy_status'] == '1' || $value['group_privacy_status'] == '0'));
            }));
        }

        $totalPages = ceil($churchListDatacounts['total'] / $perpage);
        if ($totalPages > $page) {
            $next = "true";
        }else{
            $next = "false";
        }
   
       if(count($churchList) > 0){
            $message = "Group List.";
            $jsonResponse =  General::jsonResponse(1,$message,$churchList,$next,'','form');
            return $jsonResponse;
        }else{
            $message = 'No data found in group list.';
            $errorData = array();
            $errorData['error'] = array('No data found in group list,');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');  
            return $jsonResponse;
        }
      
    }
     /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_detail  $request
     * @return Response
     */
     public function church_group_detail(Request $request){
        $input = $request->all();
        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj = new \App\User;
        $taskGroup = $taskgroupDetailObj->find($input['group_id']);
        
        $creator_name = $userDetailObj->find($taskGroup->creater_id);
        if($creator_name){
            $taskGroup->creater_name = $creator_name->firstname.' '.$creator_name->lastname;
        }else{
            $taskGroup->creater_name = 'No Name';
        }
        $taskgroup = $taskgroupmemberDetailObj->group_member_task_list($taskGroup->id);

        if($input['role_id'] == '2'){
            $member_id = array();
            foreach ($taskgroup as $key => $values) {
                $user = $userDetailObj->find($values->user_id);
            if($user){
                $member_id[]  = $user->firstname.' '.$user->lastname;
            }else{
                $member_id[]  = 'No Name';
            }
            }
            $taskGroup->group_member = $member_id;
        }else{
            $member_id = array();
            foreach ($taskgroup as $key => $values) {
            $member_id[]  = $values->user_id;
            }
            $taskGroup->group_member = $member_id;  
        }
        $message = "Group created successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$taskGroup);
        return $jsonResponse;
     }
     /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_create  $request
     * @return Response
     */
     public function church_group_create(Request $request){
        $userDetailObj = new \App\User;
        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $UserNotificationObj  = new \App\UserNotification;
        $input = $request->all();
        $TaskGroup = $taskgroupDetailObj->create($input);
        $input['group_id'] =  $TaskGroup->id; 
        
        $group_user_id = explode(",",$input['user_id']);
        $input['user_id'] = explode(",",$input['user_id']);
        foreach ($input['user_id'] as $key => $value) {
            $input['user_id'] = $value;
            $taskgroupmemberDetailObj->create($input);
        }
          
        foreach ($group_user_id as $key => $value) {

          $userList = $userDetailObj->where('id',$input['church_id'])->where('is_deleted','0')->where('status','active')->first();
          $usersend  = $userDetailObj->user_detail_sendnotification($value,$input['church_id']);
          if($usersend){
            $church_details = $userDetailObj->find($input['church_id']);
            $church_detail = array();
            $church_detail['group_id'] = $TaskGroup->id;
            $church_detail['church_name'] = ucfirst($userList->firstname);
            $church_detail['email'] = $userList->email;
            $church_detail['user_role_id'] = $usersend->user_role_id;
            $church_detail['mobile'] = $userList->mobile;
            $church_detail['image'] = $userList->image;
            $church_detail['from_user_id'] = $input['user_id'];
            $church_detail['church_id'] = $input['church_id'];
            $church_detail['church_name'] = $church_details->firstname;
            $church_detail['referral_id'] = $church_details->referral_id;
            $UserNotificationObj->send_notification($input['user_id'], $value, "create_group_by_church", $church_detail,$input['church_id'],$usersend->user_role_id);
         }
        }
        $message = "Group created successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,[]);
        return $jsonResponse;
     }
     /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_edit  $request
     * @return Response
     */
     public function church_group_edit(Request $request){

        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $input = $request->all();
    
        $taskgroup = $taskgroupDetailObj->find($input['id']);
        $taskgroup->update($input);
        
        $taskgroupmemberDetailObj->delete_member($input['id']);
        $input['group_id'] =  $input['id']; 
        $input['user_id'] = explode(",",$input['user_id']);
        foreach ($input['user_id'] as $key => $value) {
            $input['user_id'] = $value;
            $taskgroupmemberDetailObj->create($input);
        }
        $message = "Group updated successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,[]);
        return $jsonResponse;

     }
      /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\church_group_delete  $request
     * @return Response
     */
     public function church_group_delete(Request $request){
         $taskgroupDetailObj = new \App\TaskGroup;
         $taskgroupmemberDetailObj = new \App\TaskGroupMember;
         $notificationDetailObj = new \App\UserNotification;

         $input = $request->all();
         $taskgroup = $taskgroupDetailObj->find($input['id']);
         $taskgroup->update(['is_deleted'=>'1']);
         $taskgroup = $taskgroupmemberDetailObj->delete_member($input['id']);
         $notificationDetailObj->delete_notification_group($input['id']);
         $message = "Group deleted successfully.";
         $jsonResponse =  General::jsonResponse(1,$message,[]);
         return $jsonResponse;
     }
     /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\taks_assign  $request
     * @return Response
     */
    public function taks_assign(Request $request){
        $taskDetailObj  = new \App\Task;
        $userDetailObj  = new \App\User;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $input = $request->all(); 
         if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $task_list = $taskDetailObj->get_task_list($start,$input['user_id']);
        if(count($task_list)>10){
            $next = "true";
        }
        else{
            $next = "false";
        }

    $dashboardDetail = array();
    foreach ($task_list as $key => $value) {
                $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }
        $date = explode(' ',$value->date);
        
        $get_task_assign = $UserTaskAssignObj->single_get_task_assign_list($value->id,$input['user_id']);
        
        $value->user_task_detail = $get_task_assign; 
        
            $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                    $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }
            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
                foreach ($member_id as $key => $valuess) {
                    $memberdata[] =  intval($valuess);
                }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }
            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }

         if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d') && count($get_task_assign) > 0)
            $dashboardDetail['today'][]=$value;
        else if(date('Y-m-d',strtotime($date[0])) === date('Y-m-d', strtotime(' +1 day')) && count($get_task_assign) > 0)
            $dashboardDetail['tomorrow'][]=$value;
        else if(date('Y-m-d',strtotime($date[0])) > date('Y-m-d', strtotime(' +1 day')) && count($get_task_assign) > 0)
            $dashboardDetail['upcoming'][]=$value;
    }

    $message = "Task List.";
    $jsonResponse =  General::jsonResponse(1,$message,$dashboardDetail,$next,'','form');
    return $jsonResponse;

    }
    /**
     * Add Update sage action_sagepay.
     *
     * @param  \App\Http\Requests\update_taks_assign  $request
     * @return Response
     */
    public function update_taks_assign(Request $request){
        $input = $request->all(); 
        $taskDetailObj  = new \App\Task;
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $UserNotificationObj  = new \App\UserNotification;
        $userDetailObj  = new \App\User;
        $user = $UserTaskAssignObj->get_task_assign_by_id($input['id'],$input['recurring_id']);

        $updatetask = $user->update($input);
        $task = $taskDetailObj->find($user->task_id);

          /*$userList = $userDetailObj->where('id',$user->assign_user_id)->where('is_deleted','0')->where('status','active')->first();*/
           $userList = $userDetailObj->user_detail_sendnotification($user->assign_user_id,$input['church_id']);
            if($input['status'] == 'active'){
                $notification_type = 'update_taks_assign_accepted';
            }else if($input['status'] == 'reject'){
                $notification_type = 'update_taks_assign_rejected';
            }else{
                $notification_type = 'update_taks_assign';
            }
            $church_details = $userDetailObj->find($input['church_id']);
            $church_detail = array();
            $church_detail['task_id'] = $task->id;
            $church_detail['recurring_id'] = $input['recurring_id'];
            $church_detail['task_status'] = $input['status'];
            $church_detail['task_title'] = $task->title;
            $church_detail['task_description'] = $task->description;
            $church_detail['user_name'] = ucfirst($userList->firstname);
            $church_detail['email'] = $userList->email;
            $church_detail['user_role_id'] = $userList->user_role_id;
            $church_detail['mobile'] = $userList->mobile;
            $church_detail['image'] = $userList->image;
            $church_detail['from_user_id'] = $user->assign_user_id;
            $church_detail['church_id'] = $input['church_id'];
            $church_detail['church_name'] = $church_details->firstname;
            $church_detail['referral_id'] = $church_details->referral_id;
          $UserNotificationObj->send_notification($user->assign_user_id, $task->user_id,$notification_type, $church_detail,$input['church_id'],$userList->user_role_id);

        $message = "Task updated Successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,[]);
        return $jsonResponse;
    }

    /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public function taks_assign_detail(Request $request){
        
            $taskDetailObj  = new \App\Task;
            $userDetailObj  = new \App\User;
            $UserTaskAssignObj  = new \App\UserTaskAssign;
            $taskgroupmemberDetailObj = new \App\TaskGroupMember;
            $input = $request->all(); 
            $task_list = $taskDetailObj->get_task_detail_list($input['user_id'],$input['task_id']);

        foreach ($task_list as $key => $value) {

            $creator_name = $userDetailObj->find($value->user_creater_id);
            if($creator_name){
                $value->user_creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->user_creater_name = 'No Name';
            }
            $get_task_assign = $UserTaskAssignObj->single_get_task_assign_list($value->id,$input['user_id']);
            $value->user_task_detail = $get_task_assign; 

            $data  = explode(',', $value->group_id);
            $groupdata = array();
            if(!empty($data[0])){
                foreach ($data as $key => $values) {
                $groupdata[] =  intval($values);
                }
                $value->group_id = $groupdata;
            }else{
                $value->group_id = array();
            }

            $member_id  = explode(',', $value->member_id);
            $memberdata = array();
            if(!empty($member_id[0])){
            foreach ($member_id as $key => $valuess) {
                $memberdata[] =  intval($valuess);
            }
                $value->member_id = $memberdata;
            }else{
                $value->member_id = array();
            }

            $is_admin_user  = explode(',', $value->is_admin_user);
            $isadminuser = array();
            if(!empty($is_admin_user[0])){
                foreach ($is_admin_user as $key => $valuessa) {
                    $isadminuser[] =  intval($valuessa);
                }
                $value->is_admin_user = $isadminuser;
            }else{
                $value->is_admin_user = array();
            }
        }
        if($task_list){
            $message = "Task List.";
            $jsonResponse =  General::jsonResponse(1,$message,$task_list[0]);
            return $jsonResponse;
       }else{
            $message = "Task does not exist.";
            $jsonResponse =  General::jsonResponse(2,$message,[]);
            return $jsonResponse;
       }

        }
   /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public static function send_test_push_notification(Request $request) {
       $input = $request->all(); 
       $UserNotificationObj  = new \App\UserNotification;
       
        if ($input['device_type'] == "android") {
            
            $data = $UserNotificationObj->send_android_push_notification($input['device_token'], "Test Notification,fsdfsf", "This notification send for testing purpose.", "test_notification",$input['role_id'], []);
            $message = 'Android test notification send successfully.';
            $jsonResponse =  General::jsonResponse(1,$message,json_decode($data));
            return $jsonResponse;
        } else if ($input['device_type'] == "ios") {
            $data = $UserNotificationObj->send_ios_push_notification($input['device_token'], "Test Notification", "This notification send for testing purpose.", "test_notification",$input['role_id'], [],"0");
            $message = 'IOS test notification send successfully';
            $jsonResponse =  General::jsonResponse(1,$message,json_decode($data));
            return $jsonResponse;

            
        }
    }
    /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public static function get_allgroup_list(Request $request) {
        $input = $request->all();
         if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $taskgroupDetailObj = new \App\TaskGroup;
        $taskgroupmemberDetailObj = new \App\TaskGroupMember;
        $userDetailObj  = new \App\User;
        $taskDetailObj  = new \App\Task;

        if($input['task_id']){
            $task_group_list = $taskDetailObj->find($input['task_id']);

            $task_group_select = $task_group_list->group_id;
        }else{
            $task_group_select = null;
        }
        $churchList = $taskgroupmemberDetailObj->join_group_member_task_list_all($start,$input['user_id'],$task_group_select);
         $next = "false";
       /* if(count($churchList)>10){
            $next = "true";
         }
        else{
            $next = "false";
        }*/
        
        foreach ($churchList as $key => $value) {

            $creator_name = $userDetailObj->find($value->creater_id);
            if($creator_name){
                $value->creater_name = $creator_name->firstname.' '.$creator_name->lastname;
            }else{
                $value->creater_name = 'No Name';
            }

            $taskgroup = $taskgroupmemberDetailObj->group_member_task_list($value->id);
            $member_id = array();
            foreach ($taskgroup as $key => $values) {
                 $member_id[]  = $values->user_id;
            }
            $value->group_member = $member_id;
        } 

        if($churchList){
            $message = "Join Group List.";
            $jsonResponse =  General::jsonResponse(1,$message,$churchList,$next,'','form');
            return $jsonResponse;
        }else{
            $message = 'No data found in join group list.';
            $errorData = array();
            $errorData['error'] = array('No data found in join group list,');
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');  
            return $jsonResponse;
        }

      
    }
   /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public static function update_fcm(Request $request) {
        $input = $request->all();
        $userDetailObj  = new \App\User;
        $user = $userDetailObj->find($input['user_id']);
        $edit_qrcode = $user->update($input);
        $message = "Device detail change successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,(object)array());
        return $jsonResponse;
        
    }
    /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests  $request
     * @return Response
     */
    public static function giftaid_update_status(Request $request) {
        $input = $request->all(); 
        $projectDonationPayments  = new \App\ProjectDonationPayment;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $userCardInfo  = new \App\UserCardInfo;
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $userDetailObj  = new \App\User;
        $users = $userDetailObj->find($input['user_id']);

        $user  = $userDetailObj->userLoginCheck($users->email,$users->user_role_id);
        if($input['declaration_id'] == '1'){
            $donation = $projectDonationPayments->finduserProject($input['user_id']);
        }else if($input['declaration_id'] == '2'){
            $donation = $projectDonationPayments->finduserProject($input['user_id']);
            $input['gift_declaration'] = 'yes';
            $edit_giftdec = $user->update($input);
        }else if($input['declaration_id'] == '4'){
            $input['gift_declaration'] = 'no';
            $edit_giftdec = $user->update($input);
        }

            $user->image =  General::get_file_src($user->image);
        if($user->user_role_id != 3){
            $userChurch = $userDetailObj->userDetail($user->church_id);
        if($userChurch){
            $user->referral_id = $userChurch->referral_id;
        }else{
            $user->referral_id = '';    
        }
        }
        if($user->user_role_id == 3){
            $referrarDetailObj  = new \App\Referrar;
            $referraruserList= $referrarDetailObj->referrar_detail($user->id);
        if($referraruserList){
            $user->sagepay_referrer_Id =  $referraruserList->referrer_Id;
            $user->sagepay_vendor_id =  $referraruserList->vendor_id;
        }else{
            $user->sagepay_referrer_Id =  '';
            $user->sagepay_vendor_id =  '';
        }
            $userdetail = $userDetailObj->userDetail($user->id);
        if($userdetail->qrcode != '')
            $user->church_generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode));
        else
            $user->church_generated_qrcode = '';

        if($userdetail->qrcode_visitor != '')
            $user->church_generated_qrcode_visitor = base64_encode(QrCode::format('png')->size(300)->generate($userdetail->qrcode_visitor));
        else
            $user->church_generated_qrcode_visitor = '';
        }
        if($user->user_role_id == 4){
            $getscripture = $userDetailObj->find($user->church_id);
            $user->scripture = $getscripture->scripture;
         }
        if($user->user_role_id == 2){
            $UserAddress = new \App\UserAddress;
            $useraddressdetail = $UserAddress->update_address($user->id);
        if($useraddressdetail){
            $user->address_detail =  $useraddressdetail;
        }else{
            $address = array();
            $address['id'] = '';
            $address['user_id'] = '';
            $address['billingAddress1'] = '';
            $address['billingAddress2'] = '';
            $address['billingState'] = '';
            $address['billingCity'] = '';
            $address['billingPostcode'] = '';
            $address['billingCountry'] = '';
            $address['created_at'] = '';
            $address['updated_at'] = '';

            $user->address_detail =$address;
        }
        }
        $count_declaration = $userGiftDeclarations->countdeclaration($user->id);
        if($count_declaration > 0){
            $user->is_past_project =true;   
        }else{
            $user->is_past_project =false;
        }

        $count_cardinfo = $userCardInfo->countcarddetail($user->id);
        if($count_cardinfo > 0){
            $user->is_card_detail =true;   
        }else{
            $user->is_card_detail =false;
        }
       
        if($user->user_role_id == 2 || $user->user_role_id == 5){
            $UserMultiChurchObj = $UserMultiChurchObj->user_church_list($user->id);
            $church_detail = array();
        foreach ($UserMultiChurchObj as $key => $value) {
            $church_detailobj = $userDetailObj->single_church_detail($value->church_id);
            $church_detailobj->image =  General::get_file_src($church_detailobj->image);
            $church_detailobj->new_request = $value->new_request;
            $church_detailobj->user_role_id = $value->user_role_id;
            $church_detailobj->primary_church = $value->primary_church;
            $church_detail[] = $church_detailobj;
        }
            $user->church_id = $church_detail;
         }

        $message = "Update gift aid successfully.";
        $jsonResponse =  General::jsonResponse(1,$message,$user,'','','form');
        return $jsonResponse;
    }
    public static function testing_notification_update_weekly(Request $request) {
         $input = $request->all(); 
          $UserNotificationObj  = new \App\UserNotification;
          $input['extra_data'] = json_decode($input['extra_data']);

          $UserNotificationObj->weekly_send_notification($input['id'],$input['from_user_id'], $input['user_id'],$input['notification_type'], $input['extra_data']);
          print_r($input); exit;
    }

    /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests\TransactionDetailsApiRequest  $request
     * @return Response
     */
    public function transactionDetails(TransactionDetailsApiRequest $request)
    {
        $input = $request->all(); 
        $ProjectDonationPayments  = new \App\ProjectDonationPayment;
        $projectDetailObj = new \App\Project;
        $responseArray = (object)[];
        $responseArray->project_detail = $projectDetailObj->find($input['project_id']);
        if(isset($input['church_id']) && !isset($input['user_id'])){
            $responseArray->churchDonationHistory = $ProjectDonationPayments->getChurchDonationHistory($input['project_id'],$input['church_id'],$input['is_user_role_id']);
            $message = "Church Donation List.";
            $jsonResponse =  General::jsonResponse(1,$message,$responseArray);

        }
        else{
            $responseArray->userDonationHistory = $ProjectDonationPayments->getUserDonationHistory($input['project_id'],$input['user_id']);
            $message = "user Donation List.";
            $jsonResponse =  General::jsonResponse(1,$message,$responseArray);
        }
        return $jsonResponse;
    }
     /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests\SwitchChurchApiRequest  $request
     * @return Response
     */
     public function switchChurchList(SwitchChurchApiRequest $request){
         $userDetailObj  = new \App\User;
         $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;
            $perpage = 10;
            $calc  = $perpage * $page;
            $start = $calc - $perpage;

      
         $swirch_church_id = $userDetailObj->switch_church_list($start,'0',$input['user_id'],$input['search']);
         $swirch_church_count = $userDetailObj->switch_church_list($start,'1',$input['user_id'],$input['search']);
        $totalPages = ceil($swirch_church_count['total'] / $perpage);
        if ($totalPages > $page) {
            $next = "true";
        }
        else{
            $next = "false";
        }
        
        foreach ($swirch_church_id as $key => $value) {
            $value->image =  General::get_file_src($value->image);
        }
       
        $message = "Switch Church List.";
        $jsonResponse = General::jsonResponse(1,$message,$swirch_church_id,$next,'','form');
        return $jsonResponse;
        
     }

     /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests\SwitchChurchApiRequest  $request
     * @return Response
     */
     public function selectedSwitchChurchList(SwitchChurchApiRequest $request){
         $userDetailObj  = new \App\User;
         $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;
            $perpage = 10;
            $calc  = $perpage * $page;
            $start = $calc - $perpage;

      
        $swirch_church_id = $userDetailObj->selecte_switch_church_list($start,'0',$input['user_id'],$input['search']);
        $swirch_church_count = $userDetailObj->selecte_switch_church_list($start,'1',$input['user_id'],$input['search']);
        $totalPages = ceil($swirch_church_count['total'] / $perpage);
        if ($totalPages > $page) {
            $next = "true";
        }
        else{
            $next = "false";
        }
        foreach ($swirch_church_id as $key => $value) {
            $value->image =  General::get_file_src($value->image);
        }
       
        $message = "Switch Church List.";
        $jsonResponse = General::jsonResponse(1,$message,$swirch_church_id,$next,'','form');
        return $jsonResponse;
        
     }
     
     /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests\UserAddMultiChurchApiRequest  $request
     * @return Response
     */

     public function userAddMultiChurch(UserAddMultiChurchApiRequest $request){
        $UserMultiChurchObj  = new \App\UserMultiChurch;
        $userGiftDeclarations  = new \App\UserGiftDeclarations;
        $UserNotificationObj  = new \App\UserNotification;
        $userDetailObj  = new \App\User;
        $userCardInfo  = new \App\UserCardInfo;
        $input = $request->all();
        $input['primary_church'] = '0';
        if($input['new_request'] == 'N'){
            $input['user_role_id'] = '2';
        }else{
            $input['user_role_id'] = '5';
        }

        $church_count = $UserMultiChurchObj->find_user_already_register($input['user_id'],$input['church_id']);
        if($church_count > 0){
            $message = 'You have already registered a selected church';
            $jsonResponse = General::jsonResponse(2,$message,'','','','form');
            return $jsonResponse;
        }
            $create_church = $UserMultiChurchObj->create($input);
        $user = $userDetailObj->find($input['user_id']);
        if($input['user_role_id'] == 2){

        /* Send Notification church */
        $church_details = $userDetailObj->find($input['church_id']);
        $church_detail = array();
        $church_detail['user_id'] = $user->id;
        $church_detail['user_name'] = $user->firstname;
        $church_detail['email'] = $user->email;
        $church_detail['user_role_id'] = $user->user_role_id;
        $church_detail['mobile'] = $user->mobile;
        $church_detail['from_user_id'] = $user->id;
        $church_detail['church_id'] = $input['church_id'];
        $church_detail['church_name'] = $church_details->firstname;
        $church_detail['referral_id'] = $church_details->referral_id;
        $UserNotificationObj->send_notification($user->id,$input['church_id'], "member_registration", $church_detail,$input['church_id'],$input['user_role_id']);

        /* Send Notification church */

        /* Send Notification pastor */
        $pastor_detail = $userDetailObj->getchurchpastorlist($input['church_id']);
        if($pastor_detail){
        $church_details = $userDetailObj->find($input['church_id']);
        foreach ($pastor_detail as $key => $value) {
        $church_detail = array();
        $church_detail['user_id'] = $user->id;
        $church_detail['user_name'] = $user->firstname;
        $church_detail['email'] = $user->email;
        $church_detail['user_role_id'] = $user->user_role_id;
        $church_detail['mobile'] = $user->mobile;
        $church_detail['from_user_id'] = $user->id;
        $church_detail['church_id'] = $input['church_id'];
        $church_detail['church_name'] = $church_details->firstname;
        $church_detail['referral_id'] = $church_details->referral_id;
        $UserNotificationObj->send_notification($user->id,$value->id, "member_registration", $church_detail,$input['church_id'],$input['user_role_id']);
        }
        }


        /* Send Notification pastor */
        }
            $userInfo = $userDetailObj->userDetail($input['user_id']);
        if($userInfo->user_role_id != 3){
            $userChurch = $userDetailObj->userDetail($input['church_id']);
            $userInfo->referral_id = $userChurch->referral_id;
        }
            $count_declaration = $userGiftDeclarations->countdeclaration($input['user_id']);
        if($count_declaration > 0){
            $userInfo->is_past_project =true;   
        }else{
            $userInfo->is_past_project =false;
        }
            $count_cardinfo = $userCardInfo->countcarddetail($input['user_id']);
        if($count_cardinfo > 0){
            $userInfo->is_card_detail =true;   
        }else{
            $userInfo->is_card_detail =false;
        }
        if($userInfo->user_role_id == 2 || $userInfo->user_role_id == 5){
            $UserMultiChurchObj = $UserMultiChurchObj->user_church_list($userInfo->id);
            $church_detail = array();
        foreach ($UserMultiChurchObj as $key => $value) {
            $church_detailobj = $userDetailObj->single_church_detail($value->church_id);
            $church_detailobj->image =  General::get_file_src($church_detailobj->image);
            $church_detailobj->new_request = $value->new_request;
            $church_detailobj->user_role_id = $value->user_role_id;
            $church_detailobj->primary_church = $value->primary_church;
            $church_detail[] = $church_detailobj;
        }
            $userInfo->church_id = $church_detail;
        }
       $message = 'Church created has been successfully';
        $jsonResponse =  General::jsonResponse(1,$message,$userInfo,'','','form');
        return $jsonResponse;
     }

      /**
     * Determine church and donor login based on user roles .
     *
     * @param  \App\Http\Requests\UserUpdateMultiChurchApiRequest  $request
     * @return Response
     */

     public function upate_user_role(UserUpdateMultiChurchApiRequest $request){
            $UserMultiChurchObj  = new \App\UserMultiChurch;
            $userGiftDeclarations  = new \App\UserGiftDeclarations;
            $userDetailObj  = new \App\User;
            $userCardInfo  = new \App\UserCardInfo;
            $UserNotificationObj  = new \App\UserNotification;
            $input = $request->all();
            $user_update = $UserMultiChurchObj->update_user_wise_role($input['user_id'],$input['church_id'],$input['user_role_id']);
            
            if($user_update){
                $userList = $userDetailObj->user_detail_sendnotification($input['user_id'],$input['church_id']);
                $church_details = $userDetailObj->find($input['church_id']);
                /*Send User notification*/
                    $church_detail = array();
                    $church_detail['user_id'] = $userList->id;
                    $church_detail['user_name'] = $userList->firstname;
                    $church_detail['email'] = $userList->email;
                    $church_detail['user_role_id'] = $userList->user_role_id;
                    $church_detail['mobile'] = $userList->mobile;
                    $church_detail['from_user_id'] = $userList->id;
                    $church_detail['church_id'] = $input['church_id'];
                    $church_detail['church_name'] = $church_details->firstname;
                    $church_detail['referral_id'] = $church_details->referral_id;
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
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests\UserIdApiRequest  $request
    * @return Response
    */

     public function check_user_status(UserIdApiRequest $request){
         $userDetailObj  = new \App\User;
         $input = $request->all();
         $userdetail = $userDetailObj->find($input['user_id']);
         $user_status = array();
         if($userdetail->status == 'active' AND $userdetail->is_deleted == '0'){
          $user_status['is_status'] = true;
         }else{
          $user_status['is_status'] = false;
         }
        $message = 'User status';
        $jsonResponse =  General::jsonResponse(1,$message,$user_status,'','','form');
        return $jsonResponse;
     }

     /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests\UserIdApiRequest  $request
    * @return Response
    */

     public function project_default_image(Request $request){
        $ProjectDefaultImagesObj  = new \App\ProjectDefaultImages;
        $imagelist = $ProjectDefaultImagesObj->projectImages();
        foreach ($imagelist as $key => $value) {
              $value->image =  General::get_file_src($value->image);
        }
        $message = 'User status';
        $jsonResponse =  General::jsonResponse(1,$message,$imagelist,'','','form');
        return $jsonResponse;
     }

    
    /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests\ChurchExistsRequest $request
    * @return Response
    */

     public function transaction_report_user(Request $request){
       
       $ProjectDonationPayments  = new \App\ProjectDonationPayment;
            $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        if($request->has('project_id'))
            $project_id = $input['project_id'];
        else
            $project_id = '';

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $donation_report = $ProjectDonationPayments->church_trasaction_report_user($start,'0',$input['user_id'],$input['start_date'],$input['end_date'],$project_id);
        $donation_report_count = $ProjectDonationPayments->church_trasaction_report_user($start,'1',$input['user_id'],$input['start_date'],$input['end_date'],$project_id);

        $totalPages = ceil($donation_report_count['total'] / $perpage);
        if($totalPages > $page){
            $next = "true";
        }else{
            $next = "false";   
        }

        $message = 'Transaction report user list';
        $jsonResponse = General::jsonResponse(1,$message,$donation_report,$next,'','form');
        return $jsonResponse;

     }
     
     /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests\ChurchExistsRequest $request
    * @return Response
    */

     public function transaction_report_church(ChurchExistsRequest $request){
        $ProjectDonationPayments  = new \App\ProjectDonationPayment;
            $input = $request->all();
        if($request->has('page'))
            $page = $input['page'];
        else
            $page = 1;

        if($request->has('project_id'))
            $project_id = $input['project_id'];
        else
            $project_id = '';

        $perpage = 10;
        $calc  = $perpage * $page;
        $start = $calc - $perpage;

        $donation_report = $ProjectDonationPayments->church_trasaction_report($start,'0',$input['church_id'],$input['start_date'],$input['end_date'],$project_id);
        $donation_report_count = $ProjectDonationPayments->church_trasaction_report($start,'1',$input['church_id'],$input['start_date'],$input['end_date'],$project_id);

       
        $totalPages = ceil($donation_report_count['total'] / $perpage);
        if($totalPages > $page){
            $next = "true";
        }else{
            $next = "false";   
        }
        $message = 'Transaction report church list';
        $jsonResponse = General::jsonResponse(1,$message,$donation_report,$next,'','form');
        return $jsonResponse;
      
     }
   

     /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests $request
    * @return Response
    */

    public function filter_project_list(Request $request){
       $projectDetailObj = new \App\Project;
       $input = $request->all();
       $project_list = $projectDetailObj->project_list_filter($input['church_id']);
       $message = "Project List";
       if(count($project_list) > 0){
       $jsonResponse =  General::jsonResponse(1,$message,$project_list,'','','form');
       }else{
        $jsonResponse =  General::jsonResponse(2,$message,$project_list,'','','form');
       }
       return $jsonResponse;
    }

     /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests $request
    * @return Response
    */

    public function filter_project_list_user(Request $request){
		$projectDetailObj = new \App\Project;
		$UserMultiChurchObj  = new \App\UserMultiChurch;
        $ProjectDonationPayments  = new \App\ProjectDonationPayment;
		$input = $request->all();
		$UserMultiChurchObj = $UserMultiChurchObj->user_church_list($input['user_id']);
		/*$church_detail = array();
		foreach ($UserMultiChurchObj as $key => $value) {
		$church_detail[] = $value->church_id;
		}*/
         $donation_report = $ProjectDonationPayments->ProjectDonationPaymentDetail($input['user_id']);
        
        if($donation_report){
            $project_lists = array();
        foreach ($donation_report as $key => $value) {
            $project_lists[] = $value->project_id;
        }
            $project_list = $projectDetailObj->project_list_filter_user($project_lists);
            $message = "Project List";
        $jsonResponse =  General::jsonResponse(1,$message,$project_list,'','','form');
        }else{
            $project_list = array();
            $message = "Project List";
            $jsonResponse =  General::jsonResponse(2,$message,$project_list,'','','form');
        }
		
		
		return $jsonResponse;
    }
    
    /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests\TransactionYearApiRequest $request
    * @return Response
    */
    public function transaction_report_year(TransactionYearApiRequest $request){
        $ProjectDonationPayments  = new \App\ProjectDonationPayment;
        $input = $request->all();
        $donation_report = $ProjectDonationPayments->church_trasaction_year_list($input['church_id']);

        $message = "Year List";
        $jsonResponse =  General::jsonResponse(1,$message,$donation_report,'','','form');
        return $jsonResponse;

    }

    /**
    * Determine church and donor login based on user roles .
    *
    * @param  \App\Http\Requests\clearAllNotification $request
    * @return Response
    */
    public function clearAllNotification(Request $request){

        $notificationDetailObj = new \App\UserNotification;
        $input = $request->all();
        $notification = $notificationDetailObj->clear_all_notification($input['user_id']);

        $message = 'Clear all notification.';
        $jsonResponse =  General::jsonResponse(1,$message,[],'','','form');
        return $jsonResponse;
    }
    
     /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function taskRecurringType(Request $request){
        $taskRecurringDetailObj = new \App\TaskRecurringType;
        $taskPriority = $taskRecurringDetailObj->all();
        $message = 'Task Recurring List.';
        $jsonResponse =  General::jsonResponse(1,$message,$taskPriority,'','','form');
        return $jsonResponse;
    }

     /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function userAssignTaskDetail(Request $request){
        $UserTaskAssignObj  = new \App\UserTaskAssign;
        $taskdetail   = new \App\Task;
        $userdetailobj   = new \App\User;
        $input = $request->all();
        $task_detail = $taskdetail->find($input['task_id']);
        if($task_detail){
        $countotal  = $UserTaskAssignObj->count_totalassign('1',$input['task_id'],$input['id']);
        $outcountotal  = $UserTaskAssignObj->count_totalassign('2',$input['task_id'],$input['id']);
        $task_detail->outof = $outcountotal.' of '.$countotal;
        $find_status = $UserTaskAssignObj->count_complition($input['task_id'],$input['id']);
        if($find_status > 0){
            $task_detail->status = 'pending';
        }else{
            $task_detail->status = 'complete';
        }
        }
        $userdetailassign = array();
        $userdetailassign['task_detail'] = $task_detail;
        $userlist = $UserTaskAssignObj->userassigndetail($input['id']);
        foreach ($userlist as $key => $value) {
            $userdetail = $userdetailobj->find($value->assign_user_id);
            $value->firstname = $userdetail->firstname;
            $value->lastname = $userdetail->lastname;
            $value->email = $userdetail->email;
            $value->mobile = $userdetail->mobile;
            $value->image = General::get_file_src($userdetail->image);
        }
        $userdetailassign['task_user_detail'] = $userlist;
        $message = 'User Assign task detail';
        $jsonResponse =  General::jsonResponse(1,$message,$userdetailassign,'','','form');
        return $jsonResponse;
    }
    
       /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function repeattype(Request $request){
        $repeatDetailObj = new \App\RepeatType;
        $repeattype = $repeatDetailObj->repeat_list();
        $message = 'Repeat types list.';
        $jsonResponse =  General::jsonResponse(1,$message,$repeattype);
        return $jsonResponse;
    }

       /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function addtoprojectarchive(Request $request){
        $input = $request->all();
        $projectDetailObj  = new \App\Project;
        $projectRepeatDonation  = new \App\ProjectRepeatDonation;
        $project = $projectDetailObj->project_detail($input['project_id']);
        $project->update($input);
        if($input['is_project_archive'] == '0'){
            $message = 'Project restored successfully.';    
        }else{
            $donation_list = $projectRepeatDonation->project_archieve_repeat($input['project_id']);
            $message = 'Archive project successfully.';
        }
        $jsonResponse =  General::jsonResponse(1,$message,'');
        return $jsonResponse;
    }
    
         /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function userfamilytree(UserFamilyTreeApiRequest $request){
        
       $userfamilytreedetailobj  = new \App\UserFamilyTree;
       $input = $request->all();
       if($input['user_id'] == $input['scan_user_id']){
            $message = 'You cannot add yourself as a family member';
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;

       }
       $user_add = $userfamilytreedetailobj->find_user_family($input['user_id']);
       if($user_add){
           
            $message = 'You have already part of family member.';
            $errorData = array();
            $errorData['church_id'] = array($message);
            $jsonResponse =  General::jsonResponse(3,$message,$errorData,'','','form');
            return $jsonResponse;

       }else{
       $user_detail = $userfamilytreedetailobj->find_user_family($input['scan_user_id']);
       
       if($user_detail){
        $input['user_id'] = $input['user_id'];
        $input['id'] = $user_detail->id;
        $users = $userfamilytreedetailobj->create($input);
       }else{
         if($input['user_id']){
           $users = $userfamilytreedetailobj->create($input);
        }
        if($input['scan_user_id']){
            $input['user_id'] = $input['scan_user_id'];
            $input['id'] = $users->id;
            $user = $userfamilytreedetailobj->create($input);   

        }
       
       }
        $message = 'Add family tree successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,'');
        return $jsonResponse;
      }

    }   

         /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function userfamilytreelist(Request $request){
    $userDetailObj  = new \App\User;
    $userfamilytreedetailobj  = new \App\UserFamilyTree;
    $input = $request->all();
    $dataofuser = $userfamilytreedetailobj->find_user($input['user_id']);
        if($dataofuser){
            $femilymember = $userfamilytreedetailobj->get_all_family_member($dataofuser->id,$input['user_id']);
            $familymember_list = array();
            foreach ($femilymember as $key => $value) {
                $user_list = $userDetailObj->find($value->user_id);
                $user_list->image =  General::get_file_src($user_list->image);
                $familymember_list[] = $user_list;
                
            }
            if($familymember_list){
                $message = 'Family tree list.';
                $jsonResponse =  General::jsonResponse(1,$message,$familymember_list);
                return $jsonResponse;

            }else{
                $message = 'Family tree list.';
                $jsonResponse = General::jsonResponse(2,$message,'','','','form');
                return $jsonResponse;

            }
        }else{
            $message = 'Family tree not found.';
            $jsonResponse = General::jsonResponse(2,$message,'','','','form');
            return $jsonResponse;
         
        }

    } 
    

         /**
     * Determine forget password based on user roles .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return Response
     */
    public static function removefamilymember(Request $request){
        $userfamilytreedetailobj  = new \App\UserFamilyTree;
        $input = $request->all(); 
        $user_add = $userfamilytreedetailobj->delete_family_member($input['user_id']);

        $message = 'Family member removed successfully.';
        $jsonResponse =  General::jsonResponse(1,$message,array());
        return $jsonResponse;
        
    }
    
    
    
    
}
