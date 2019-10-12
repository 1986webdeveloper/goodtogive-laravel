<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Datatables;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Support\Facades\URL;
use DB;

class UserCardController extends Controller
{
    /**
     * Determine user card management.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return view
     */
    public function UserCardManagement(Request $request) {
        $title = 'User Card List';
        return view('usercard/usercard', compact('title'));
    }

    /**
     * Determine user card list.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return return 
     */
    public static function getUserCard(Request $request){
        $userCardInfoDetailObj  = new \App\UserCardInfo;
        $userCardInfo = $userCardInfoDetailObj->userCard();
        $page = datatables()->of($userCardInfo)
                ->addColumn('user_name', function ($page) {
                    $userDetailObj = new \App\User;
                    $user = $userDetailObj->find($page->user_id);
                    return $user->firstname.' '.$user->lastname;
                })->addColumn('card_info', function ($page) {
                    $userCardInfoDetailObj  = new \App\UserCardInfo;
                    $userCardInfo = $userCardInfoDetailObj->userWiseCard($page->user_id);
                    $cardInfo = '';
                    foreach($userCardInfo as $userCard){
                        $cardInfo .= $userCard->card_number.'<br/>';
                    }
                    return $cardInfo;
                })->addColumn('action', function ($page) {
                    return '<a href="'. URL('delete_user_card/'.$page->user_id ) .'" class="btn btn-danger delete" ><i class="la la-trash-o"></i></a>';
                })->rawColumns(['action','card_info'])
                  ->addIndexColumn()
                  ->toJson();
                return $page;
    }

    /**
     * Determine method to delete user card.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return response
     */
    public static function deleteUserCard(Request $request, $id){
        $userCardDetailObj = new \App\UserCardInfo;
        $userCard = $userCardDetailObj->userCardDelete($id);
        $request->session()->flash('message', 'User Card has been deleted successfully.');
        return redirect('user_card');
    }
}
