<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddGlobalSettingAdminRequest;

use DB;
class GlobalSettingController extends Controller
{


    /**
     * Determine Global Setting screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function addProjectImageSetting(Request $request){
        $projectImageDetailObj = new \App\ProjectDefaultImages;
        $title = 'Project Images Setting';
        $propert_image = array();
        $project = $projectImageDetailObj->projectImages();
        
        foreach ($project as $key => $value) {
            $images['images'] = General::get_file_src($value->image);
            $images['id']=  $value->id;
            array_push($propert_image,(object)$images);
        }

        return view('projectimagesetting/imagesettings', compact('title','propert_image'));
    }
  
  public static function upload_myfile_default(Request $request) {

        $key = (!empty($request->input('key')) ? $request->input('key') : '');
        $isdelete = DB::table('project_default_images')->where('id', $key)->delete();
        $status = '1';
        return $status;
    }

    public static function service_provider_image_upload_default(Request $request) {
           $input = $request->all();
           
    $projectImageDetailObj = new \App\ProjectDefaultImages;
    $i = 0;

            $return = [];
            $counter = intval($input['file_id']) + 1;
    
            if ($input['project_image']) {
                 $return = [
                    "initialPreview" => [],
                    "initialPreviewConfig" => [],
                    "initialPreviewAsData" => false,
                ];
                 $projectImageDetailObj = new \App\ProjectDefaultImages;
                foreach($input['project_image'] as $key => $value) {
                if($value != null){
                $i++;
                $nameofimage = "project_image".'_'.$i;
                $image_info = General::upload_file($value, $nameofimage, "user_images");
                $image['image'] = $image_info;

                $last_id = $projectImageDetailObj->create($image);
                 $image_url = General::get_file_src($image_info);

                $return["initialPreview"][] = '<img src="' . $image_url . '" class="file-preview-image kv-preview-data" />'
                        . '<input type="hidden" name="sprovider_images_id[]" value="' . $last_id->id . '" />';
                $return["initialPreviewConfig"][] = [
                    'url' => route('upload_myfile_default'),
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

    /**
     * Determine Global Setting screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function GlobalSettingManagement(Request $request){
        $title = 'General Setting';
        return view('globalsetting/globalsetting', compact('title'));
    }

    /**
     * Determine global setting list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getGlobalSetting(Request $request){
        $siteGeneralSettingDetailObj  = new \App\SiteGeneralSetting;
        $page = datatables()->of($siteGeneralSettingDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_global_setting') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_global_setting/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine add global setting screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add global setting screen
     */
    public static function addGlobalSetting(Request $request){
        $title = "Add General Setting";
        return view('globalsetting/addglobalsetting', compact('title'));
    }

    /**
     * Determine method to add global setting with validation.
     *
     * @param  \App\Http\Requests\AddGlobalSettingAdminRequest  $request
     * @return redirect //add global setting method
     */
    public static function actionAddGlobalSetting(AddGlobalSettingAdminRequest $request){
        $siteGeneralSettingDetailObj = new \App\SiteGeneralSetting;
        $input = $request->all();
        if( $input['option_image'] != "" && $input['option_value'] != ""){
            $request->session()->flash('message', 'Please fill one from option value and option image.');
            $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('global-setting-add'));
            return response()->json($arrayMessage);
        }
        if (isset($input['option_image']) && $input['option_image'] != "") {
            $nameofimage = 'general_setting'.'_'.date('H:i:s');
            $input['option_value'] = General::upload_file($input['option_image'], $nameofimage, "user_images");
            $input['type'] ='1';
        }
        $siteGeneralSettingDetailObj->create($input);
        $request->session()->flash('message', 'Global setting has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('global-setting-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update global setting screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editGlobalSetting(Request $request,$id){
        $title = "Edit General Setting";
        $siteGeneralSettingDetailObj = new \App\SiteGeneralSetting;
        $siteGeneralSetting = $siteGeneralSettingDetailObj->find($id);
        if( $siteGeneralSetting->type =='1'){
            $siteGeneralSetting['imageurl'] = General::get_file_src($siteGeneralSetting->option_value);

        }
        return view('globalsetting/editglobalsetting', compact('title','siteGeneralSetting'));
    }

    public static function actionEditGlobalSetting(AddGlobalSettingAdminRequest $request){
        $siteGeneralSettingDetailObj = new \App\SiteGeneralSetting;
        $input = $request->all();
        $siteGeneralSetting = $siteGeneralSettingDetailObj->find($input['id']);
        if( $input['option_image'] != "" && $input['option_value'] != ""){
            $request->session()->flash('message', 'Please fill one from option value and option image.');
            $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('global-setting-add'));
            return response()->json($arrayMessage);
        }
        if (isset($input['option_image']) && $input['option_image'] != "") {
            $nameofimage = 'general_setting'.'_'.date('H:i:s');
            $input['option_value'] = General::upload_file($input['option_image'], $nameofimage, "user_images");
            $input['type'] ='1';
        }

        if( $input['option_image'] == "" && $input['option_value'] == "" &&   $input['old_image']!= '' ){
            $input['option_value'] = $input['old_image'];
            $input['type'] ='1';
        }

        $siteGeneralSetting->update($input);
        $request->session()->flash('message', 'Global setting has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('global-setting-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete global setting.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteGlobalSetting(Request $request, $id){
        $siteGeneralSettingDetailObj = new \App\SiteGeneralSetting;
        $siteGeneralSetting = $siteGeneralSettingDetailObj->find($id);
        $siteGeneralSetting->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Global setting has been deleted successfully.');
        return redirect('globalsetting');
    }
}
