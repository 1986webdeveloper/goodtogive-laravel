<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class UserNotification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','from_user_id','type','extra_data','date','date','is_read','notification_option','notification_type', 'message', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function notification_list($page,$getdata,$user_id){
        $data = array();
        if( $user_id != null )
            $data =  UserNotification::where('user_id',$user_id)
                        ->where('is_deleted','0')
                        ->where('is_read','0')
                        ->where('notification_option','0')
                        ->orderBy('id','DESC');
            if($getdata == '1'){
                    $data = $data->paginate(10)->toArray();  
            }else{
                    $data = $data->skip($page)->take(10)->get();
            }
                       
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function notification_detail($id){
        $data = array();
        if( $id != null )
            $data =  UserNotification::where('id',$id)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

      public function delete_notification($id){
         $data = array();
        if( $id  !=  null )
            $data =  UserNotification::where('extra_data','LIKE','%"task_id":'.$id.',%')            
                        ->delete();
        return $data;
      }

       /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

        public function delete_notification_group($id){
         $data = array();
        if( $id  !=  null )
            $data =  UserNotification::where('extra_data','LIKE','%"group_id":'.$id.',%')            
                        ->delete();
        return $data;
      }
      
       /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

      public function update_notification_read($id){
        $notifications_data = [
            "is_read" => "1",
        ];
        $data = DB::table('user_notifications')->where('id', $id)->update($notifications_data);
        return $data;
      }

       /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

      public function clear_all_notification($user_id){
        $notifications_data = [
            "is_read" => "1",
        ];
        $data = DB::table('user_notifications')->where('user_id', $user_id)->where('notification_option','0')->update($notifications_data);
        return $data;
      }

       /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

      public function notification_list_weekly(){
        $data = array();
        
            $data =  UserNotification::where('is_deleted','0')
            ->where('notification_option','1')
            ->get();
        return $data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public function notificationDetailUser($user_id){
        $data = array();
        if( $user_id != null )
            $data =  UserNotification::where('user_id',$user_id)
                        ->where('is_deleted','0')
                        ->where('is_read','0')
                        ->where('notification_option','0')
                        ->orderBy('id','DESC')
                        ->get();
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public function notificationDetailUserCount($user_id){
        $data = array();
        if( $user_id != null )
            $data =  UserNotification::where('user_id',$user_id)
                        ->where('is_deleted','0')
                        ->where('is_read','0')
                        ->where('notification_option','0')
                        ->orderBy('id','DESC')
                        ->get()->count();
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public static function weekly_send_notification($id,$from_user_id, $to_user_id, $notification_type, $extra_data) {
        
    if (is_object($extra_data)) {
        $extra_data = (array) $extra_data;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    $extra_notification_data = self::null_to_string($extra_data);
    $notification_type_data = config("constants.notification_type_data");

    if (array_key_exists($notification_type, $notification_type_data)) {
        $push_notification_data = collect($notification_type_data[$notification_type])
                ->put(
                        "extra_data",
                        collect($extra_notification_data)
                        ->mapWithKeys(function ($value, $key) {
                            return ["##" . strtoupper($key) . "##" => $value];
                        })
                        ->all()
                )
                ->all();
        $notifications_data = [
            "from_user_id" => $from_user_id,
            "user_id" => $to_user_id,
            "type" => $notification_type,
            "extra_data" => json_encode($extra_notification_data),
            "message" => $push_notification_data["body"],
            "is_read" => "0",
            "notification_option" => "0",
            "is_deleted" => "0",
        ];
     
  
        $extra_notification_data["notification_id"] = DB::table('user_notifications')->where('id', $id)->update($notifications_data);
        $where = [
            ["id", "=", $to_user_id],
            ["is_deleted", "=", "0"],
            ["device_token", "!=", ""],
            ["device_type", "!=", ""],
        ];
       

        $device_result = DB::table("users")
                ->select("device_token", "device_type")
                ->where($where)
                ->whereNotNull("device_token")
                ->whereNotNull("device_type");
        if ($device_result->count() > 0) {
            $device_result = $device_result->get()->first();

                
        
           if (!empty($device_result->device_token)) {
                $search = array_keys($push_notification_data["extra_data"]);
                $replace = array_values($push_notification_data["extra_data"]);

                if ($device_result->device_type == "android") {
                    self::send_android_push_notification($device_result->device_token, $push_notification_data["title"], str_replace($search, $replace, $push_notification_data["body"]), $notification_type, $extra_notification_data);
                } elseif($device_result->device_type == "ios") {
                    self::send_ios_push_notification($device_result->device_token, $push_notification_data["title"], str_replace($search, $replace, $push_notification_data["body"]), $notification_type, $extra_notification_data);
                }
            }
        }
        return $extra_notification_data["notification_id"];
    }
    return FALSE;
    }
 /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

public static function send_notification($from_user_id, $to_user_id, $notification_type, $extra_data,$church_id,$user_role_id) {
    
        if (is_object($extra_data)) {
            $extra_data = (array) $extra_data;
        }

        $extra_notification_data = self::null_to_string($extra_data);
        
        $where_user_setting = [
        ["user_id", "=", $to_user_id],
        ["field_name", "=", "NOTIFICATION_OF"],
        ];
        $where_user = [
        ["id", "=", $to_user_id]
        ];
        $device_result_user = DB::table("user_settings")
        ->select("field_name", "field_value")
        ->where($where_user_setting)->get()->first();
        if($device_result_user){
         $field_value = $device_result_user->field_value;
        }else{
         $field_value = '0';
        }
        
        $device_user = DB::table("users")
        ->select("user_role_id")
        ->where($where_user)->get()->first();
        
        $notification_type_data = config("constants.notification_type_data");
         if($field_value == '1' || $device_user->user_role_id == '3' || $device_user->user_role_id == '4'){
        if (array_key_exists($notification_type, $notification_type_data)) {
            $push_notification_data = collect($notification_type_data[$notification_type])
                    ->put(
                            "extra_data",
                            collect($extra_notification_data)
                            ->mapWithKeys(function ($value, $key) {
                                return ["##" . strtoupper($key) . "##" => $value];
                            })
                            ->all()
                    )
                    ->all();
            $notifications_data = [
                "from_user_id" => $from_user_id,
                "user_id" => $to_user_id,
                "type" => $notification_type,
                "extra_data" => json_encode($extra_notification_data),
                "message" => $push_notification_data["body"],
                "user_role_id" => $user_role_id,
                "church_id" => $church_id,
                "is_read" => "0",
                "is_deleted" => "0",
                "created_at" => now(),
            ];
            if($notification_type == 'reject_user_member' || $notification_type == 'approve_user_member' || $notification_type == 'delete_inactive_account' || $notification_type == 'change_primary_user_status'){
            $extra_notification_data["notification_id"] = '';   
            
            }else{
                $extra_notification_data["notification_id"] = DB::table('user_notifications')->insertGetId($notifications_data);
             
            }
            $where = [
                ["id", "=", $to_user_id],
                ["is_deleted", "=", "0"],
                ["device_token", "!=", ""],
                ["device_type", "!=", ""],
            ];
           

            $device_result = DB::table("users")
                    ->select("device_token", "device_type","user_role_id")
                    ->where($where)
                    ->whereNotNull("device_token")
                    ->whereNotNull("device_type");
            
            if ($device_result->count() > 0) {
                $device_result = $device_result->get()->first();

                 
            
               if (!empty($device_result->device_token)) {
                    $search = array_keys($push_notification_data["extra_data"]);
                    $replace = array_values($push_notification_data["extra_data"]);
                   
                    if ($device_result->device_type == "android") {

                        self::send_android_push_notification($device_result->device_token, $push_notification_data["title"], str_replace($search, $replace, $push_notification_data["body"]), $notification_type,$device_result->user_role_id, $extra_notification_data);
                    } elseif($device_result->device_type == "ios") {
                        self::send_ios_push_notification($device_result->device_token, $push_notification_data["title"], str_replace($search, $replace, $push_notification_data["body"]), $notification_type,$device_result->user_role_id, $extra_notification_data);
                    }
                }
            }
            return $extra_notification_data["notification_id"];
        }
    }else{
      if (array_key_exists($notification_type, $notification_type_data)) {
            $push_notification_data = collect($notification_type_data[$notification_type])
                    ->put(
                            "extra_data",
                            collect($extra_notification_data)
                            ->mapWithKeys(function ($value, $key) {
                                return ["##" . strtoupper($key) . "##" => $value];
                            })
                            ->all()
                    )
                    ->all();
            $notifications_data = [
                "from_user_id" => $from_user_id,
                "user_id" => $to_user_id,
                "type" => $notification_type,
                "extra_data" => json_encode($extra_notification_data),
                "message" => $push_notification_data["body"],
                "notification_option" => "1",
                "notification_type" => "Weekly",
                "user_role_id" => $user_role_id,
                "church_id" => $church_id,
                "is_read" => "0",
                "is_deleted" => "0",
                "created_at" => now(),
            ];

            if($notification_type == 'reject_user_member' || $notification_type == 'approve_user_member' || $notification_type == 'delete_inactive_account' || $notification_type == 'change_primary_user_status'){
            $extra_notification_data["notification_id"] = '';   
            
            }else{
                $extra_notification_data["notification_id"] = DB::table('user_notifications')->insertGetId($notifications_data);
             
            }

            return $extra_notification_data["notification_id"];
        }  
    }

        return FALSE;
    }
   
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public static function send_android_push_notification($token, $title, $body, $notification_type,$user_role_id, $extra_data = []) {

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => true,
        ];
    
        $extraNotificationData = array_merge($extra_data, ["title" => $title, "message" => $body, 'type' => $notification_type]);

        $fcmNotification = [
            'to' => $token,
            'notification' => $notification,
            'data' => $extraNotificationData
        ];
       
        if($user_role_id == 2 || $user_role_id == 5){
             $headers = [
        "Authorization: key=AAAAH1vGtWo:APA91bGxQIk-c3N6zYHX9Y_DQR1NkCdJ24TQ4JkX7B-ykfSkWMDaCxt8chaaWqBnr58_-i-LbOOj_4d234yBnUQdNJ4GJSrY7YBxMwMT6xqCbcbHabwypEs3Hx-RMMiRl0iCCoOKpD5O",
        //            "Authorization: key=AAAAW3A8qTc:APA91bFWICxSUu49okiJZGw2hr7-LFpl-vhpeI5dKFX1GZc5LmvGb-OUCmyfC16bvs033VKhufCXzU1JZqEAYYlYi1j1sCAytLmUbDcMMuCL5JLg87NMm3YVkiI-DChJH92KaTr6cs_5",
        "Content-Type: application/json"
        ]; 
            
      
        }else{
            
          $headers = [
            "Authorization: key=AAAAUR3Dn1M:APA91bGSeMhgWZvvjB8D_6YRoi3jnJLfHDe8yLjLJpgnzAcYf9hhAdIOLqYJM3qqkULxQtfUIPbX2RmtCJYqDVWSiudy0MffYAlAeH0dXqcYUk-oIa3tDG2PGCj5NX4R_dbVt2tQdMCs",
//            "Authorization: key=AAAAW3A8qTc:APA91bFWICxSUu49okiJZGw2hr7-LFpl-vhpeI5dKFX1GZc5LmvGb-OUCmyfC16bvs033VKhufCXzU1JZqEAYYlYi1j1sCAytLmUbDcMMuCL5JLg87NMm3YVkiI-DChJH92KaTr6cs_5",
            "Content-Type: application/json"
        ];  
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

        $result = curl_exec($ch);
     
        curl_close($ch);
        return $result;
    }
    
     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
     
    public static function send_ios_push_notification($token, $title, $body, $notification_type,$user_role_id,$extra_data = []) {
        $production = 'sandbox';
        $passphrase = 'agc@123';
        $pem_file = __DIR__ . "/ios-push-notification-certificate/QKDM_APNS_Development.pem";
        $gateway = 'ssl://gateway.sandbox.push.apple.com:2195';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pem_file);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client($gateway, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            exit("Failed to connect: {$err} {$errstr}" . PHP_EOL);
        }
        $data = array_merge($extra_data, [
            "type" => $notification_type,
            "title" => $title,
            "message" => $body
        ]);
        $notification['aps'] = array(
            'alert' => array(
                'body' => $body,
                'data' => $data,
                'action-loc-key' => 'QKDM',
            ),
            'badge' => 2,
            'sound' => 'oven.caf',
        );
        $payload = json_encode($notification);
        $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);
        return $result;
    }

     public static function null_to_string($data = null, $default = "") {
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (is_array($data) && count($data) > 0) {
            return array_map(function ($val) use ($default) {
                return (is_array($val) || is_object($val)) ? self::null_to_string($val, $default) : ((is_null($val)) ? $default : $val);
            }, (array) $data);
        } else if (is_array($data) && count($data) <= 0) {
            return $data;
        }
        return (is_null($data)) ? $default : $data;
    }

}
