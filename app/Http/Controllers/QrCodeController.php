<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Determine Qr Code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return //return list of qr code
     */
    public static function qrCodeManagement(Request $request){
        $title = "Qr Code List";
        return view('qrcode/qrcode', compact('title'));
    }

    /**
     * Determine qrcode list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getQrCode(Request $request){
        $projectDetailObj  = new \App\Project;
        $page = datatables()->of($projectDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('church_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->find($page->church_id);
                    return $user->firstname." ".$user->lastname;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/view_qr_code') . '/' . $page->id .'" class="btn btn-success"><i class="la la-eye" aria-hidden="true"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function viewQrCode(Request $request,$id){
        $title = "View Qr Code";
        $projectDetailObj = new \App\Project;
        $project = $projectDetailObj->find($id);
        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($project->church_id);
        $qrcode_image = General::get_file_src($project->id.'-code.png');
        return view('qrcode/view_qrcode', compact('title','project','user','qrcode_image'));
    }

    /**
     * Determine view and download qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function getGenerateQrCode(Request $request){
        $title = "Generate Qr Code";
        $projectDetailObj = new \App\Project;
        $input = $request->all();
        $project = $projectDetailObj->find($input['id']);
        try{
            DB::beginTransaction();
            $explode_name = explode(' ',$project->name);

            $qrcode = mt_rand(1000000, 9999999).$explode_name[0].$project->id;
            // QrCode::size(500)->format('png')->generate($qrcode, public_path('storage/public/storage/user_images/'.$input['id'].'-code.png'));
            $project->update(['qrcode'=>$qrcode,'need_to_scan_qr'=>'enable']);
            DB::commit();
           /* Transaction successful. */
        }catch(\Exception $e){   
            DB::rollback();
            /* Transaction failed. */
        }
        return "1" ;
    }

    /**
     * Determine method to delete qr code.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteQrCode(Request $request, $id){
        $projectDetailObj = new \App\Project;
        $project = $projectDetailObj->find($id);
        $projectSlab->update(['qrcode' => '','need_to_scan_qr' => 'disable']);
        $request->session()->flash('message', 'Qr code has been deleted successfully.');
        return redirect('qrcode');
    }
}
