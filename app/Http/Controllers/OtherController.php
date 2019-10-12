<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtherController extends Controller
{
    public static function check_email() {
        $id = $_GET['id'];
        $email = $_GET['email'];
        $userDetailObj  = new \App\User;
        // print_r('sdffs');exit;
        if ($id) {
            $user = $userDetailObj->user_login_check_admin($email,$id);
            if ($user) {
                return 'false';
            } else {
                return 'true';
            }
        } else {

            $user = $userDetailObj->user_login_check_admin($email);
            if ($user) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }

    public static function check_reference() {
        $id = $_GET['id'];
        $church_reference_id = $_GET['church_reference_id'];
        $userDetailObj  = new \App\User;
        // print_r('sdffs');exit;
        if ($id) {
            $user = $userDetailObj->user_reference_check_admin($church_reference_id,$id);
            if ($user) {
                return 'false';
            } else {
                return 'true';
            }
        } else {

            $user = $userDetailObj->user_reference_check_admin($church_reference_id);
            if ($user) {
                return 'false';
            } else {
                return 'true';
            }
        }
    }

    public static function check_mobile(Request $request) {
        $mobile = $request->input('mobile');
        $id = $request->input('id');
        $userDetailObj  = new \App\User;

        if ($id) {
            $user = $userDetailObj->user_mobile_check($mobile,$id);
            if($user)
                return 'false';
            else
                return 'true';
        }else{

            $user = $userDetailObj->user_mobile_check($mobile,$id);
            if ($user)
                return 'false';
            else
                return 'true';
        }
    }

    public static function check_user_role(Request $request) {
        $user_role_id = $request->input('user_role_id');
        print_r($user_role_id);
        if ($user_role_id != 3)
            return 'true';
        else
            return 'false';
    }
}
