<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTaskAssign extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'assign_user_id', 'status','recurring_id','is_admin','is_deleted','created_at','updated_at'
    ];

    /**
     * Does something interesting
     *
     * Showing relationship between task and user
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function UserTaskAssign(){
        return $this->belongsTo('App\User','id','user_id');
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

   public function userassigndetail($id){
     $data = array();
     $data =  UserTaskAssign::where('recurring_id',$id)->get();
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

   public function get_task_assign_by_id($id,$recurring_id){


        $data =   UserTaskAssign::where('id',$id)   
        ->where('recurring_id',$recurring_id)                     
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

  public function get_task_assign($user_id,$task_id){
        $data =  \DB::table('user_task_assigns')
        ->where('is_deleted','0')
        ->where('assign_user_id',$user_id)                        
        ->where('task_id',$task_id)->count();
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

  public function get_task_assign_notification($user_id,$task_id){
        $data =  \DB::table('user_task_assigns')
        ->where('is_deleted','0')
        ->where('assign_user_id',$user_id)                        
        ->where('task_id',$task_id)->first();
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

    public function get_task_assign_withkey($user_id,$task_id,$taskrecurringto){
        $data =  \DB::table('user_task_assigns')
        ->where('is_deleted','0')
        ->where('assign_user_id',$user_id)                        
        ->where('recurring_id',$taskrecurringto)                        
        ->where('task_id',$task_id)->count();
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
    
    public function update_task_assign_admin_status($task_id,$taskrecurringto){
            $this::where('task_id', $task_id)
            ->where('recurring_id',$taskrecurringto)
            ->update(['is_admin'=>'N']); 
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

    public function get_task_assign_withkey_data($user_id,$task_id,$taskrecurringto){
        $data =  \DB::table('user_task_assigns')
        ->where('is_deleted','0')
        ->where('assign_user_id',$user_id)                        
        ->where('recurring_id',$taskrecurringto)                        
        ->where('task_id',$task_id)->first();
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

     public function get_task_assign_list($task_id,$user_id){
        $data =  \DB::table('user_task_assigns')
        ->where('is_deleted','0')
        ->where('assign_user_id',$user_id)                        
        ->where('task_id',$task_id)->get();
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

       public function task_list_with_id($task_id){
        $data =  \DB::table('tasks')
        ->where('is_deleted','0')
        ->where('id',$task_id)->first();
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

    public function single_get_task_assign_list($task_id,$user_id){
       $data = array();
       
        $data =  \DB::table('user_task_assigns as uts')
        ->select('uts.*')
        ->join('task_recurrings as tr','tr.id','=','uts.recurring_id')
        ->where('uts.is_deleted','0')
        ->where('uts.assign_user_id',$user_id)  
        ->where('uts.status','!=','reject')  
        ->whereDate('tr.recurring_date','>=',date('Y-m-d'))                      
        ->where('uts.task_id',$task_id)->get();
        
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

     public function delete_member($task_id){
        $data = array();
        if( $task_id  !=  null )
            $data =  UserTaskAssign::where('task_id',$task_id)                        
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

     public function count_complition($task_id,$recurring_id){
        $data = array();
        if( $task_id  !=  null )
            $data =  UserTaskAssign::where('task_id',$task_id)
                        ->where('recurring_id',$recurring_id)
                         ->where(function ($query){
                        $query->where('status', '=', 'pending')
                        ->orWhere('status', '=', 'active');
                        })                       
                        ->count();
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

     public function count_totalassign($type,$task_id,$recurring_id){
        $data = array();
        if( $task_id  !=  null )
            if($type == '1'){
            $data =  UserTaskAssign::where('task_id',$task_id)
                        ->where('recurring_id',$recurring_id)
                        ->count();
            }else if($type == '2'){
            $data =  UserTaskAssign::where('task_id',$task_id)
            ->where('recurring_id',$recurring_id)
            ->where(function ($query){
            $query->where('status', '=', 'complete');
            })                       
            ->count();
            }
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
    
    public function delete_member_user($user_id,$task_id){
       
         $data =  \DB::table('user_task_assigns')
         ->select('assign_user_id')
        ->where('is_deleted','0')
        ->where('task_id',$task_id)->get()->toArray();
        $uservale2 = array();
        foreach ($data as $key => $value) {
            $uservale2[] = $value->assign_user_id;
           
        }
        
        $result=array_diff($uservale2,$user_id);
        
        if($result){
            foreach ($result as $key => $value) {
             
               $data =  UserTaskAssign::where('assign_user_id',$value)->where('task_id',$task_id)                     
                        ->delete();
            }
        }
        
       /* $data = array();
        if( $task_id  !=  null )
            $data =  UserTaskAssign::where('task_id',$task_id)                        
                        ->delete();
        return $data;*/
    }

    


}
