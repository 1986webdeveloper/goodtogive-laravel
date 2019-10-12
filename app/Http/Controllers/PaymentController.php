<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use DB;

class PaymentController extends Controller
{
    /**
     * Determine payment management.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public function PaymentManagement(Request $request) {
        $title = 'Payment / Donation List ';
        return view('payment/payment', compact('title'));
    }

    /**
     * Determine Payment list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function getPaymentList(Request $request){
        $projectDonationPaymentDetailObj  = new \App\ProjectDonationPayment;
        $projectDonationPayment = $projectDonationPaymentDetailObj->all()->where('is_deleted','0');
        $page = datatables()->of($projectDonationPayment)
                ->addColumn('donor_name', function ($page) {
                    $userDetailObj = new \App\User;
                    $user = $userDetailObj->find($page->user_id);
                    return $user->firstname." ".$user->lastname;
                })->addColumn('project_name', function ($page) {
                    $projectDetailObj = new \App\Project;
                    return $projectDetailObj->find($page->project_id)->name;
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/view_payment_detail') . '/' . $page->id .'" class="btn btn-success"><i class="la la-eye" aria-hidden="true"></i></a>';
                })->rawColumns(['action'])
                    ->addIndexColumn()
                    ->toJson();   
                    return $page;
    }

    /**
     * Determine view of project donation payment.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function viewPaymentDetail(Request $request,$id){
        $title = "View Payment / Donation";
        $projectPaymentDonationDetailObj = new \App\ProjectDonationPayment;
        $projectPaymentDonation = $projectPaymentDonationDetailObj->find($id);
        
        $userDetailObj = new \App\User;
        $user = $userDetailObj->find($projectPaymentDonation->user_id);

        $projectDetailObj = new \App\Project;
        $project = $projectDetailObj->find($projectPaymentDonation->project_id);
        $project->startdate = date('d-m-Y', strtotime($project->startdate));
        $project->enddate = date('d-m-Y', strtotime($project->enddate));
        return view('payment/view_payment', compact('title','project','user','projectPaymentDonation'));
    }
}
