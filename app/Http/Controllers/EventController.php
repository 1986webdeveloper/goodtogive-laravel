<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\Admin\AddEventAdminRequest;
use DB;

class EventController extends Controller
{
    /**
     * Determine Project screen .
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public static function EventManagement(Request $request){
        $title = 'Event List';
        return view('event/event', compact('title'));
    }

    /**
     * Determine Event list datatables.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function getEvent(Request $request){
        $eventDetailObj  = new \App\Event;
        $page = datatables()->of($eventDetailObj->all()->where('is_deleted','0')->sortByDesc('id'))
                ->addColumn('user_name', function ($page) {
                    $userDetailObj  = new \App\User;
                    $user = $userDetailObj->userDetail($page->user_id);
                    return $user->firstname." ".$user->lastname;
                })->addColumn('start_date', function ($page) {
                    return date('d-m-Y', strtotime($page->start_date));
                })->addColumn('end_date', function ($page) {
                    return date('d-m-Y', strtotime($page->end_date));
                })->addColumn('action', function ($page) {
                    return '<a style="margin-right:10px" href="' . URL::to('/edit_event') . '/' . $page->id .'" class="btn btn-success"><i class="la la-edit" aria-hidden="true"></i></a><a href="'. URL('delete_event/'.$page->id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action'])
                ->addIndexColumn()
                ->toJson();   
        return $page;
    }

    /**
     * Determine add event screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view //add event screen
     */
    public static function addEvent(Request $request){
        $title = "Add Event";
        $userDetailObj = new \App\User;
        $churchList = $userDetailObj->adminChurchList();
        return view('event/addevent', compact('title','churchList'));
    }

    /**
     * Determine method to add task with validation.
     *
     * @param  \App\Http\Requests\AddEventAdminRequest  $request
     * @return redirect //add task method
     */
    public static function actionAddEvent(AddEventAdminRequest $request){
        $eventDetailObj = new \App\Event;
        $input = $request->all();
        $input['start_date'] = date("Y-m-d H:i", strtotime($input['start_date'].' '.date("H:i", strtotime($input['start_time']))));
        $input['end_date'] = date("Y-m-d H:i", strtotime($input['end_date'].' '.date("H:i", strtotime($input['end_time']))));
        $eventDetailObj->create($input);
        $request->session()->flash('message', 'Event has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('event-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine update event screen.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view 
     */
    public static function editEvent(Request $request,$id){
        $title = "Edit Event";
        $eventDetailObj = new \App\Event;
        $event = $eventDetailObj->find($id);
        $userDetailObj = new \App\User;
        $churchList = $userDetailObj->adminChurchList();
        $start_date = \Carbon\Carbon::parse($event->start_date);
        $event['startdate'] = $start_date->format('Y-m-d');
        $event['starttime'] = $start_date->format('H:i:s');
         $start_date = \Carbon\Carbon::parse($event->end_date);
        $event['enddate'] = $start_date->format('Y-m-d');
        $event['endtime'] = $start_date->format('H:i:s');
        return view('event/editevent', compact('title','churchList','event'));
    }

    /**
     * Determine method to add task with validation.
     *
     * @param  \App\Http\Requests\AddEventAdminRequest  $request
     * @return redirect //add task method
     */
    public static function actionEditEvent(AddEventAdminRequest $request){
        $eventDetailObj = new \App\Event;
        $input = $request->all();
        $event= $eventDetailObj->find($input['id']);
           $input['start_date'] = date("Y-m-d H:i", strtotime($input['start_date'].' '.date("H:i", strtotime($input['start_time']))));
        $input['end_date'] = date("Y-m-d H:i", strtotime($input['end_date'].' '.date("H:i", strtotime($input['end_time']))));
        // $input['start_date'] = date("Y-m-d H:i", strtotime($input['start_date'].' '.date("H:i", strtotime($input['start_time']))));
        // $input['end_date'] = date("Y-m-d H:i", strtotime($input['end_date'].' '.date("H:i", strtotime($input['end_time']))));
        $event->update($input);
        $request->session()->flash('message', 'Event has been added successfully.');
        $arrayMessage  =  array('status'=>'success','redirectUrl'=> route('event-management'));
        return response()->json($arrayMessage);
    }

    /**
     * Determine method to delete event.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteEvent(Request $request, $id){
        $eventDetailObj = new \App\Event;
        $event = $eventDetailObj->find($id);
        $event->update(['is_deleted' => '1']);
        $request->session()->flash('message', 'Event has been deleted successfully.');
        return redirect('event');
    }
}
