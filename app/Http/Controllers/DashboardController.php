<?php
/**
 * An DashboardController Class 
 *
 * The controller class is using to controll all dashboard funcationality.
 *
 * @package    Good To Give
 * @subpackage Common
 * @author     Acquaint Soft Developer 
 */
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Determine main screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function Index(){
        $title = 'Dashboard';
        return view('dashboard/dashboard', compact('title'));
    }

    /**
     * Determine dashboard of admin.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public function Dashboard(Request $request) {         
        $title = 'Dashboard';
        $userDetailObj  = new \App\User;
        $projectDetailObj = new \App\Project;
        $fundDetailObj = new \App\ProjectDonationPayment;
        $userCount =   count($userDetailObj->allUserList());
        $projectCount = $projectDetailObj->project_count();
        $totalFund = $fundDetailObj->fund_ammount();
        return view('dashboard/dashboard', compact('title', 'userCount', 'projectCount', 'totalFund'));
    }

    /**
     * Determine dashboard list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function dashboardList(Request $request){
        $eventDetailObj  = new \App\Event;     
        $page = datatables()->of($eventDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('church_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->user_id);
                    return $user->firstname." ".$user->lastname;
                })
                ->addColumn('church_reference_id', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->user_id);
                    return $user->church_reference_id;
                })
                ->addColumn('title', function ($page) {
                    return $page->title;
                })->addColumn('description', function ($page) {
                    return $page->description;
                })->addColumn('start_date', function ($page) {
                    return date('d-m-Y', strtotime($page->start_date));
                })->addColumn('end_date', function ($page) {
                    return date('d-m-Y', strtotime($page->end_date));
                })->addIndexColumn()
                    ->toJson();   
                return $page;
    }
}
