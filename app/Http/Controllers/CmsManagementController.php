<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddCmsAdminRequest;
use App\Http\Requests\Admin\EditCmsAdminRequest;
use DB;

class CmsManagementController extends Controller
{
    /**
     * Determine CMS Management screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function cmsManagement(Request $request){
        $title = 'CMS List';
        return view('cms/cms', compact('title'));
    }

    /**
     * Determine CMS Management list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getCmsList(Request $request){
        $cmsManagementDetailObj  = new \App\CmsManagement;
        $page = datatables()->of($cmsManagementDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_cms_management') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_cms_management/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
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
        $title = "Add CMS";
        return view('cms/addcms', compact('title'));
    }

    /**
     * Determine method to add cms management with validation.
     *
     * @param  \App\Http\Requests\AddCmsAdminRequest  $request
     * @return redirect //add project method
     */
    public static function actionAddCms(AddCmsAdminRequest $request){
        $cmsManagementDetailObj = new \App\CmsManagement;
        $input = $request->all();
        try{
            DB::beginTransaction();
            // $input['slug'] = str_replace(' ', '-', strtolower(ltrim(rtrim($input['title']))));
            $cmsManagementDetailObj->create($input);
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        $request->session()->flash('message', 'Content has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('cms-management'));
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
        $cmsManagementDetailObj = new \App\CmsManagement;
        $cmsManagement = $cmsManagementDetailObj->find($id);
        return view('cms/editcms', compact('title','cmsManagement'));
    }

    /**
     * Determine method to update cms management.
     *
     * @param  \App\Http\Requests\EditCmsAdminRequest  $request
     * @return response 
     */
    public static function actionEditCms(EditCmsAdminRequest $request){
        $title = 'Edit CMS';
        $input = $request->all();
        $cmsManagementDetailObj  = new \App\CmsManagement;
        $cmsManagement  = $cmsManagementDetailObj->find($input['id']);
        $cmsManagement->update($input);
        $request->session()->flash('message', 'Content has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('cms-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete cms.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteCms(Request $request, $id){
        $cmsManagementDetailObj  = new \App\CmsManagement;
        $cmsManagement = $cmsManagementDetailObj->find($id);
        $cmsManagement->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Content has been deleted successfully.');
        return redirect('cms_management');
    }
}
