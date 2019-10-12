<?php
/**
 * An ProjectController Class 
 *
 * The controller class is using to controll all project and fund name of church.
 *
 * @package    Good To Give
 * @subpackage Common
 * @author     Acquaint Soft Developer 
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use App\Http\Requests\Admin\AddProjectAdminRequest;
use App\Http\Requests\Admin\ChurchFundAdminRequest;
use App\Http\Requests\Admin\EditFundNameAdminRequest;
use Illuminate\Support\Facades\URL;
use LaravelColors\LaravelColorsServiceProvider;
use QrCode;
use DB;

class ProjectController extends Controller
{
 
      /**
     * Determine church fund list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return //return list of church fund
     */

    public static function ajaxchurchDonation(Request $request){
        $projectDetailObj = new \App\Project;  
        $projectPaymentDonationDetail = new \App\ProjectDonationPayment;
        $input  = $request->all();
       
        $project = $projectDetailObj->project_detail($input['id']);
        if($project){
        $totalamount = $projectPaymentDonationDetail->totalFundCollectionprojectamount($input['id']);
        $project->total_amount = $totalamount;
        $percentage_val  = intval(($totalamount / $project->goal_amount) * 100);
        $project->temppercentage = $percentage_val;
        $percentage = 100 - $percentage_val;
        if($percentage_val > 100)
        $project->percentage = 0;
        else
        $project->percentage = $percentage;


    $project->generated_qrcode = base64_encode(QrCode::format('png')->size(300)->generate($project->qrcode));

        $project_html['per'] = $project->percentage.'%';
        $project_html['tempper'] = $project->temppercentage;
        $project_html['donation'] = '<span>&#163;</span>'.$project->total_amount.' / <span>&#163;</span>'.$project->goal_amount;

       
      }else{
        $percentage = 0;
        $project_html['html'] = '<span>Not Found Project</span>
        <h2>Amount : 0 / 0 </h2> 
        <img src="data:image/png;base64, '.base64_encode(QrCode::format('png')->size(300)->generate('0')).'">';
        $project_html['canvas_chart'] = '<div class="progressbar">
        <div class="second circle" data-percent="0">
        <strong></strong>
        </div>
        </div><input type="hidden" name="percentag_val" id="percentag_val" value="0">';

        if($input['skillpercentage'] != $percentage){
        $project_html['updated'] = 'true';
        }else{
        $project_html['updated'] = 'false';   
        } 
      }

        return $project_html;

    }
    public static function churchDonation(Request $request,$id){
      $projectDetailObj = new \App\Project;  
      $projectPaymentDonationDetail = new \App\ProjectDonationPayment;
      $project_id = explode("_",$id);
       $title = 'Real Time Donation';
      if(count($project_id) != 2){

         $project = (object)array();
        $project->name = 'Not Found Project';
        $project->total_amount = '0';
        $project->goal_amount = '0';
        $project->qrcode = '0';
        $project->percentage = '0';
        $project->id = '0';
        $background_color = '';
        $font_color = '#000';
        $transition_position = '';
        $backgroun_image = '';
        $color_br = '#000';

         return view('project/project_donation', compact('title','project','backgroun_image','transition_position','background_color','font_color','color_br'));
      }
    
      $project = $projectDetailObj->project_detail($project_id[1]);

      if($project){
      $totalamount = $projectPaymentDonationDetail->totalFundCollectionprojectamount($project_id[1]);
        foreach ($project->project_settings_detail as $key => $value) {
        if($value->field_name == 'BACKGROUND_IMAGE'){
        $value->field_value = General::get_file_src($value->field_value);
        }
        }
      $project->total_amount = $totalamount;
      $percentage_val = intval(($totalamount / $project->goal_amount) * 100);
      
       $project->temppercentage = $percentage_val;
      $percentage  = 100 - $percentage_val;
      if($percentage_val > 100)
        $project->percentage = 0;
      else
        $project->percentage = $percentage;

        $backgroun_image = '';
        $transition_position = '';
        $background_color = '';
        foreach ($project->project_settings_detail as $key => $value) {

          if($value->field_name == 'BACKGROUND_IMAGE'){
            $backgroun_image .= $value->field_value;
          }
          if($value->field_name == 'TRANSITION_POSITION'){
            if($value->field_value == '1'){
             $transition_position .= 'tb';
            }else if($value->field_value == '2'){
             $transition_position .= 'bt';
            }else if($value->field_value == '3'){
             $transition_position .= 'lr';
            }else{
              $transition_position .= 'rl';
            }
         
          }
          if($value->field_name == 'BACKGROUND_COLOR'){
              $background_color .= $value->field_value;
          }
        }
        if($background_color == '#fff'){
          $font_color = '#000';
           $color_br = '#000';
        } else{
          $font_color = '#fff';
           $color_br = '#fff';
        }
      }else{
        $project = (object)array();
        $project->name = 'Not Found Project';
        $project->total_amount = '0';
        $project->goal_amount = '0';
        $project->qrcode = '0';
        $project->percentage = '0';
        $project->id = '0';
        $background_color = '';
        $font_color = '#000';
        $color_br = '#000';
        $transition_position = '';
        $backgroun_image = '';
        
      }

      return view('project/project_donation', compact('title','project','backgroun_image','transition_position','background_color','font_color','color_br'));
     
    }

    /**
     * Determine church fund list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return //return list of church fund
     */
    public static function churchFundList(Request $request){
        $churchfundDetailObj = new \App\ChurchFund;
        $input  = $request->all();
        $churchfund = $churchfundDetailObj->church_fund_detail($input['churchid']);
        return $churchfund;
    }

    /**
     * Determine Project screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function ProjectManagement(Request $request){
        $title = 'Project List';
        return view('project/project_list', compact('title'));
    }

    /**
     * Determine project list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getProject(Request $request){
        $projectDetailObj  = new \App\Project;

        $page = datatables()->of($projectDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('church_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->church_id);
                    return $user->firstname." ".$user->lastname;
                })
                ->addColumn('church_fund_name', function ($page) {
                    $churchfundDetailObj  = new \App\ChurchFund;
                    return $churchfundDetailObj->find($page->church_fund_id)->name;
                })->addColumn('start_date', function ($page) {
                    return date('d-m-Y', strtotime($page->startdate));
                })->addColumn('end_date', function ($page) {
                  
                    if($page->enddate != ''){
                     return date('d-m-Y', strtotime($page->enddate));
                    }else{
                      return 'N/A';
                    }
                })->addColumn('amount', function ($page) {
                    return $page->goal_amount;
                })->addColumn('qr_code', function ($page) {
                    return $page->qrcode;
                })->addColumn('need_qr', function ($page) {
                    return $page->need_to_scan_qr;
                })->addColumn('donation_slab', function ($page) {
                    return $page->donation_slab_custom_amount;
                 })->addColumn('is_archive', function ($page) {
                     $projectPaymentDonationDetail = new \App\ProjectDonationPayment;
                    $total_amount_count = $projectPaymentDonationDetail->totalFundCollectionprojectamount($page->id);
                    //if(($page->enddate < date('Y-m-d') && $page->enddate != '') || $page->goal_amount < $total_amount_count){
                    if(($page->enddate < date('Y-m-d') && $page->enddate != '') || $page->is_project_archive == '1'){
                     return 'Archive';
                    }else{
                     return 'Current'; 
                    }
                    
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_project') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_project/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine add project screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add project screen
     */
    public static function addProject(Request $request){
        $title = "Add Project";
        $userDetailObj = new \App\User;
        $church_list = $userDetailObj->adminChurchList();
        return view('project/addproject', compact('title','church_list'));
    }

    /**
     * Determine method to add project with validation.
     *
     * @param  \App\Http\Requests\AddProjectAdminRequest  $request
     * @return redirect //add project method
     */

    public static function actionEditProject(Request $request){
            $projectDetailObj = new \App\Project;
            $input = $request->all();
            
            $projectDetail = $projectDetailObj->find($input['id']);

            $input['qrcode'] = mt_rand(1000000, 9999999);
            $project_image = (!empty($request->file('files')) ? $request->file('files') : '');
            $projectDetail->update($input);
            $fundname = array_unique($input['repeater'],SORT_REGULAR);
            $donationslabDetailObj = new \App\ProjectDonationSlab;
            $projectSlab = $donationslabDetailObj->delte_slab($input['id']);

            foreach($fundname as $key => $value) {
                $fundslab['project_id'] = $input['id'];
                $fundslab['amount'] = $value['fundprice'];
                $donationslabDetailObj->create($fundslab);
            } 


            $projectImageDetailObj = new \App\ProjectImage;
            foreach($input['sprovider_images_id'] as $key => $value) {
            $project = $projectImageDetailObj->find($value);
            $project->update(['project_id' => $input['id']]);
            }  
         /*   $projectImageDetailObj = new \App\ProjectImage;
            $i = 0;
            
            
                
            foreach($input['project_image'] as $key => $value) {
            if($value != null){
            $i++;
            $nameofimage = "project_image".'_'.$i;
            $image_info = General::upload_file($value, $nameofimage, "user_images");
            $image['image'] = $image_info;
            $image['project_id'] = $input['id'];
            $projectImageDetailObj->create($image);
            }
            }
            */
           
                 $request->session()->flash('message', 'Project has been update successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('project-management'));
        return response()->json($arrayMessage);

    }
    public static function actionAddProject(Request $request){
        $projectDetailObj = new \App\Project;
         $userSettingDetailObj = new \App\UserSetting;
          $projectImagedefaultDetailObj = new \App\ProjectDefaultImages;
          $projectSettingDetailObj = new \App\ProjectSetting;

        $input = $request->all();
        $input['qrcode'] = mt_rand(1000000, 9999999);
        $project_image = (!empty($request->file('files')) ? $request->file('files') : '');

       $project = $projectDetailObj->create($input); 
         
            $fundname = array_unique($input['repeater'],SORT_REGULAR);
            $donationslabDetailObj = new \App\ProjectDonationSlab;
            foreach($fundname as $key => $value) {
                $fundslab['project_id'] = $project->id;
                $fundslab['amount'] = $value['fundprice'];
                $donationslabDetailObj->create($fundslab);
            }  
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

          $projectSettingArray = array('BACKGROUND_IMAGE','TRANSITION_POSITION','BACKGROUND_COLOR');
          $projectSettingData['project_id']=$project->id;
            foreach($projectSettingArray as $projectSettingManual){
              $projectSettingData['field_name'] = $projectSettingManual;
              $projectSettingData['field_value'] = $input[$projectSettingManual];
              $projectSettingDetailObj->create($projectSettingData);
            }
           // dd($project);     
            //$projectImageDetailObj = new \App\ProjectImage;
            //$i = 0;
           
            //echo count($input['project_images_id']);

            //dd($input['project_image']);
             $projectImageDetailObj = new \App\ProjectImage;
            foreach($input['sprovider_images_id'] as $key => $value) {
                 $project_image = $projectImageDetailObj->find($value);
                 $project_image->update(['project_id' => $project->id]);
                //$i++;
               /* $nameofimage = "project_image".'_'.$i;
                $image_info = General::upload_file($value, $nameofimage, "user_images");
                $image['image'] = $image_info;
                $image['project_id'] = $project->id;*/
                //$projectImageDetailObj->create($image);
            }
        $request->session()->flash('message', 'Project has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('project-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update fund name screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editProject(Request $request,$id){
        $title = "Edit Project";
        $projectDetailObj = new \App\Project;
        $project = $projectDetailObj->project_detail($id);
       $propert_image = array();
        //$images=[];
        $project->startdate = date('Y-m-d', strtotime($project->startdate));
        if($project->enddate){
        $project->enddate =  date('Y-m-d', strtotime($project->enddate));
        }
        foreach ($project->project_images as $key => $value) {
        
            $images['images'] = General::get_file_src($value['image']);
            $images['id']=  $value['id'];
            array_push($propert_image,(object)$images);
        }
       

        $userDetailObj = new \App\User;
        $churchList = $userDetailObj->adminChurchList();

        $fundNameDetailObj = new \App\ChurchFund;
        $fundNames = $fundNameDetailObj->church_fund_detail($project->church_id);

        $donationslabDetailObj = new \App\ProjectDonationSlab;
         $project_donation_slabs = $donationslabDetailObj->donation_slab($project->id);

        return view('project/editproject', compact('title','churchList','project','images','propert_image','fundNames','project_donation_slabs'));
    }

    /**
     * Determine method to delete fund name.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteProject(Request $request, $id){
        $projectDetailObj = new \App\Project;
        $project = $projectDetailObj->find($id);
        try{
            DB::beginTransaction();
            $project->update(['is_deleted' => '1']);
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
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'Project has been deleted successfully.');
        return redirect('project');
    }

    /**
     * Determine Fund Name screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function FundManagement(Request $request){
        $title = 'Fund Name List';
        return view('fundname/fundname', compact('title'));
    }
    
    /**
     * Determine list of fund name with datatable.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response //list of fund name
     */
    public static function getFundName(Request $request){
        $churchfundDetailObj  = new \App\ChurchFund;
        $page = datatables()->of($churchfundDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('church_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->church_id);
                    return $user->firstname." ".$user->lastname;
                })->addColumn('fund_name', function ($page) {
                    return $page->name;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_fundname') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_fundname/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                    })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();  
        return $page;
    }

    /**
     * Determine fund name screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function addFundName(Request $request){
        $title = "Add Fund Name";
        $userDetailObj = new \App\User;
        $church_list = $userDetailObj->adminChurchList();
        return view('fundname/addfundname', compact('title','church_list'));
    }

    /**
     * Determine method to add fund name.
     *
     * @param  \App\Http\Requests\ChurchFundAdminRequest  $request
     * @return response
     */
    public static function actionAddFundName(ChurchFundAdminRequest $request){
        $input = $request->all();
        $churchfundDetailObj = new \App\ChurchFund;
        $churchfundDetailObj->create($input);
        $request->session()->flash('message', 'Fund name has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('fundname-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update fund name screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editFundName(Request $request,$id){
        $title = "Edit Fund Name";
        $churchfundDetailObj = new \App\ChurchFund;
        $churchfund = $churchfundDetailObj->find($id);
        $userDetailObj = new \App\User;
        $church_list = $userDetailObj->adminChurchList();
        return view('fundname/editfundname', compact('title','church_list','churchfund'));
    }

    /**
     * Determine method to update fund name.
     *
     * @param  \App\Http\Requests\EditFundNameAdminRequest  $request
     * @return response
     */
    public static function actionEditFundName(EditFundNameAdminRequest $request){
        $churchfundDetailObj = new \App\ChurchFund;
        $input = $request->all();
        $churchfund = $churchfundDetailObj->find($input['id']);
        $request->session()->flash('message', 'Fund name has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('fundname-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete fund name.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteFundName(Request $request, $id){
        $churchfundDetailObj = new \App\ChurchFund;
        $churchfund = $churchfundDetailObj->find($id);
        $churchfund->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Fund name has been deleted successfully.');
        return redirect('fundname');
    }

    public static function projectImageUploader(Request $request) {
        $input = $request->all();
        $id = intval($input["id"]);
        $return = [];
        $counter = 1;

        if ($request->hasFile("project_image")) {
            $images = $request->file("project_image");

            $return = [
                "initialPreview" => [],
                "initialPreviewConfig" => [],
                "initialPreviewAsData" => false,
            ];
            foreach ($images as $key => $image) {
                $nameofimage = 'project_image' . '_' . mt_rand(100000, 999999);
                $image = General::upload_file($image, $nameofimage, "user_images");
                $image_url = General::get_file_src($image);
                
                $return["initialPreview"][] = '<img src="' . $image_url . '" class="file-preview-image kv-preview-data" />'
                        . '<input type="hidden" name="project_images_id[]" value="' . $image . '" />';
                $counter++;
            }
        }
        return collect($return)->toJson();
    }

    public static function upload_myfile(Request $request) {

        $key = (!empty($request->input('key')) ? $request->input('key') : '');
        $isdelete = DB::table('project_images')->where('id', $key)->delete();
        $status = '1';
        return $status;
    }
    public static function service_provider_image_upload(Request $request) {
           $input = $request->all();
           
    $projectImageDetailObj = new \App\ProjectImage;
    $i = 0;

            $return = [];
            $counter = intval($input['file_id']) + 1;
    
            if ($input['project_image']) {
                 $return = [
                    "initialPreview" => [],
                    "initialPreviewConfig" => [],
                    "initialPreviewAsData" => false,
                ];
                 $projectImageDetailObj = new \App\ProjectImage;
                foreach($input['project_image'] as $key => $value) {
                if($value != null){
                $i++;
                $nameofimage = "project_image".'_'.$i;
                $image_info = General::upload_file($value, $nameofimage, "user_images");
                $image['image'] = $image_info;
                $image['project_id'] = $input['id'];
                $last_id = $projectImageDetailObj->create($image);
                 $image_url = General::get_file_src($image_info);

                $return["initialPreview"][] = '<img src="' . $image_url . '" class="file-preview-image kv-preview-data" />'
                        . '<input type="hidden" name="sprovider_images_id[]" value="' . $last_id->id . '" />';
                $return["initialPreviewConfig"][] = [
                    'url' => route('upload_myfile'),
                    'key' => $last_id->id,
                    'extra' => [
                        "id" => $last_id->id,
                        "_token" => csrf_token()
                    ]
                ];
                }
                } 
            }
            return collect($return)->toJson();
        }

    public function redirectDeepLink(Request $request){
            
            try {

                $device = $this->isMobileDevice();
                $id = $request->input('v');

                $app = config('constants.DEEPLINKING.APP') . $id;
                $data = array();


            if ($device == 'iPhone') {

                $data['primaryRedirection'] = $app;
                $data['secndaryRedirection'] = config('constants.DEEPLINKING.ANDROIDAPPSTORE');
               $redirect = 'com.goodtogive://postEvent/'.$id; 

            }else if ($device == 'Android'){

                $data['primaryRedirection'] = $app;
                $data['secndaryRedirection'] = config('constants.DEEPLINKING.IOSAPPSTORE');
                $redirect = 'com.goodtogive://postEvent/'.$id;
                

            }else{
                
                /*$result =   Post::select('id as video_id','title as video_title',
                DB::Raw('IFNULL( `post_video` , "" ) as post_video'))
                ->where('id',$id);
                 $result= $result->get()->first();
                  $result->post_video = asset('uploads/videos/'.$result->post_video);

                $redirect = $result->post_video;*/
                //$redirect = config('constants.DEEPLINKING.WEBSITE');
                //echo $redirect; exit;
               // $data['WebRedirection'] = config('constants.DEEPLINKING.WEBSITE').$id;
                // $redirect = config('constant.DEEPLINKING.WEBSITE');
                echo "Web not awaiable here";
                exit;
               return redirect($redirect);
            }
            return redirect($redirect);
            die;
                $message = 'Deep link URL';
                return self::json_response($data,$message,200);

            } catch (Exception $e) {
                Log::error(__METHOD__ . ' ' . $e->getMessage());
                return Utilities::responseError(__('messages.SOMETHING_WENT_WRONG') . $e->getMessage());
            }

     }

      private function isMobileDevice() {

        $aMobileUA = array(
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
        );
        //Return true if Mobile User Agent is detected
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
          if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return $sMobileOS;
          }
        }
        //Otherwise return false..
        return false;
      }


}
