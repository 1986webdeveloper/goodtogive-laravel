<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\GeneralController AS General;
use App\Http\Controllers\DynamicCSVController AS CSVController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\File;
use Storage;
use App\Service;
use App\Model;
use App\Mail\Quote;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Omnipay\Common\Helper;
use Omnipay\Common\CreditCard;
use Form\Server\Direct;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* Generate csv every day donation list */
        
        $schedule->call(function () {
            $App_url = config('app.url');
            $url =  $App_url.'csv'; 
            $handal_date = date("d-m-Y"); 
            $filename = 'storage/csv/GTG-'.$handal_date.'.csv';
            $curlCh = curl_init();
            curl_setopt($curlCh, CURLOPT_URL, $url);
            curl_setopt($curlCh, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlCh, CURLOPT_SSLVERSION,3);
            $curlData = curl_exec ($curlCh);
            curl_close ($curlCh);
           $downloadPath = public_path($filename);
            $file = fopen($downloadPath, "w+");
            fputs($file, $curlData);
            fclose($file);
       
          })->cron('* * * * *');

        /* Generate csv every day donation list */


        /* Weekly send notification */

        $schedule->call(function () {
            $notificationDetailObj = new \App\UserNotification;
            $notification = $notificationDetailObj->notification_list_weekly();
            foreach ($notification as $key => $value) {
                $pDate = strtotime($value->date.'+ 1 week');
                $notificationdate = date('d-m-Y',$pDate);
                if($notificationdate == date('d-m-Y')){
                    $value->extra_data = json_decode($value->extra_data);
                    $extra_notification_data = $notificationDetailObj->weekly_send_notification($value->id,$value->from_user_id,$value->user_id,$value->type,$value->extra_data);
                }
            }
        })->cron('* * * * *');

        /* Weekly send notification */

        /* start End date with daily recurring task */

         $schedule->call(function () {
                $taskDetailObj = new \App\Task;
                $taskRecurringDetails  = new \App\TaskRecurring;
                $UserTaskAssignObj  = new \App\UserTaskAssign;
                $taskgroupmemberDetailObj = new \App\TaskGroupMember;
                $UserNotificationObj  = new \App\UserNotification;
                $userDetailObj = new \App\User;
                $taskDetailObjlist = $taskDetailObj->task_list_all();
                
                foreach ($taskDetailObjlist as $key => $value) {
                   $retunr_recurring_date = $taskDetailObj->task_assign_create_recurring($value);
                }
            })->daily();

         /* End end date with daily recurring task */
         
         /*start task recurring type wise*/

            $schedule->call(function () {

                $taskDetailObj = new \App\Task;
                $taskRecurringDetails  = new \App\TaskRecurring;
                $UserTaskAssignObj  = new \App\UserTaskAssign;
                $taskgroupmemberDetailObj = new \App\TaskGroupMember;
                $UserNotificationObj  = new \App\UserNotification;
                $userDetailObj = new \App\User;
                $taskRecurrngDetailObjlist = $taskDetailObj->task_list_all_recurring();

                foreach ($taskRecurrngDetailObjlist as $key => $value) {
                    $date = $value->date;
                    if($value->recurring == '1' && date('Y-m-d', strtotime('+1 day', strtotime($date))) == date('Y-m-d')){
                        $retunr_recurring_date = $taskDetailObj->task_assign_create_recurring($value);
                    }else if($value->recurring == '2' && date('Y-m-d', strtotime('+1 week', strtotime($date))) == date('Y-m-d')){
                        $retunr_recurring_date = $taskDetailObj->task_assign_create_recurring($value);
                    }else if($value->recurring == '3' && date('Y-m-d', strtotime('+1 months', strtotime($date))) == date('Y-m-d')){
                        $retunr_recurring_date = $taskDetailObj->task_assign_create_recurring($value);
                    }else if($value->recurring == '4' && date('Y-m-d', strtotime('+1 year', strtotime($date))) == date('Y-m-d')){
                        $retunr_recurring_date = $taskDetailObj->task_assign_create_recurring($value);
                    } 
                } 
            })->daily();

            /*start task recurring type wise*/

               /* Stop repeat donation when project enddate  archieve */
         
         $schedule->call(function () {
            $projectDetailObj = new \App\Project;
            $userDetailObj  = new \App\User;
            $projectRepeatDonation  = new \App\ProjectRepeatDonation;
            $UserNotificationObj  = new \App\UserNotification;
            $project_list = $projectDetailObj->archieve_project_list();

            foreach ($project_list as $key => $value) {

                /* Send Notification church */
                $repepat_user_list = $projectRepeatDonation->get()->where('project_id', $value->id)->where('is_repeat_status','0');
                
                if($repepat_user_list){
                foreach ($repepat_user_list as $key => $values) {
                    
                    $church_details = $userDetailObj->find($value->church_id);
                    
                    $user = $userDetailObj->find($values->user_id);
                    $church_detail = array();
                    $church_detail['user_id'] = $user->id;
                    $church_detail['user_name'] = $user->firstname;
                    $church_detail['email'] = $user->email;
                    $church_detail['user_role_id'] = $user->user_role_id;
                    $church_detail['mobile'] = $user->mobile;
                    $church_detail['from_user_id'] = $user->id;
                    $church_detail['church_id'] = $church_details->id;
                    $church_detail['church_name'] = $church_details->firstname;
                    $church_detail['referral_id'] = $church_details->referral_id;
                    $church_detail['project_id'] = $value->id;
                    $church_detail['project_name'] = $value->name;
                    $UserNotificationObj->send_notification($church_details->id,$user->id, "repeat_donation_stop", $church_detail,$church_details->id,'');

                  $update_project_repeat = $projectRepeatDonation->update_project_idwise($values->id);
                  
                }
               }
                /* Send Notification church */
            }
         })->daily();
        // })->cron('* * * * *');

         /* Stop repeat donation when project enddate  archieve */

            /*repeat donation*/

            $schedule->call(function () {
                
                $projectRepeatDonation  = new \App\ProjectRepeatDonation;
               $donation_list = $projectRepeatDonation->find_donation_list();
             
                foreach ($donation_list as $key => $value) {

                   if($value->repeat_type == '1'){
                        $date = date('Y-m-d', strtotime('+1 week', strtotime($value->repeat_date)));
                        if($date == date('Y-m-d')){
                             $doantion_status = $projectRepeatDonation->repeat_donation_cronjob($value);
                          
                        }
                    }else if($value->repeat_type == '2'){
                        $date = date('Y-m-d', strtotime('+1 months', strtotime($value->repeat_date)));
                        if($date == date('Y-m-d')){
                            $doantion_status = $projectRepeatDonation->repeat_donation_cronjob($value);

                        }
                    }else if($value->repeat_type == '3'){
                        $date = date('Y-m-d', strtotime('+4 months', strtotime($value->repeat_date)));
                        if($date == date('Y-m-d')){
                             $doantion_status = $projectRepeatDonation->repeat_donation_cronjob($value);

                        }
                    }else{
                        $date = date('Y-m-d', strtotime('+1 year', strtotime($value->repeat_date)));
                        if($date == date('Y-m-d')){
                             $doantion_status = $projectRepeatDonation->repeat_donation_cronjob($value);

                        }
                    }
                }
            })->daily();
            //})->cron('* * * * *');

         /*repeat donation*/


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
