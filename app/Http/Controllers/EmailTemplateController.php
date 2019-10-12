<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddEmailTemplateAdminRequest;
use DB;

class EmailTemplateController extends Controller
{
    /**
     * Determine Email Template screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function emailTemplateManagement(Request $request){
        $title = 'Email Template';
        return view('emailtemplate/emailtemplate', compact('title'));
    }

    /**
     * Determine Email datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getEmailTemplate(Request $request){
        $emailTemplateDetailObj  = new \App\EmailTemplate;
        $page = datatables()->of($emailTemplateDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_email_template') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine add email template screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add event screen
     */
    public static function addEmailTemplate(Request $request){
        $title = "Add Email Template";
        return view('emailtemplate/addemailtemplate', compact('title'));
    }

    /**
     * Determine method to add email template with validation.
     *
     * @param  \App\Http\Requests\AddEmailTemplateAdminRequest  $request
     * @return redirect //add email template method
     */
    public static function actionAddEmailTemplate(AddEmailTemplateAdminRequest $request){
        $emailTemplateDetailObj = new \App\EmailTemplate;
        $input = $request->all();
        $emailTemplateDetailObj->create($input);
        $request->session()->flash('message', 'Email content has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('email-template-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update email template screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editEmailTemplate(Request $request,$id){
        $title = "Edit Email Template";
        $emailTemplateDetailObj = new \App\EmailTemplate;
        $emailTemplate = $emailTemplateDetailObj->find($id);
        return view('emailtemplate/editemailtemplate', compact('title','emailTemplate'));
    }

    /**
     * Determine method to update email template.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function actionEditEmailTemplate(Request $request){
        $emailTemplateDetailObj = new \App\EmailTemplate;
        $input = $request->all();
        
        $emailTemplate = $emailTemplateDetailObj->find($input['id']);
        $request->session()->flash('message', 'Email content has been updated successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('email-template-management'));
        return response()->json($arrayMessage);
    }
}
